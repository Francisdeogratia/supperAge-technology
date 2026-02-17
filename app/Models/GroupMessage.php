<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    protected $fillable = [
        'group_id',
        'sender_id',
        'message',
        'file_path',
        'voice_note',
        'voice_duration',
        'reply_to_id',
        'reactions',
        'is_deleted',
        'is_edited',
        'status',
        'message_type',
        'call_type',
        'call_id',
        'call_duration',
        'link_preview',  // ✅ NEW
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_deleted' => 'boolean',
        'is_edited' => 'boolean',
        'voice_duration' => 'integer',
        'call_duration' => 'integer',
        'link_preview' => 'array',  // ✅ NEW - cast to array
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function sender()
    {
        return $this->belongsTo(UserRecord::class, 'sender_id');
    }
    
    public function replyTo()
    {
        return $this->belongsTo(GroupMessage::class, 'reply_to_id');
    }
    
    public function call()
    {
        return $this->belongsTo(GroupCall::class, 'call_id');
    }
}