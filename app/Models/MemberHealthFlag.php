<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// REQ-MM-07: Special Population health flags
class MemberHealthFlag extends Model
{
    protected $fillable = ['member_id', 'condition', 'notes'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
