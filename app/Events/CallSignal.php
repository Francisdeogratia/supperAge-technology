<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallSignal implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $callId;
    public $fromUserId;
    public $signal;
    
    public function __construct($callId, $fromUserId, $signal)
    {
        $this->callId = (int) $callId;
        $this->fromUserId = (int) $fromUserId;
        $this->signal = $signal;
        
        \Log::info('ðŸ“¡ CallSignal __construct called', [
            'call_id' => $this->callId,
            'from_user' => $this->fromUserId,
            'signal_type' => $signal['type'] ?? 'unknown'
        ]);
    }
    
    public function broadcastOn()
    {
        $channel = new PrivateChannel('calls.' . $this->callId);
        
        \Log::info('ðŸ“¡ CallSignal broadcastOn called', [
            'channel_name' => 'private-calls.' . $this->callId
        ]);
        
        return $channel;
    }
    
    public function broadcastAs()
    {
        \Log::info('ðŸ“¡ CallSignal broadcastAs called: CallSignal');
        return 'CallSignal';
    }
    
    public function broadcastWith()
    {
        $data = [
            'call_id' => $this->callId,
            'from_user_id' => $this->fromUserId,
            'signal' => $this->signal
        ];
        
        \Log::info('ðŸ“¡ CallSignal broadcastWith called', $data);
        
        return $data;
    }
}