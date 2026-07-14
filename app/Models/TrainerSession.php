<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerSession extends Model
{
    protected $table = 'trainer_sessions';

    protected $fillable = [
        'trainer_id',
        'member_id',
        'session_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * "6:00 AM" style label for the start time, regardless of whether the
     * DB driver hands back "06:00:00" or a Carbon instance.
     */
    public function getStartLabelAttribute(): string
    {
        return \Illuminate\Support\Carbon::parse($this->start_time)->format('g:i A');
    }

    public function getEndLabelAttribute(): string
    {
        return \Illuminate\Support\Carbon::parse($this->end_time)->format('g:i A');
    }
}
