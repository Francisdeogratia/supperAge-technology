<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupCallParticipant extends Model
{
    protected $fillable = [
        'call_id',
        'user_id',
        'status',
        'joined_at',
        'left_at'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    public function call()
    {
        return $this->belongsTo(GroupCall::class, 'call_id');
    }

    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
}