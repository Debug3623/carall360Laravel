<?php

namespace App\Events;

use App\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AllChatUsers implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $oUser;
    public $oChatHead;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($oUser,$oChatHead)
    {
        $this->oUser        =   $oUser;
        $this->oChatHead    =   $oChatHead;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat_users.'. $this->oChatHead->doctor_user_id);
    }

    public function broadcastWith()
    {
        if($this->oUser->isDoctor()){
			$oChats 		=	Chat::where('doctor_user_id',$this->oUser->id)->with('patientUser','latestMessage')->get()->sortByDesc('latestMessage.created_at')->values();
            chatUserImage($oChats); 
            return [
                'data'       => $oChats,
            ];
		}
		if($this->oUser->isPatient()){
			$oChats 		=	Chat::where('doctor_user_id', $this->oChatHead->doctor_user_id)->with('patientUser','latestMessage')->get()->sortByDesc('latestMessage.created_at')->values();
            chatUserImage($oChats); 
            return [
                'data'       => $oChats,
            ];
		}
    }

    public function broadcastAs()
    {
        return 'list';
    }
}
