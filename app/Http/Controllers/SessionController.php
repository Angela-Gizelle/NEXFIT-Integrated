<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberCreditBalance;
use App\Models\Session;
use App\Models\SessionCreditAdjustment;
use App\Models\Trainer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Session Management module — REQ-SI-01..07 (Session Inventory Tracking).
 *
 * Staff/Admin management routes are gated behind the `auth:staff` guard
 * at the route level (see routes/session_routes.php); the trainer's own
 * read-only view (trainerIndex) sits behind `auth:trainer` instead.
 * Admin-only actions (adjustCredit) additionally check Staff::isAdmin().
 */
class SessionController extends Controller
{
    /**
     * The studio's fixed daily session slots.
     * Kept in sync with resources/views/sessions/create.blade.php.
     */
    public static function slots(): array
    {
        return [
            ['time' => '09:00:00', 'label' => '9:00 – 10:00 AM'],
            ['time' => '10:15:00', 'label' => '10:15 – 11:15 AM'],
            ['time' => '17:00:00', 'label' => '5:00 – 6:00 PM'],
        ];
    }

    /**
     * The 7 Carbon dates (Monday–Sunday) for the week containing $date.
     *
     * @return \Illuminate\Support\Collection<int, \Carbon\Carbon>
     */
    public static function weekDates(Carbon $date): \Illuminate\Support\Collection
    {
        $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);

        return collect(range(0, 6))->map(fn ($i) => $weekStart->copy()->addDays($i));
    }

    // -------------------------------------------------------------------------
    // Manage Bookings / Sessions list
    // -------------------------------------------------------------------------
    public function index(Request $request)
    {
        $query = Session::with(['member', 'trainer', 'packageSale.sessionPackage'])
            ->orderByDesc('session_date')
            ->orderBy('session_time');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('trainer_id')) {
            $query->where('trainer_id', $request->trainer_id);
        }
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }
        if ($request->filled('date')) {
            $query->whereDate('session_date', $request->date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('member', fn ($q) => $q->where('full_name', 'like', "%$search%"));
        }

        $sessions   = $query->paginate(10)->withQueryString();
        $trainers   = Trainer::orderBy('name')->get();
        $totalCount = Session::count();

        return view('sessions.index', compact('sessions', 'trainers', 'totalCount'));
    }

    // -------------------------------------------------------------------------
    // Create new session booking
    // -------------------------------------------------------------------------
    public function create()
    {
        $members  = Member::where('status', 'Active')->orderBy('full_name')->get();
        $trainers = Trainer::orderBy('name')->get();
        return view('sessions.create', compact('members', 'trainers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'          => 'required|exists:members,id',
            'trainer_id'         => 'required|exists:trainers,id',
            'backup_trainer_id'  => 'nullable|exists:trainers,id',
            'program'            => 'required|in:Pilates,Personal Training,Open Gym Access',
            'level'              => 'nullable|in:Fundamentals,Mid-Level,Advanced',
            'session_date'       => 'required|date|after_or_equal:today',
            'session_time'       => 'required',
            'remarks'            => 'nullable|string|max:500',
        ]);

        // Check that member has credits (REQ-SI-02)
        $balance = MemberCreditBalance::where('member_id', $validated['member_id'])->first();
        if (!$balance || !$balance->hasCredits()) {
            return back()->withErrors(['member_id' => 'This member has no remaining session credits. Please record a package sale first.'])->withInput();
        }

        // Reject booking a slot that's already in the past — the UI hides
        // these, but guard here too since forms can be posted directly.
        $slotDateTime = Carbon::parse($validated['session_date'] . ' ' . $validated['session_time']);
        if ($slotDateTime->isPast()) {
            return back()->withErrors(['session_time' => 'That slot has already passed and can no longer be booked.'])->withInput();
        }

        // Check trainer availability — prevent double-booking
        $conflict = Session::where('trainer_id', $validated['trainer_id'])
            ->whereDate('session_date', $validated['session_date'])
            ->where('session_time', $validated['session_time'])
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['trainer_id' => 'This trainer already has a session booked at that date and time.'])->withInput();
        }

        Session::create(array_merge($validated, ['status' => 'confirmed']));

        return redirect()->route('session-management.index')->with('success', 'Session booked successfully.');
    }

    // -------------------------------------------------------------------------
    // Session detail view
    // -------------------------------------------------------------------------
    public function show(Session $session)
    {
        $session->load(['member', 'trainer', 'backupTrainer', 'packageSale.sessionPackage', 'conductedBy', 'cancelledBy']);
        return view('sessions.show', compact('session'));
    }

    // -------------------------------------------------------------------------
    // Mark session as Conducted (REQ-SI-02)
    // -------------------------------------------------------------------------
    public function markConducted(Session $session)
    {
        if (!in_array($session->status, ['confirmed', 'pending'])) {
            return back()->withErrors(['error' => 'Only confirmed or pending sessions can be marked as conducted.']);
        }

        DB::transaction(function () use ($session) {
            $balance = MemberCreditBalance::where('member_id', $session->member_id)->firstOrFail();
            $balance->deductCredit();

            $session->update([
                'status'       => 'conducted',
                'conducted_at' => now(),
                'conducted_by' => Auth::guard('staff')->id(),
            ]);
        });

        return back()->with('success', 'Session marked as conducted and credit deducted.');
    }

    // -------------------------------------------------------------------------
    // Cancel session
    // -------------------------------------------------------------------------
    public function cancel(Request $request, Session $session)
    {
        if ($session->status === 'cancelled') {
            return back()->withErrors(['error' => 'Session is already cancelled.']);
        }

        $session->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => Auth::guard('staff')->id(),
            'remarks'      => $request->input('reason', $session->remarks),
        ]);

        return back()->with('success', 'Session cancelled.');
    }

    // -------------------------------------------------------------------------
    // Restore a cancelled session (credit returned, session set back to confirmed)
    // -------------------------------------------------------------------------
    public function restore(Session $session)
    {
        if ($session->status !== 'cancelled') {
            return back()->withErrors(['error' => 'Only cancelled sessions can be restored.']);
        }

        DB::transaction(function () use ($session) {
            if (!$session->credit_restored) {
                $balance = MemberCreditBalance::where('member_id', $session->member_id)->firstOrFail();
                $balance->restoreCredit();
            }

            $session->update([
                'status'          => 'confirmed',
                'cancelled_at'    => null,
                'cancelled_by'    => null,
                'credit_restored' => false,
            ]);
        });

        return back()->with('success', 'Session restored and credit returned.');
    }

    // -------------------------------------------------------------------------
    // Session Inventory — per-member credit overview (REQ-SI-03, REQ-SI-07)
    // -------------------------------------------------------------------------
    public function inventory(Request $request)
    {
        $query = MemberCreditBalance::with('member')
            ->join('members', 'member_credit_balances.member_id', '=', 'members.id');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('members.full_name', 'like', "%$s%");
        }

        $balances = $query->select('member_credit_balances.*')
            ->paginate(15)
            ->withQueryString();

        // -------------------------------------------------------------
        // Booking calendar (same widget as the bookings page) so staff
        // can redeem a member's credit for a session directly here.
        // -------------------------------------------------------------
        $date      = $request->filled('date')
            ? Carbon::parse($request->date)
            : Carbon::today();
        $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd   = $weekStart->copy()->addDays(6);
        $weekDates = self::weekDates($date);

        $trainers = Trainer::orderBy('name')->get();
        $slots    = self::slots();

        $selectedTrainerId = $request->filled('trainer_id')
            ? (int) $request->trainer_id
            : optional($trainers->first())->id;
        $selectedTrainer = $trainers->firstWhere('id', $selectedTrainerId);

        $sessions = Session::with('member')
            ->where('trainer_id', $selectedTrainerId)
            ->whereBetween('session_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->get()
            ->keyBy(fn ($s) => $s->session_date->toDateString() . '_' . $s->session_time);

        $members = Member::where('status', 'Active')
            ->whereHas('creditBalance', fn ($q) => $q->where('credits_remaining', '>', 0))
            ->with('creditBalance')
            ->orderBy('full_name')
            ->get();

        return view('sessions.inventory', compact(
            'balances', 'date', 'weekStart', 'weekEnd', 'weekDates',
            'trainers', 'selectedTrainerId', 'selectedTrainer', 'slots', 'sessions', 'members'
        ));
    }

    // -------------------------------------------------------------------------
    // Manual credit adjustment — admin only (REQ-SI-05)
    // -------------------------------------------------------------------------
    public function adjustCredit(Request $request, Member $member)
    {
        if (!auth('staff')->user()?->isAdmin()) {
            abort(403, 'Only admins can adjust member session credits.');
        }

        $validated = $request->validate([
            'adjustment_amount' => 'required|integer|not_in:0',
            'reason'            => 'required|string|min:5|max:255',
        ]);

        DB::transaction(function () use ($validated, $member) {
            $balance = MemberCreditBalance::firstOrCreate(
                ['member_id' => $member->id],
                ['credits_purchased' => 0, 'credits_conducted' => 0, 'credits_remaining' => 0, 'credits_forfeited' => 0]
            );

            $before = $balance->credits_remaining;
            $after  = max(0, $before + $validated['adjustment_amount']);

            $balance->update(['credits_remaining' => $after]);

            SessionCreditAdjustment::create([
                'member_id'         => $member->id,
                'adjustment_amount' => $validated['adjustment_amount'],
                'credits_before'    => $before,
                'credits_after'     => $after,
                'reason'            => $validated['reason'],
                'adjusted_by'       => Auth::guard('staff')->id(),
                'adjusted_at'       => now(),
            ]);
        });

        return back()->with('success', 'Credit balance adjusted and logged.');
    }

    // -------------------------------------------------------------------------
    // Trainer-facing session list (read-only, their sessions only)
    // -------------------------------------------------------------------------
    public function trainerIndex(Request $request)
    {
        $date      = $request->filled('date')
            ? Carbon::parse($request->date)
            : Carbon::today();
        $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd   = $weekStart->copy()->addDays(6);
        $weekDates = self::weekDates($date);
        $slots     = self::slots();

        // This trainer's own sessions for the visible week, keyed by
        // "Y-m-d_H:i:s" — cancelled/no-show are kept here (unlike the
        // booking grid) so the trainer can still see what happened to a
        // slot, just read-only.
        $sessions = Session::with('member')
            ->where('trainer_id', Auth::guard('trainer')->id())
            ->whereBetween('session_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get()
            ->keyBy(fn ($s) => $s->session_date->toDateString() . '_' . $s->session_time);

        return view('sessions.trainer-index', compact(
            'date', 'weekStart', 'weekEnd', 'weekDates', 'slots', 'sessions'
        ));
    }
}
