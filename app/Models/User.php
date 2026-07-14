<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Fitness assessments this user submitted (each booking flow starts
     * with one). Only populated for assessments made while logged in.
     */
    public function assessments()
    {
        return $this->hasMany(FitnessAssessment::class);
    }

    /**
     * The membership record linked to this account, if the person has
     * been enrolled as a member (by staff, or via signup + enrollment).
     * This is what drives the post-login redirect to the member dashboard.
     */
    public function member()
    {
        return $this->hasOne(Member::class);
    }

    /**
     * All bookings tied to this user's account, via their assessments.
     */
    public function bookings()
    {
        return $this->hasManyThrough(
            Booking::class,
            FitnessAssessment::class,
            'user_id',              // FK on fitness_assessments
            'fitness_assessment_id' // FK on bookings
        );
    }

    /**
     * AI-generated training plans for this user (training-plan module).
     */
    public function trainingPlans()
    {
        return $this->hasMany(TrainingPlan::class);
    }
}
