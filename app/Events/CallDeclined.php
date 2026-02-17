<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallDeclined implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $callId;
    public $declinedBy;
    
    public function __construct($callId, $declinedBy)
    {
        $this->callId = (int) $callId;
        $this->declinedBy = (int) $declinedBy;
        
        \Log::info('❌ CallDeclined CREATED', [
            'call_id' => $this->callId,
            'declined_by' => $this->declinedBy,
            'will_broadcast_to' => 'private-calls.' . $this->callId
        ]);
    }
    
    public function broadcastOn()
    {
        return new PrivateChannel('calls.' . $this->callId);
    }
    
    public function broadcastAs()
    {
        return 'CallDeclined';
    }
    
    public function broadcastWith()
    {
        $data = [
            'call_id' => $this->callId,
            'declined_by' => $this->declinedBy,
            'message' => 'Call was declined'
        ];
        
        \Log::info('❌ CallDeclined BROADCASTING DATA', $data);
        
        return $data;
    }
}