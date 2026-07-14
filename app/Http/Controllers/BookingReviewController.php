<?php
// NEW FILE: app/Http/Controllers/BookingReviewController.php
//
// The new 4-step design splits "pick a slot" and "review & confirm"
// into two separate pages. This controller renders the review step:
// it takes the slot/program/trainer choice from program.blade.php's
// form submission and shows it back to the member for confirmation
// before the real Booking::create() happens in BookingController.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FitnessAssessment;
use App\Models\Service;
use App\Models\Trainer;
use App\Models\Program;

class BookingReviewController extends Controller
{
    public function show(Request $request)
    {
        $assessment = FitnessAssessment::findOrFail($request->fitness_assessment_id);
        $service    = Service::findOrFail($request->service_id);
        $trainer    = $request->trainer_id ? Trainer::find($request->trainer_id) : null;
        $program    = $request->program_id ? Program::find($request->program_id) : null;

        $hiddenFields = [
            'fitness_assessment_id' => $assessment->id,
            'service_id'            => $service->id,
            'trainer_id'            => $trainer?->id,
            'backup_trainer_id'     => $request->backup_trainer_id,
            'program_id'            => $program?->id,
            'schedule_id'           => $request->schedule_id,
            'booking_date'          => $request->booking_date,
            'booking_time'          => $request->booking_time,
        ];

        $level = $assessment->fitness_level ?? 'Beginner';
        $goal = $request->fitness_goal ?? $assessment->fitness_goal ?? null;

        return view('booking.review', [
            'assessment'   => $assessment,
            'service'      => $service,
            'trainer'      => $trainer,
            'program'      => $program,
            'bookingDate'  => $request->booking_date,
            'bookingTime'  => $request->booking_time,
            'level'        => $level,
            'goal'         => $goal,
            'hiddenFields' => $hiddenFields,
        ]);
    }
}
