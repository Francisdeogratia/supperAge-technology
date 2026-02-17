<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',                 // post owner
        'actor_id',                // who did the action
        'post_id',
        'message',
        'link',
        'notification_reciever_id',
        'read_notification',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at'
    ];

    public function actor()
    {
        return $this->belongsTo(UserRecord::class, 'actor_id');
    }

    public function receiver()
    {
        return $this->belongsTo(UserRecord::class, 'notification_reciever_id');
    }

    public function post()
    {
        return $this->belongsTo(SamplePost::class, 'post_id');
    }

    public function sender()
{
    return $this->belongsTo(UserRecord::class, 'user_id', 'id');
}
}
