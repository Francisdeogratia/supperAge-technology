<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallAcceptedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $callId;
    public $callerId;
    public $receiverId;
    
    public function __construct(int $callId, int $callerId, int $receiverId)
    {
        $this->callId = $callId;
        $this->callerId = $callerId;
        $this->receiverId = $receiverId;
        
        \Log::info('ðŸ“ž CallAcceptedEvent CONSTRUCTED', [
            'call_id' => $callId, 
            'caller_id' => $callerId,
            'receiver_id' => $receiverId,
            'broadcasting_to' => 'calls.' . $callId
        ]);
    }
    
    public function broadcastOn()
    {
        \Log::info('ðŸ“¡ CallAcceptedEvent broadcasting', [
            'channel' => 'calls.' . $this->callId
        ]);
        
        return new PrivateChannel('calls.' . $this->callId);
    }
    
    public function broadcastAs()
    {
        return 'CallAccepted';
    }
    
    public function broadcastWith()
    {
        return [
            'callId' => $this->callId,
            'callerId' => $this->callerId,
            'receiverId' => $this->receiverId,
            'agoraChannel' => 'call_' . $this->callId,
            'message' => 'Call has been accepted'
        ];
    }
}