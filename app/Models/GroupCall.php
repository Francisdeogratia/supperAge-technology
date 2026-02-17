<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupCall extends Model
{
    protected $fillable = [
        'group_id',
        'initiated_by',  // ✅ Keep this consistent
        'call_type',
        'status',
        'started_at',
        'ended_at',
        'duration'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // ✅ FIX: Use correct column name
    public function initiator()
    {
        return $this->belongsTo(UserRecord::class, 'initiated_by');
    }

    public function participants()
    {
        return $this->hasMany(GroupCallParticipant::class, 'call_id');
    }
}