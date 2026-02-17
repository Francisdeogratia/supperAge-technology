<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Call extends Model
{
    protected $fillable = [
        'caller_id',
        'receiver_id',
        'call_type',
        'status',
        'started_at',
        'answered_at',
        'ended_at',
        'duration'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'answered_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration' => 'integer'
    ];

    /**
     * Get the user who initiated the call
     */
    public function caller(): BelongsTo
    {
        return $this->belongsTo(UserRecord::class, 'caller_id');
    }

    /**
     * Get the user who received the call
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(UserRecord::class, 'receiver_id');
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return '00:00';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Check if call was answered
     */
    public function wasAnswered(): bool
    {
        return !is_null($this->answered_at);
    }

    /**
     * Get call status icon
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'ended' => '✅',
            'declined' => '❌',
            'no_answer' => '⏰',
            'active' => '📞',
            'ringing' => '🔔',
            default => '📞'
        };
    }

    /**
     * Get call type icon
     */
    public function getTypeIconAttribute(): string
    {
        return $this->call_type === 'video' ? '📹' : '📞';
    }
}