<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'file_path',
        'voice_note',
        'voice_duration',
        'link_preview',      // ✅ ADDED
        'reply_to_id',
        'reactions',
        'status',
        'is_deleted_by_sender',
        'is_deleted_by_receiver',
        'is_edited',
        'is_forwarded',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_edited' => 'boolean',
        'voice_duration' => 'integer',
    ];

    public function sender()
    {
        return $this->belongsTo(UserRecord::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(UserRecord::class, 'receiver_id');
    }
    
    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }
}