<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserMutedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $callId;
    public $userId;
    public $muted;

    public function __construct($callId, $userId, $muted)
    {
        $this->callId = $callId;
        $this->userId = $userId;
        $this->muted = $muted;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('calls.' . $this->callId);
    }

    public function broadcastAs()
    {
        return 'UserMuted';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->userId,
            'muted' => $this->muted
        ];
    }
}