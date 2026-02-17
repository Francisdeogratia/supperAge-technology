<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
        'group_image',
        'cover_image',
        'created_by',
        'privacy',
        'member_count',
        'is_active',
        'pinned_message_id' // ✅ ADD THIS
    ];

    public function creator()
    {
        return $this->belongsTo(UserRecord::class, 'created_by');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function joinRequests()
    {
        return $this->hasMany(GroupJoinRequest::class);
    }
    
    // ✅ ADD THIS RELATIONSHIP
    public function pinnedMessage()
    {
        return $this->belongsTo(GroupMessage::class, 'pinned_message_id');
    }
    
    public function isMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }
    
    public function hasPendingRequest($userId)
    {
        return $this->joinRequests()
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->exists();
    }

    public function isAdmin($userId)
    {
        return $this->members()
            ->where('user_id', $userId)
            ->where('role', 'admin')
            ->exists();
    }
}