<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberCreditBalance;
use App\Models\SessionPackage;
use App\Models\SessionPackageSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MembershipSignupController extends Controller
{
    // Mirrors App\Http\Controllers\MemberController::PACKAGE_DURATIONS —
    // kept in sync so staff-side and self-service enrollment agree on
    // what each plan means.
    public const PLANS = [
        'Single Session' => ['days' => 1,   'price' => 350,   'credits' => 1],
        'Monthly'         => ['days' => 30,  'price' => 1500,  'credits' => 12],
        '3-Month'         => ['days' => 90,  'price' => 4200,  'credits' => 36],
        '6-Month'         => ['days' => 180, 'price' => 7800,  'credits' => 72],
        'Annual'          => ['days' => 365, 'price' => 14400, 'credits' => 144],
        'Student Rate'    => ['days' => 30,  'price' => 1200,  'credits' => 12],
        'PWD Rate'        => ['days' => 30,  'price' => 1200,  'credits' => 12],
    ];

    /**
     * Plan-selection page for the "Yes, I Want Membership" path from
     * /booking/membership. Requires login so we know who to attach the
     * membership to.
     */
    public function show()
    {
        // Already a member — no need to sign up again.
        if (Auth::user()->member()->exists()) {
            return redirect()->route('member.dashboard');
        }

        return view('member.join', ['plans' => self::PLANS]);
    }

    /**
     * Creates the member record (and their first package) for the
     * logged-in user, then sends them straight to their new dashboard —
     * this is the write that was missing before: becoming a member now
     * actually lands a row in `members`, the same way registering lands
     * a row in `users` or booking lands a row in `bookings`.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->member()->exists()) {
            return redirect()->route('member.dashboard');
        }

        $request->validate([
            'package_type'    => 'required|string|in:' . implode(',', array_keys(self::PLANS)),
            'phone'           => 'nullable|string|max:20',
            'birthdate'       => 'nullable|date',
            'address'         => 'nullable|string',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'payment_mode'    => 'required|string',
        ]);

        $plan = self::PLANS[$request->package_type];

        $member = DB::transaction(function () use ($user, $request, $plan) {
            $member = Member::create([
                'user_id'                 => $user->id,
                'full_name'                => $user->name,
                'email'                    => $user->email,
                'phone'                    => $request->phone,
                'birthdate'                => $request->birthdate,
                'address'                  => $request->address,
                'emergency_contact_name'   => $request->emergency_contact_name,
                'emergency_contact_phone'  => $request->emergency_contact_phone,
                'enrollment_date'          => now(),
                'fitness_level'            => 'Fundamentals',
                'population_class'         => 'General',
                'status'                   => 'Active',
            ]);

            $coverageStart = Carbon::now();
            $coverageEnd   = $coverageStart->copy()->addDays($plan['days']);

            $member->packages()->create([
                'package_type'    => $request->package_type,
                'purchase_date'   => $coverageStart,
                'coverage_start'  => $coverageStart,
                'coverage_end'    => $coverageEnd,
                'session_credits' => $plan['credits'],
                'credits_used'    => 0,
                'amount_paid'     => $plan['price'],
                'payment_mode'    => $request->payment_mode,
                'processed_by'    => null, // self-service, not staff-processed
                'status'          => 'Active',
            ]);

            // ── Session Management module: record the package they availed ──
            // (SRS 4.3.3.2 REQ-SP-01/05) so the credits are trackable through
            // the Session Management module, not just the Membership record.
            // The membership plan itself isn't tied to a single program, so
            // we keep/create a matching catalog entry under "Open Gym Access"
            // rather than forcing a Pilates/PT-specific package onto it.
            $sessionPackage = SessionPackage::firstOrCreate(
                ['name' => $request->package_type . ' Membership Plan'],
                [
                    'type'            => $request->package_type,
                    'program'         => 'Open Gym Access',
                    'session_credits' => $plan['credits'],
                    'validity_days'   => $plan['days'],
                    'base_price'      => $plan['price'],
                    'is_active'       => true,
                    'description'     => 'Auto-generated from the self-service membership signup plan.',
                ]
            );

            $paymentMode = in_array($request->payment_mode, ['cash', 'gcash', 'bank_transfer'], true)
                ? $request->payment_mode
                : 'other';

            SessionPackageSale::create([
                'member_id'           => $member->id,
                'session_package_id'  => $sessionPackage->id,
                'pricing_type'        => in_array($request->package_type, ['Student Rate', 'PWD Rate'], true)
                    ? (str_contains($request->package_type, 'Student') ? 'student' : 'pwd')
                    : 'standard',
                'amount_paid'         => $plan['price'],
                'payment_mode'        => $paymentMode,
                'sale_type'           => 'new_enrollment',
                // Self-service signup — no staff member processed this, so the
                // member's own account is recorded as who logged the sale.
                'processed_by'        => $user->id,
                'sale_date'           => $coverageStart->toDateString(),
                'sale_time'           => $coverageStart->toTimeString(),
                'notes'               => 'Recorded automatically via self-service membership signup.',
            ]);

            $balance = MemberCreditBalance::firstOrCreate(
                ['member_id' => $member->id],
                ['credits_purchased' => 0, 'credits_conducted' => 0, 'credits_remaining' => 0, 'credits_forfeited' => 0]
            );
            $balance->addCredits($plan['credits']);

            return $member;
        });

        return redirect()->route('member.dashboard')
            ->with('success', 'Welcome to Fit Urban! Your membership is now active.');
    }
}