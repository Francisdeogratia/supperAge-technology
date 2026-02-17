<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    protected $fillable = ['user_id', 'post_id'];

    public function post()
    {
        return $this->belongsTo(SamplePost::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
}

