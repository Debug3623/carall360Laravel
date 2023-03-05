<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $oMsg;
    public $oUser;
    public $iChatId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($oMsg, $oUser,$iChatId)
    {
        $this->oMsg     =   $oMsg;
        $this->oUser    =   $oUser;
        $this->iChatId  =   $iChatId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('user_message.'. $this->iChatId);
    }

    public function broadcastWith()
    {
        return [
            'user_id'       => $this->oUser->id,
            'id'            => $this->oMsg->id,
            'body'          => $this->oMsg->body,
            'created_by'    => $this->oMsg->created_by,
            'created_at'    => $this->oMsg->created_at->toDateTimeString(),
            'is_seen'       => $this->oMsg->is_seen,
        ];
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
