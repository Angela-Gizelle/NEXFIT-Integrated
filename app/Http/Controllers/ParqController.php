<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParqResponse;
use App\Models\AuditLog;

class ParqController extends Controller
{
    public function store(Request $request)
    {
        $hasRisk = false;

        $questions = [
            'heart_condition',
            'chest_pain_activity',
            'chest_pain_rest',
            'dizziness_balance',
            'bone_joint_condition',
            'blood_pressure_medication',
            'other_medical_reason',
        ];

        foreach ($questions as $question) {
            if ($request->$question == "yes") {
                $hasRisk = true;
            }
        }

        $parq = ParqResponse::create([

            'fitness_assessment_id' => session('assessment_id'),

            'heart_condition'           => $request->heart_condition == "1",
            'chest_pain_activity'       => $request->chest_pain_activity == "1",
            'chest_pain_rest'           => $request->chest_pain_rest == "1",
            'dizziness_balance'         => $request->dizziness_balance == "1",
            'bone_joint_condition'      => $request->bone_joint_condition == "1",
            'blood_pressure_medication' => $request->blood_pressure_medication == "1",
            'other_medical_reason'      => $request->other_medical_reason == "1",

            'medical_clearance_required' => $hasRisk,
        ]);

        AuditLog::record('created', $parq, null, $parq->toArray());
        
        session(['parq_has_risk' => $hasRisk]);


        return redirect('/booking/program');
    }
}
