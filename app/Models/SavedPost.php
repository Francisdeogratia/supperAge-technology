<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedPost extends Model
{
    protected $table = 'saved_posts';
    protected $fillable = ['user_id', 'post_id'];

    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(SamplePost::class, 'post_id');
    }
}
