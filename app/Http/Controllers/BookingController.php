<?php
// NEW FILE: app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\AuditLog;
use App\Models\Session;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // ── Step 4: Confirm and store booking ───────────────────────
    public function store(Request $request)
    {
       $request->validate([
    'fitness_assessment_id'          => 'required|exists:fitness_assessments,id',
    'service_id'                     => 'required|exists:services,id',
    'schedule_id'                    => 'nullable|exists:schedules,id',
    'booking_date'                   => 'required|date|after_or_equal:today',
    'booking_time'                   => 'required',
    'medical_clearance_acknowledged' => 'accepted',
    'rights_policy_acknowledged'     => 'accepted',
    'member_declaration_signed'      => 'accepted',
]);

        // ── Real-time slot check (skip for Open Gym — no schedule_id) ───
$schedule = null;
if ($request->schedule_id) {
    $schedule = Schedule::lockForUpdate()->find($request->schedule_id);

    if (!$schedule || $schedule->is_full) {
        $nextSlot = Schedule::where('service_id', $request->service_id)
            ->where('trainer_id', $schedule?->trainer_id)
            ->where('date', '>=', now()->toDateString())
            ->where('is_full', false)
            ->where('is_active', true)
            ->orderBy('date')
            ->orderBy('start_time')
            ->first();

        return back()->with([
            'error'     => 'Sorry, that slot is no longer available.',
            'next_slot' => $nextSlot,
        ]);
    }
}
        // ── Create booking in a transaction ──────────────────────
        $booking = DB::transaction(function () use ($request, $schedule) {

            $booking = Booking::create([
                'fitness_assessment_id'          => $request->fitness_assessment_id,
                'service_id'                     => $request->service_id,
                'trainer_id'                     => $request->trainer_id,
                'backup_trainer_id'              => $request->backup_trainer_id,
                'program_id'                     => $request->program_id,
                'schedule_id'                    => $request->schedule_id,
                'booking_date'                   => $request->booking_date,
                'booking_time'                   => $request->booking_time,
                'status'                         => 'Confirmed',
                'medical_clearance_acknowledged' => true,
                'rights_policy_acknowledged'     => true,
                'member_declaration_signed'      => true,
                'notes'                          => $request->notes,
            ]);

            // Decrement available slot count
            if ($schedule) {
             $schedule->reserve();
        }

            // Audit log
            AuditLog::record('created', $booking, null, $booking->toArray());

            // ── Session Management module: record the session they availed ──
            // (SRS 4.3.3.1 REQ-SI-01/04) — only when this booking belongs to
            // someone already enrolled as a member (Membership module).
            // Public/prospective bookings (no member record yet) have
            // nothing to attach a training_session to, so they're left as
            // booking-only until that person actually becomes a member.
            $member = $booking->assessment?->user?->member;

            if ($member) {
                Session::create([
                    'member_id'          => $member->id,
                    'trainer_id'         => $booking->trainer_id,
                    'backup_trainer_id'  => $booking->backup_trainer_id,
                    'booking_id'         => $booking->id,
                    'program'            => $booking->service?->name ?? 'Open Gym Access',
                    'level'              => $member->fitness_level,
                    'session_date'       => $booking->booking_date,
                    'session_time'       => $booking->booking_time,
                    'status'             => 'confirmed',
                    'remarks'            => 'Recorded automatically from the online booking flow.',
                ]);
            }

            return $booking;
        });

        return redirect('/booking/confirmation/' . $booking->id)
            ->with('success', 'Your booking is confirmed!');
    }

    // ── Step 5: Show confirmation page ───────────────────────────
    public function show(int $id)
    {
        $booking = Booking::with([
            'assessment', 'service',
            'trainer', 'backupTrainer',
            'program', 'schedule',
        ])->findOrFail($id);

        return view('booking.confirmation', compact('booking'));
    }

    // ── Cancel booking ───────────────────────────────────────────
    public function cancel(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);
        $old     = $booking->toArray();

        DB::transaction(function () use ($booking, $request, $old) {

            $booking->update([
                'status' => 'Cancelled',
                'notes'  => $request->reason,
            ]);

            // Release the slot back
            if ($booking->schedule) {
                $booking->schedule->release();
            }

            // Keep the Session Management record (if one exists for this
            // booking) in sync — REQ-SI-06 per-session remarks.
            Session::where('booking_id', $booking->id)
                ->whereNotIn('status', ['conducted', 'cancelled'])
                ->update([
                    'status'  => 'cancelled',
                    'remarks' => $request->reason,
                ]);

            AuditLog::record('cancelled', $booking, $old, $booking->fresh()->toArray());
        });

        return back()->with('success', 'Booking cancelled successfully.');
    }

    // ── Reschedule booking ───────────────────────────────────────
    public function reschedule(Request $request, int $id)
    {
        $request->validate([
            'schedule_id'  => 'required|exists:schedules,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
        ]);

        $booking     = Booking::findOrFail($id);
        $newSchedule = Schedule::lockForUpdate()->findOrFail($request->schedule_id);

        if ($newSchedule->is_full) {
            return back()->with('error', 'The selected slot is already full.');
        }

        $old = $booking->toArray();

        DB::transaction(function () use ($booking, $newSchedule, $request, $old) {

            // Release old slot
            if ($booking->schedule) {
                $booking->schedule->release();
            }

            $booking->update([
                'schedule_id'  => $newSchedule->id,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'status'       => 'Rescheduled',
            ]);

            $newSchedule->reserve();

            // Keep the Session Management record (if one exists for this
            // booking) in sync with the new date/time.
            Session::where('booking_id', $booking->id)
                ->whereNotIn('status', ['conducted', 'cancelled'])
                ->update([
                    'session_date' => $request->booking_date,
                    'session_time' => $request->booking_time,
                    'status'       => 'rescheduled',
                ]);

            AuditLog::record('rescheduled', $booking, $old, $booking->fresh()->toArray());
        });

        return back()->with('success', 'Booking rescheduled successfully.');
    }
}
