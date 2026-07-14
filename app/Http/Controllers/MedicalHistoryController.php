<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalHistory;
use App\Models\AuditLog;

class MedicalHistoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fitness_assessment_id' => 'required|exists:fitness_assessments,id',
        ]);

        $history = MedicalHistory::create([
            'fitness_assessment_id'   => $request->fitness_assessment_id,
            'chronic_illness'         => $request->chronic_illness,
            'chronic_illness_details' => $request->chronic_illness_details,
            'major_surgery'           => $request->major_surgery,
            'major_surgery_details'   => $request->major_surgery_details,
            'current_medications'     => $request->current_medications,
            'medication_name'         => $request->medication_name,
            'interested_in'           => $request->interested_in,
        ]);

        AuditLog::record('created', $history, null, $history->toArray());

        return response()->json([
            'success' => true,
            'data'    => $history,
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $history = MedicalHistory::findOrFail($id);
        $old     = $history->toArray();

        $history->update($request->only([
            'chronic_illness',
            'chronic_illness_details',
            'major_surgery',
            'major_surgery_details',
            'current_medications',
            'medication_name',
            'interested_in',
        ]));

        AuditLog::record('updated', $history, $old, $history->fresh()->toArray());

        return response()->json([
            'success' => true,
            'data'    => $history->fresh(),
        ]);
    }
}
