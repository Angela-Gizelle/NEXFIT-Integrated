<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'trainer_level',
        'is_available',
        'is_active',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_active'    => 'boolean',
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
