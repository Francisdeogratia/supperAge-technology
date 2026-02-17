<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'event_type',
        'category',
        'privacy',
        'max_attendees',
        'attendee_count',
        'event_image',
        'meeting_link',
        'status'
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i'
    ];

    public function creator()
    {
        return $this->belongsTo(UserRecord::class, 'created_by');
    }

    public function attendees()
    {
        return $this->hasMany(EventAttendee::class);
    }
}