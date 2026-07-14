<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalkInInquiry extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'interested_in',
        'notes',
        'followed_up',
        'followed_up_at',
        'followed_up_by',
    ];

    protected $casts = [
        'followed_up'    => 'boolean',
        'followed_up_at' => 'datetime',
    ];
}
