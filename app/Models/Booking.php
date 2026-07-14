<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'fitness_assessment_id',
        'service_id',
        'trainer_id',
        'backup_trainer_id',
        'program_id',
        'schedule_id',
        'booking_date',
        'booking_time',
        'status',
        'medical_clearance_acknowledged',
        'rights_policy_acknowledged',
        'member_declaration_signed',
        'notes',
    ];

    protected $casts = [
        'booking_date'                    => 'date',
        'medical_clearance_acknowledged'  => 'boolean',
        'rights_policy_acknowledged'      => 'boolean',
        'member_declaration_signed'       => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────

    public function assessment()
    {
        return $this->belongsTo(FitnessAssessment::class, 'fitness_assessment_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function backupTrainer()
    {
        return $this->belongsTo(Trainer::class, 'backup_trainer_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
