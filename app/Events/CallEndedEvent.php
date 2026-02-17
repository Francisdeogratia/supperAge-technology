<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallEndedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $callId;
    public $userId;
    public $reason;
    
    public function __construct(int $callId, int $userId, string $reason = 'ended')
    {
        $this->callId = $callId;
        $this->userId = $userId;
        $this->reason = $reason;
        
        \Log::info('CallEndedEvent CREATED', [
            'call_id' => $callId,
            'user_id' => $userId,
            'reason' => $reason,
            'will_broadcast_to' => 'private-calls.' . $callId
        ]);
    }
    
    public function broadcastOn()
    {
        return new PrivateChannel('calls.' . $this->callId);
    }
    
    public function broadcastAs()
    {
        return 'CallEnded';
    }
    
    public function broadcastWith()
    {
        $data = [
            'call_id' => $this->callId,
            'user_id' => $this->userId,
            'reason' => $this->reason
        ];
        
        \Log::info('ðŸ›‘ CallEndedEvent BROADCASTING DATA', $data);
        
        return $data;
    }
}