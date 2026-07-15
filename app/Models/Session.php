<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Session Management module — REQ-SI-01..07 (Session Inventory Tracking).
 *
 * Adapted to the base project: trainer()/backupTrainer() point at the
 * `trainers` table (via the Trainer model), not `users` — see the
 * training_sessions migration for why.
 */
class Session extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'training_sessions';

    protected $fillable = [
        'member_id',
        'trainer_id',
        'backup_trainer_id',
        'session_package_sale_id',
        'booking_id',
        'program',
        'level',
        'session_date',
        'session_time',
        'status',
        'remarks',
        'conducted_at',
        'conducted_by',
        'cancelled_by',
        'cancelled_at',
        'credit_restored',
    ];

    protected $casts = [
        'session_date'    => 'date',
        'conducted_at'    => 'datetime',
        'cancelled_at'    => 'datetime',
        'credit_restored' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }

    public function backupTrainer()
    {
        return $this->belongsTo(Trainer::class, 'backup_trainer_id');
    }

    public function packageSale()
    {
        return $this->belongsTo(SessionPackageSale::class, 'session_package_sale_id');
    }

    // Traceability back to the Online Booking module, if this session
    // originated from a public/staff booking.
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function conductedBy()
    {
        return $this->belongsTo(Staff::class, 'conducted_by', 'staff_id');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(Staff::class, 'cancelled_by', 'staff_id');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('session_date', today());
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByTrainer($query, int $trainerId)
    {
        return $query->where('trainer_id', $trainerId);
    }

    public function getFormattedTimeAttribute(): string
    {
        return date('h:i A', strtotime($this->session_time));
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'confirmed'   => 'badge-confirmed',
            'conducted'   => 'badge-conducted',
            'pending'     => 'badge-pending',
            'cancelled'   => 'badge-cancelled',
            'no_show'     => 'badge-noshow',
            'rescheduled' => 'badge-rescheduled',
            default       => 'badge-secondary',
        };
    }
}
