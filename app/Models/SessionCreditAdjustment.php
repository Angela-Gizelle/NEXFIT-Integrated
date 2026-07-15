<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Session Management module — REQ-SI-05 (immutable audit log).
class SessionCreditAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'adjustment_amount',
        'credits_before',
        'credits_after',
        'reason',
        'adjusted_by',
        'adjusted_at',
    ];

    protected $casts = [
        'adjusted_at'       => 'datetime',
        'adjustment_amount' => 'integer',
        'credits_before'    => 'integer',
        'credits_after'     => 'integer',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function adjustedBy()
    {
        return $this->belongsTo(Staff::class, 'adjusted_by', 'staff_id');
    }

    public function getDirectionAttribute(): string
    {
        return $this->adjustment_amount >= 0 ? 'added' : 'deducted';
    }
}
