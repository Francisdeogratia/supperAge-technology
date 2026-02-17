<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupCallInitiated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $callId;
    public $groupId;
    public $groupName;
    public $callType;
    public $initiatorId;
    public $initiatorName;
    public $agoraChannel;

    public function __construct($callId, $groupId, $groupName, $callType, $initiatorId, $initiatorName)
    {
        $this->callId = $callId;
        $this->groupId = $groupId;
        $this->groupName = $groupName;
        $this->callType = $callType;
        $this->initiatorId = $initiatorId;
        $this->initiatorName = $initiatorName;
        $this->agoraChannel = 'group_call_' . $callId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('group.' . $this->groupId);
    }

    public function broadcastAs()
    {
        return 'GroupCallInitiated';
    }
}