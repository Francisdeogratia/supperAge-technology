<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LoginDetail;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable; // <-- 1. Import the Interface
use Illuminate\Auth\Authenticatable as AuthenticatableTrait; // Trait to handle implementation

// 2. Class MUST implement the interface and use the trait
class UserRecord extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait; // <-- 3. Use the trait here
    protected $table = 'users_record';

    

    // public $timestamps = false;
     // ✅ ADD THIS LINE if it's not there
    public $timestamps = true; // This enables created_at and updated_at

    protected $fillable = [
        'specialcode',
        'name',
        'username',
        'email',
        'profileimg',
        'bgimg',
        'bio',
        'gender',
        'dob',
        'number_followers',
        'continent',
        'country',
        'status',
        'disabled_until',
        'email_status',
        'phone_status',
        'unsetacct',
        'token',
        'tokenExpire',
        'role',
        'phone',
        'password',
        'created',
    ];

    protected $guarded = [];
    protected $dates = ['disabled_until'];
    protected $casts = [
        'disabled_until' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ✅ Relationship to premium tasks
    public function premiumTasks()
    {
        return $this->belongsToMany(PremiumTask::class, 'user_premium_tasks', 'user_id', 'premium_task_id')
                    ->withPivot('completed')
                    ->withTimestamps();
    }

    public function badgeVerifications()
    {
        return $this->hasMany(BadgeVerification::class, 'user_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'sender_id');
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'receiver_id');
    }

    public function mutualFollowers(UserRecord $otherUser)
    {
        return UserRecord::whereIn('id', function ($query) {
                $query->select('sender_id')
                      ->from('follow_tbl')
                      ->where('receiver_id', $this->id);
            })
            ->whereIn('id', function ($query) use ($otherUser) {
                $query->select('sender_id')
                      ->from('follow_tbl')
                      ->where('receiver_id', $otherUser->id);
            })
            ->get();
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')
                    ->withPivot('is_completed', 'completed_at')
                    ->withTimestamps();
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_owner_id');
    }

    public function lastLogin()
    {
        return $this->hasOne(LoginDetail::class, 'user_record_id')->latestOfMany();
    }

    // ✅ Get latest login session (active or not)
    public function loginSession()
    {
        return $this->hasOne(LoginDetail::class, 'user_record_id')->latest();
    }

    // ✅ Get only active sessions (not logged out)
    public function activeLoginSession()
    {
        return $this->hasOne(LoginDetail::class, 'user_record_id')
                    ->whereNull('logout_at')
                    ->latest();
    }

    // ✅ Check if user is online (has active session within 5 minutes)
    public function getIsOnlineAttribute()
    {
        $session = $this->activeLoginSession;
        if (!$session) return false;
        
        // User is online if updated_at is within last 5 minutes and not logged out
        return $session->updated_at && Carbon::parse($session->updated_at)->gt(now()->subMinutes(5));
    }

    // ✅ Get last seen time
    public function getLastSeenAttribute()
    {
        $session = $this->loginSession;
        if (!$session) {
            return 'Never';
        }
        
        // Use logout_at if logged out, otherwise use updated_at
        $lastActivity = $session->logout_at ?? $session->updated_at;
        
        if (!$lastActivity) {
            return 'Never';
        }
        
        return Carbon::parse($lastActivity)->diffForHumans();
    }

    // ✅ Get device from latest session
    public function getDeviceAttribute()
    {
        $session = $this->loginSession;
        return $session ? $session->device : 'Unknown';
    }



    public function lastLoginSession()
{
    return $this->hasOne(LoginDetail::class, 'user_record_id', 'id')
                ->latest('created_at');
}

/**
     * Monetization relationship
     */
    public function monetization()
    {
        return $this->hasOne(UserMonetization::class, 'user_id');
    }

/**
     * Posts relationship
     */
    public function posts()
    {
        return $this->hasMany(SamplePost::class, 'user_id');
    }

public function postPerformance()
{
    return $this->hasMany(PostPerformanceTracking::class, 'user_id');
}









}