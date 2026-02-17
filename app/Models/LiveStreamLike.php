<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStreamLike extends Model
{
    protected $fillable = ['stream_id', 'user_id'];

    public function stream()
    {
        return $this->belongsTo(LiveStream::class, 'stream_id');
    }

    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
}