<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ParqResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParqReassessController extends Controller
{
    /**
     * Lets a logged-in member retake the PAR-Q health screening from
     * their dashboard. Mirrors App\Http\Controllers\ParqController's
     * question set, but is stored against member_id instead of a guest
     * fitness_assessment_id.
     */
    public function store(Request $request)
    {
        $member = Auth::user()->member()->firstOrFail();

        $questions = [
            'heart_condition',
            'chest_pain_activity',
            'chest_pain_rest',
            'dizziness_balance',
            'bone_joint_condition',
            'blood_pressure_medication',
            'other_medical_reason',
        ];

        $hasRisk = false;
        $answers = [];

        foreach ($questions as $question) {
            $answers[$question] = $request->input($question) == '1';
            if ($answers[$question]) {
                $hasRisk = true;
            }
        }

        $parq = ParqResponse::create(array_merge($answers, [
            'member_id'                  => $member->id,
            'assessment_date'            => now()->toDateString(),
            'medical_clearance_required' => $hasRisk,
            'additional_notes'           => $request->input('health_notes'),
            'assessed_by'                => null, // self-submitted
        ]));

        // REQ-MM-06: auto-flip population class based on the new answers
        $member->update([
            'population_class' => $hasRisk ? 'Special' : 'General',
        ]);

        AuditLog::record('created', $parq, null, $parq->toArray());

        return redirect()->route('member.dashboard')
            ->with('success', 'Your PAR-Q health screening has been updated.');
    }
}
