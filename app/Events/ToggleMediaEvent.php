<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ToggleMediaEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $callId;
    public $senderId;
    public $type;  // 'audio' or 'video'
    public $state; // true = muted/off, false = unmuted/on

    /**
     * Create a new event instance.
     */
    public function __construct($callId, $senderId, $type, $state)
    {
        $this->callId = $callId;
        $this->senderId = $senderId;
        $this->type = $type;
        $this->state = $state;
        
        \Log::info('ðŸ“¡ ToggleMediaEvent created', [
            'call_id' => $callId,
            'sender_id' => $senderId,
            'type' => $type,
            'state' => $state ? 'MUTED/OFF' : 'UNMUTED/ON'
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('calls.' . $this->callId);
    }
    
    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'ToggleMediaEvent';
    }
    
    /**
     * Determine if this event should broadcast.
     */
    public function broadcastWhen()
    {
        return true;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'callId' => $this->callId,
            'senderId' => $this->senderId,
            'type' => $this->type,
            'state' => $this->state
        ];
    }
}