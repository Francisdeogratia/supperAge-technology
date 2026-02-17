<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserRecord;

class IncomingCallEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $callId;
    public $callerId;
    public $receiverId; 
    public $callType;
    public $callerName;

    // Update constructor to accept callerId parameter
    public function __construct(int $callId, int $receiverId, string $callType, int $callerId)
    {
        $this->callId = $callId;
        $this->callerId = $callerId;  // ← Now passed as parameter
        $this->receiverId = $receiverId;
        $this->callType = $callType;
        
        $this->callerName = UserRecord::find($this->callerId)->name ?? 'Unknown Caller';
        
        logger()->info('IncomingCallEvent Created', [
            'call_id' => $callId,
            'caller_id' => $callerId,
            'receiver_id' => $receiverId,
            'caller_name' => $this->callerName
        ]);
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('users.' . $this->receiverId);
    }
}












