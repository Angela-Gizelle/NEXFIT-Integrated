<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParqResponse extends Model
{
    protected $fillable = [

        'fitness_assessment_id',
        'member_id',
        'assessment_date',

        'heart_condition',
        'chest_pain_activity',
        'chest_pain_rest',
        'dizziness_balance',
        'bone_joint_condition',
        'blood_pressure_medication',
        'other_medical_reason',

        'medical_clearance_required',
        'additional_notes',
        'assessed_by',

    ];

    protected $casts = [
        'assessment_date'             => 'date',
        'heart_condition'             => 'boolean',
        'chest_pain_activity'         => 'boolean',
        'chest_pain_rest'             => 'boolean',
        'dizziness_balance'           => 'boolean',
        'bone_joint_condition'        => 'boolean',
        'blood_pressure_medication'   => 'boolean',
        'other_medical_reason'        => 'boolean',
        'medical_clearance_required'  => 'boolean',
    ];

    public function assessment()
    {
        return $this->belongsTo(FitnessAssessment::class, 'fitness_assessment_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // REQ-MM-06: any flagged risk factor means this member is Special Population
    public function isSpecialPopulation(): bool
    {
        return $this->heart_condition
            || $this->chest_pain_activity
            || $this->chest_pain_rest
            || $this->dizziness_balance
            || $this->bone_joint_condition
            || $this->blood_pressure_medication
            || $this->other_medical_reason;
    }
}