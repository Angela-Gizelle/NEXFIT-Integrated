<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Session Management module — REQ-SP-01..07 (Session Package Sales).
class SessionPackageSale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'walkin_name',
        'session_package_id',
        'pricing_type',
        'amount_paid',
        'payment_mode',
        'reference_number',
        'sale_type',
        'processed_by',
        'sale_date',
        'sale_time',
        'notes',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'sale_date'   => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function sessionPackage()
    {
        return $this->belongsTo(SessionPackage::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'session_package_sale_id');
    }

    // Uses the base project's Member::full_name (single column), unlike
    // the module's original first_name/last_name split.
    public function getClientNameAttribute(): string
    {
        return $this->member?->full_name ?? $this->walkin_name ?? 'Walk-in';
    }
}
