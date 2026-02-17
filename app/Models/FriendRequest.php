<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
    ];

    // Relationship to sender
    public function sender()
    {
        return $this->belongsTo(UserRecord::class, 'sender_id');
    }

    // Relationship to receiver
    public function receiver()
    {
        return $this->belongsTo(UserRecord::class, 'receiver_id');
    }
}
