<?php


// app/Events/SignalEvent.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SignalEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $callId;
    public $senderId;
    public $signalData;

    public function __construct(int $callId, int $senderId, array $signalData)
    {
        $this->callId = $callId;
        $this->senderId = $senderId;
        $this->signalData = $signalData;
    }

    // Broadcast on the shared call channel
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('calls.' . $this->callId);
    }
}