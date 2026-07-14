<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Session Management module — REQ-SP-03/04 (package catalog).
class SessionPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'program',
        'session_credits',
        'validity_days',
        'base_price',
        'student_price',
        'pwd_price',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'base_price'      => 'decimal:2',
        'student_price'   => 'decimal:2',
        'pwd_price'       => 'decimal:2',
        'session_credits' => 'integer',
        'validity_days'   => 'integer',
    ];

    public function sales()
    {
        return $this->hasMany(SessionPackageSale::class);
    }

    public function getPriceForType(string $pricingType): float
    {
        return match ($pricingType) {
            'student' => $this->student_price ?? $this->base_price,
            'pwd'     => $this->pwd_price ?? $this->base_price,
            default   => $this->base_price,
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
