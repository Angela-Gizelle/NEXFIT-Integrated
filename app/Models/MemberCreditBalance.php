<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Session Management module — REQ-SI-03 (aggregate credit balance).
class MemberCreditBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'credits_purchased',
        'credits_conducted',
        'credits_remaining',
        'credits_forfeited',
    ];

    protected $casts = [
        'credits_purchased' => 'integer',
        'credits_conducted' => 'integer',
        'credits_remaining' => 'integer',
        'credits_forfeited' => 'integer',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function addCredits(int $amount): void
    {
        $this->increment('credits_purchased', $amount);
        $this->increment('credits_remaining', $amount);
    }

    public function deductCredit(): bool
    {
        if ($this->credits_remaining <= 0) {
            return false;
        }
        $this->increment('credits_conducted');
        $this->decrement('credits_remaining');
        return true;
    }

    public function restoreCredit(): void
    {
        $this->decrement('credits_conducted');
        $this->increment('credits_remaining');
    }

    public function hasCredits(): bool
    {
        return $this->credits_remaining > 0;
    }
}
