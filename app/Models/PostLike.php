<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $table = 'post_likes';
    protected $fillable = ['user_id', 'post_id'];

    public function user()
{
    return $this->belongsTo(UserRecord::class, 'user_id');
}

}
