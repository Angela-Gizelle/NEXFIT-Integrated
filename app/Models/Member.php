<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes; // REQ SR-2: soft delete only

    protected $fillable = [
        'user_id', 'full_name', 'email', 'phone', 'birthdate', 'address',
        'emergency_contact_name', 'emergency_contact_phone',
        'enrollment_date', 'fitness_level', 'population_class',
        'status', 'assigned_trainer_id',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'birthdate'       => 'date',
    ];

    // The login account linked to this membership record, if any.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Assigned trainer
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'assigned_trainer_id');
    }

    // REQ-MM-02: all membership packages
    public function packages()
    {
        return $this->hasMany(MemberPackage::class);
    }

    /**
     * The member's currently Active package (status = Active).
     * This is the source of truth for membership validity.
     */
    public function activePackage()
    {
        return $this->hasOne(MemberPackage::class)
            ->where('status', 'Active')
            ->latest('coverage_start');
    }

    // REQ-MM-05: ParQ — shares landing's parq_responses table, keyed by member_id
    public function parqResponses()
    {
        return $this->hasMany(ParqResponse::class);
    }

    public function latestParq()
    {
        return $this->hasOne(ParqResponse::class)->latestOfMany();
    }

    // REQ-MM-07: health flags
    public function healthFlags()
    {
        return $this->hasMany(MemberHealthFlag::class);
    }

    // All bookings tied to this member's own account (if linked to a user)
    public function bookings()
    {
        return $this->user ? $this->user->bookings() : Booking::query()->whereRaw('1 = 0');
    }

    // ── Session Management module ──────────────────────────────────

    // REQ-SI-03: aggregate credit balance (purchased/conducted/remaining/forfeited)
    public function creditBalance()
    {
        return $this->hasOne(MemberCreditBalance::class);
    }

    // REQ-SI-01..07: individual training/session records
    public function trainingSessions()
    {
        return $this->hasMany(Session::class);
    }

    // REQ-SP-01..07: session package sale transactions
    public function sessionPackageSales()
    {
        return $this->hasMany(SessionPackageSale::class);
    }

    // REQ-SI-05: immutable manual credit adjustment audit trail
    public function creditAdjustments()
    {
        return $this->hasMany(SessionCreditAdjustment::class);
    }

    /**
     * REQ-MM-04: true when the active package ends within 7 days,
     * OR when the member has no active package at all (fully expired).
     * Used to decide whether to show the Renew button.
     */
    public function isNearExpiry(): bool
    {
        $pkg = $this->activePackage;

        if (!$pkg) {
            // No active package — member needs renewal
            return $this->packages()->exists();
        }

        return now()->diffInDays($pkg->coverage_end, false) <= 7;
    }
}
