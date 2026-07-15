<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Trainer is both a booking-system profile (specialization, schedules, etc.)
// AND a self-registering login account (auth:trainer guard) — see
// Auth\RegisterController and Auth\LoginController. It extends
// Authenticatable instead of the plain Eloquent Model so it can log in.
class Trainer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'specialization',
        'trainer_level',
        'is_available',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_active'    => 'boolean',
        'password'     => 'hashed',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Members currently assigned to this trainer (Membership module)
    public function members()
    {
        return $this->hasMany(Member::class, 'assigned_trainer_id');
    }

    // Sessions this trainer is the primary trainer for (Session Management module)
    public function trainingSessions()
    {
        return $this->hasMany(Session::class, 'trainer_id');
    }

    // Sessions this trainer is the backup trainer for (Session Management module)
    public function backupTrainingSessions()
    {
        return $this->hasMany(Session::class, 'backup_trainer_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'trainer_service');
    }

    // AI-generated training plans this trainer has been matched/assigned to (training-plan module)
    public function trainingPlans()
    {
        return $this->hasMany(TrainingPlan::class);
    }

    /**
     * Get available schedule slots for this trainer on a given date.
     */
    public function availableSlotsOn(string $date)
    {
        return $this->schedules()
            ->where('date', $date)
            ->where('is_full', false)
            ->where('is_active', true)
            ->get();
    }
}
