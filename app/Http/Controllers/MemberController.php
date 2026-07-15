<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberPackage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class MemberController extends Controller
{
    // REQ-MM-09: search and filter by name, package type, status, fitness level, population class
    public function index(Request $request)
    {
        // Safety-net auto-expiry: keeps member/package status accurate even if the
        // scheduled `memberships:expire` cron job is unavailable or delayed on the
        // host. This is intentionally cheap — it only touches Active packages whose
        // coverage_end has already passed, so on a normal day it does nothing.
        Artisan::call('memberships:expire');

        $query = Member::with(['trainer', 'activePackage', 'healthFlags']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('fitness_level')) {
            $query->where('fitness_level', $request->fitness_level);
        }
        if ($request->filled('population_class')) {
            $query->where('population_class', $request->population_class);
        }
        if ($request->filled('package_type')) {
            $query->whereHas('packages', function ($q) use ($request) {
                $q->where('package_type', $request->package_type);
            });
        }

        $members = $query->orderBy('id', 'asc')->get();

        $activeCount   = Member::where('status', 'Active')->count();
        $inactiveCount = Member::where('status', 'Inactive')->count();

        // REQ-MM-04: count of expired/inactive memberships shown on the "Expired
        // Memberships" card. This intentionally mirrors $inactiveCount (and the
        // card's own click-to-filter, which matches on rowStatus === 'Inactive'):
        // the memberships:expire call above already flips a member's status to
        // Inactive the moment their last Active package lapses, so by this point
        // "status=Active with only Expired packages" can never occur — checking
        // for it here would always yield 0 regardless of how many members are
        // actually overdue for renewal.
        $expiredCount = $inactiveCount;

        // Expiring today — active packages whose end_date is today
        $expiringTodayCount = Member::where('status', 'Active')
            ->whereHas('packages', function ($q) {
                $q->where('status', 'Active')
                  ->whereDate('coverage_end', now()->toDateString());
            })->count();

        // Expiring within 7 days (includes today)
        $expiringWeekCount = Member::where('status', 'Active')
            ->whereHas('packages', function ($q) {
                $q->where('status', 'Active')
                  ->whereBetween('coverage_end', [
                      now()->toDateString(),
                      now()->addDays(7)->toDateString(),
                  ]);
            })->count();

        return view('staff.members.index', compact(
            'members', 'activeCount', 'inactiveCount',
            'expiredCount', 'expiringTodayCount', 'expiringWeekCount'
        ));
    }

    public function create()
    {
        $trainers = \App\Models\Trainer::orderBy('name')->get();
        return view('staff.members.create', compact('trainers'));
    }

    public const PACKAGE_DURATIONS = [
        'Single Session' => 1,
        'Monthly'         => 30,
        '3-Month'         => 90,
        '6-Month'         => 180,
        'Annual'          => 365,
        'Student Rate'    => 30,
        'PWD Rate'        => 30,
    ];

    // REQ-MM-01 & REQ-MM-02: create member + first package
    public function store(Request $request)
    {
        $request->validate([
            'full_name'               => 'required|string|max:255',
            'email'                   => 'required|email|unique:members',
            'phone'                   => 'nullable|string|max:20',
            'birthdate'               => 'nullable|date',
            'address'                 => 'nullable|string',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'enrollment_date'         => 'required|date',
            'fitness_level'           => 'required|in:Fundamentals,Mid-Level,Advanced',
            'population_class'        => 'required|in:General,Special',
            'status'                  => 'required|in:Active,Inactive,Churned',
            'assigned_trainer_id'     => 'nullable|exists:trainers,id',
            // package fields
            'package_type'   => 'required|string',
            'coverage_start' => 'required|date',
            'session_credits' => 'required|integer|min:1',
            'amount_paid'    => 'required|numeric|min:0',
            'payment_mode'   => 'required|string',
        ]);

        $member = Member::create(array_merge(
            $request->only([
                'full_name', 'email', 'phone', 'birthdate', 'address',
                'emergency_contact_name', 'emergency_contact_phone',
                'enrollment_date', 'fitness_level', 'population_class', 'status',
                'assigned_trainer_id',
            ]),
            // Auto-link to a login account with a matching email, if one
            // exists. This is what makes the member dashboard reachable
            // right after enrollment — no manual linking required.
            ['user_id' => \App\Models\User::where('email', $request->email)->value('id')]
        ));

        $purchaseDate = Carbon::parse($request->coverage_start);
        $coverageEnd  = $purchaseDate->copy()->addDays(self::PACKAGE_DURATIONS[$request->package_type]);

        // REQ-MM-02: record first package — status is Active by default
        $member->packages()->create([
            'package_type'    => $request->package_type,
            'purchase_date'   => $request->coverage_start,
            'coverage_start'  => $request->coverage_start,
            'coverage_end'    => $coverageEnd,
            'session_credits' => $request->session_credits,
            'credits_used'    => 0,
            'amount_paid'     => $request->amount_paid,
            'payment_mode'    => $request->payment_mode,
            'processed_by'    => auth('staff')->id(),
            'status'          => 'Active',
        ]);

        return redirect()->route('members.show', $member)
            ->with('success', 'Member enrolled successfully.');
    }

    public function show(Member $member)
    {
        $member->load(['trainer', 'packages', 'healthFlags', 'parqResponses', 'activePackage']);
        return view('staff.members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('staff.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'full_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:members,email,' . $member->id,
            'fitness_level'    => 'required|in:Fundamentals,Mid-Level,Advanced',
            'population_class' => 'required|in:General,Special',
            'status'           => 'required|in:Active,Inactive,Churned',
        ]);

        $member->update(array_merge(
            $request->only([
                'full_name', 'email', 'phone', 'birthdate', 'address',
                'emergency_contact_name', 'emergency_contact_phone',
                'fitness_level', 'population_class', 'status',
            ]),
            // Keep the account link in sync with the email on file —
            // covers both an email correction and the case where the
            // member registers a login account after being enrolled.
            ['user_id' => \App\Models\User::where('email', $request->email)->value('id')]
        ));

        return redirect()->route('members.show', $member)
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Manual trigger: evaluate member_packages and sync members.status.
     * Mirrors the logic of the scheduled Artisan command.
     */
    public function expireMemberships()
    {
        $expiredPackages = MemberPackage::where('status', 'Active')
            ->whereDate('coverage_end', '<', now()->toDateString())
            ->get();

        $packageCount = $expiredPackages->count();

        foreach ($expiredPackages as $pkg) {
            $pkg->update(['status' => 'Expired']);

            // If the member now has no Active packages left, set them Inactive.
            $member = $pkg->member;
            $hasActivePackage = $member->packages()
                ->where('status', 'Active')
                ->exists();

            if (!$hasActivePackage) {
                $member->update(['status' => 'Inactive']);
            }
        }

        $message = $packageCount > 0
            ? "{$packageCount} membership package(s) have been marked as Expired."
            : 'No expired memberships found.';

        return redirect()->route('members.index')->with('success', $message);
    }

    /**
     * REQ-MM-RENEW: Show the renewal form for a member.
     * Only shown when the member's latest package is within 7 days of
     * expiry or has already expired.
     */
    public function renewForm(Member $member)
    {
        $member->load(['packages', 'activePackage']);
        return view('staff.members.renew', compact('member'));
    }

    /**
     * REQ-MM-RENEW: Process membership renewal.
     * Creates a NEW member_package record; existing records are never overwritten.
     */
    public function renew(Request $request, Member $member)
    {
        $request->validate([
            'package_type'    => 'required|string|in:' . implode(',', array_keys(self::PACKAGE_DURATIONS)),
            'coverage_start'  => 'required|date',
            'session_credits' => 'required|integer|min:1',
            'amount_paid'     => 'required|numeric|min:0',
            'payment_mode'    => 'required|string',
        ]);

        $coverageStart = Carbon::parse($request->coverage_start);
        $coverageEnd   = $coverageStart->copy()->addDays(self::PACKAGE_DURATIONS[$request->package_type]);

        // A renewal supersedes any package(s) still marked Active — close them out
        // first so member_packages.status never has more than one Active row at a
        // time. Without this, activePackage() (hasOne latest coverage_start) can
        // resolve to the wrong package when coverage_start dates tie or the old
        // package hasn't hit its coverage_end yet.
        $member->packages()
            ->where('status', 'Active')
            ->update(['status' => 'Expired']);

        // Create a new package record — previous records are preserved for history.
        $member->packages()->create([
            'package_type'    => $request->package_type,
            'purchase_date'   => $coverageStart,
            'coverage_start'  => $coverageStart,
            'coverage_end'    => $coverageEnd,
            'session_credits' => $request->session_credits,
            'credits_used'    => 0,
            'amount_paid'     => $request->amount_paid,
            'payment_mode'    => $request->payment_mode,
            'processed_by'    => auth('staff')->id(),
            'status'          => 'Active',
        ]);

        // Renewal always restores the member to Active.
        $member->update(['status' => 'Active']);

        return redirect()->route('members.show', $member)
            ->with('success', 'Membership renewed successfully.');
    }

    // REQ SR-2: soft delete only — no permanent deletion
    public function destroy(Member $member)
    {
        $member->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['message' => 'Member archived successfully.']);
        }

        return redirect()->route('members.index')
            ->with('success', 'Member archived successfully.');
    }
}