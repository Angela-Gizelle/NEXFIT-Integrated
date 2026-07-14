<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'open_time',
        'close_time',
        'requires_trainer',
        'requires_program',
        'is_active',
    ];

    protected $casts = [
        'requires_trainer' => 'boolean',
        'requires_program' => 'boolean',
        'is_active'        => 'boolean',
    ];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, 'trainer_service');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
