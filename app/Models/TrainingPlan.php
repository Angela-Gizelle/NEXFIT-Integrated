<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingPlan extends Model
{
    protected $fillable = [
        'user_id',
        'trainer_id',
        'plan_content',
        'is_current',
        'generated_by',
    ];

    protected $casts = [
        'plan_content' => 'array',
        'is_current' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }
}
