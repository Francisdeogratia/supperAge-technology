<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostRepost extends Model
{
    protected $table = 'post_reposts';
    protected $fillable = ['user_id', 'post_id'];
    public function user()
{
    return $this->belongsTo(UserRecord::class, 'user_id');
}

}
