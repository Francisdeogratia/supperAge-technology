<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserVideoToggledEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $callId;
    public $userId;
    public $enabled;

    public function __construct($callId, $userId, $enabled)
    {
        $this->callId = $callId;
        $this->userId = $userId;
        $this->enabled = $enabled;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('calls.' . $this->callId);
    }

    public function broadcastAs()
    {
        return 'UserVideoToggled';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->userId,
            'enabled' => $this->enabled
        ];
    }
}