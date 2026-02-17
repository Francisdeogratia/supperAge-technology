<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostReward extends Model
{
    protected $fillable = ['post_id', 'user_id', 'type', 'amount', 'status'];

    public function post()
    {
        return $this->belongsTo(SamplePost::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
}

