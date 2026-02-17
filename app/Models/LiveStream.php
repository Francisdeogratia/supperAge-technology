<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'stream_key',
        'status',
        'viewer_count',
        'total_views',
        'peak_viewers',
        'reward_claimed',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'reward_claimed' => 'boolean'
    ];

    public function creator()
    {
        return $this->belongsTo(UserRecord::class, 'creator_id');
    }

    public function viewers()
    {
        return $this->hasMany(LiveStreamViewer::class, 'stream_id');
    }
}