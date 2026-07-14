<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'trainer_id',
        'service_id',
        'date',
        'start_time',
        'end_time',
        'max_capacity',
        'booked_count',
        'is_full',
        'is_active',
    ];

    protected $casts = [
        'date'     => 'date',
        'is_full'  => 'boolean',
        'is_active' => 'boolean',
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Increment booked_count and mark full when capacity is reached.
     * Returns FALSE if already full (slot cannot be booked).
     */
    public function reserve(): bool
    {
        if ($this->is_full) {
            return false;
        }

        $this->increment('booked_count');

        if ($this->booked_count >= $this->max_capacity) {
            $this->update(['is_full' => true]);
        }

        return true;
    }

    /**
     * Release a slot (on cancellation / reschedule).
     */
    public function release(): void
    {
        if ($this->booked_count > 0) {
            $this->decrement('booked_count');
            $this->update(['is_full' => false]);
        }
    }
}
