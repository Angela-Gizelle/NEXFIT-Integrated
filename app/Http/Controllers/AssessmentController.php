<?php
// REPLACE: app/Http/Controllers/AssessmentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\FitnessAssessment;
use App\Models\MedicalHistory;

class AssessmentController extends Controller
{
   public function store(Request $request)
{
    $assessment = FitnessAssessment::create([
        'user_id'                        => auth()->id(),
        'full_name'                      => $request->full_name,
        'date_of_birth'                  => $request->date_of_birth,
        'gender'                         => $request->gender,
        'phone_number'                   => $request->phone_number,
        'email'                          => $request->email,
        'emergency_contact_name'         => $request->emergency_contact_name,
        'emergency_contact_number'       => $request->emergency_contact_number,
        'emergency_contact_relationship' => $request->emergency_contact_relationship,
        'exercise_experience'            => strtolower($request->exercise_experience ?? 'beginner'),
        'currently_exercising'           => $request->currently_exercising,
        'exercise_frequency'             => $request->exercise_frequency,
        'exercise_type'                  => json_encode($request->exercise_type ?? []),
        'exercise_other'                 => $request->exercise_other,
        'interested_in'                  => $request->interested_in,
        'fitness_goal'                   => $request->fitness_goal,
    ]);

    MedicalHistory::create([
        'fitness_assessment_id'   => $assessment->id,
        'chronic_illness'         => $request->chronic_illness,
        'chronic_illness_details' => $request->chronic_illness_details,
        'major_surgery'           => $request->major_surgery,
        'major_surgery_details'   => $request->major_surgery_details,
        'current_medications'     => $request->current_medications,
        'medication_name'         => $request->medication_name,
        'interested_in'           => $request->interested_in,
    ]);

    session(['assessment_id' => $assessment->id]);

   // booking_type already set by PublicBookingController::assessment()
    // just use whatever is in session, fallback to guest
    $type = session('booking_type', 'guest');

    Log::info('Booking type:', ['type' => $type]);

    if ($type === 'guest') {
        return redirect('/booking/program');
    }

    return redirect('/booking/parq/' . $assessment->id);
}
}