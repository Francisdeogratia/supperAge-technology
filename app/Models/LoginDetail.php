<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginDetail extends Model
{
    protected $table = 'login_details';
    
    protected $fillable = [
        'user_record_id',
        'specialcode',
        'ip_address',
        'user_agent',
        'logout_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    // Relationship back to user
    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_record_id');
    }

    // Check if user is still logged in (not logged out)
    public function getIsActiveAttribute()
    {
        return is_null($this->logout_at);
    }

    // Get device type from user_agent
    public function getDeviceAttribute()
    {
        if (!$this->user_agent) return 'Unknown';
        
        if (preg_match('/mobile|android|iphone|ipad|ipod/i', $this->user_agent)) {
            return 'Mobile';
        }
        return 'Desktop';
    }

    // Check if mobile
    public function getIsMobileAttribute()
    {
        return $this->device === 'Mobile';
    }
}

