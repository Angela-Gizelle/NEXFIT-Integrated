<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPackage extends Model
{
    protected $fillable = [
        'member_id', 'package_type', 'purchase_date',
        'coverage_start', 'coverage_end', 'session_credits',
        'credits_used', 'amount_paid', 'payment_mode',
        'processed_by', 'status',
    ];

    protected $casts = [
        'purchase_date'  => 'date',
        'coverage_start' => 'date',
        'coverage_end'   => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getCreditsRemainingAttribute(): int
    {
        return $this->session_credits - $this->credits_used;
    }

    /**
     * A package is expired when its own status field says so,
     * or when the coverage_end date has passed (whichever is available).
     */
    public function isExpired(): bool
    {
        return $this->status === 'Expired' || $this->coverage_end->isPast();
    }
}
