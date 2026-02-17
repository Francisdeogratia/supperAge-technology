<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostPerformanceTracking extends Model
{
    protected $table = 'post_performance_tracking';
    
    protected $fillable = [
        'post_id',
        'user_id',
        'likes_count',
        'comments_count',
        'reposts_count',
        'shares_count',
        'views_count',
        'fire_notification_sent_at',
        'is_viral',
        'viral_achieved_at'
    ];
    
    protected $casts = [
        'fire_notification_sent_at' => 'datetime',
        'viral_achieved_at' => 'datetime',
        'is_viral' => 'boolean'
    ];
    
    public function post()
    {
        return $this->belongsTo(SamplePost::class, 'post_id');
    }
    
    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
    
    // Check if post qualifies for "On Fire" notification
    public function isOnFire()
    {
        return $this->likes_count >= 1000
            && $this->comments_count >= 500
            && $this->reposts_count >= 100
            && $this->shares_count >= 100
            && $this->views_count >= 2000;
    }
}