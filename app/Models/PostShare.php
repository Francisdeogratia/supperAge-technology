<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostShare extends Model
{
    protected $table = 'post_shares';
    protected $fillable = [
        'user_id',
        'recipient_id', // ✅ Must be here
        'post_id',
        'platform',
        'message', // ✅ Must be here
    ];
    public function user()
{
    return $this->belongsTo(UserRecord::class, 'user_id');
}

// ✅ Relationship to the person who received the share
    public function recipient()
    {
        return $this->belongsTo(UserRecord::class, 'recipient_id');
    }

    // Relationship to the post
    public function post()
    {
        return $this->belongsTo(SamplePost::class, 'post_id');
    }

}

