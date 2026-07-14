<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FitnessAssessment extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'gender',
        'phone_number',
        'email',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_relationship',
        'exercise_experience',
        'currently_exercising',
        'exercise_frequency',
        'exercise_type',
        'exercise_other',
        'interested_in',
        'fitness_goal',      // ← NEW: single goal driving trainer recommendation
    ];

    protected $casts = [
        'exercise_type'        => 'array',
        'currently_exercising' => 'boolean',
        'date_of_birth'        => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalHistory()
    {
        return $this->hasOne(MedicalHistory::class);
    }

    public function parq()
    {
        return $this->hasOne(ParqResponse::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    public function getFitnessLevelAttribute(): string
    {
        return match (strtolower($this->exercise_experience)) {
            'intermediate' => 'Intermediate',
            'advanced'     => 'Advanced',
            default        => 'Beginner',
        };
    }
}