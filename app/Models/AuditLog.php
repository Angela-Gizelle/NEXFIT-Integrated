<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'auditable_type',
        'auditable_id',
        'action',
        'old_values',
        'new_values',
        'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quick helper to log any action from a controller.
     *
     * Usage:
     *   AuditLog::record('created', $booking, null, $booking->toArray());
     */
    public static function record(
        string $action,
        Model  $subject,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        return self::create([
            'user_id'        => Auth::id(),
            'auditable_type' => class_basename($subject),
            'auditable_id'   => $subject->getKey(),
            'action'         => $action,
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'ip_address'     => request()->ip(),
        ]);
    }
}
