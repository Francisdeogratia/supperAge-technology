<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $fillable = ['user_id', 'post_id', 'comment', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id');
    }
}
