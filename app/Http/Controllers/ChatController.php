<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Doctor as DOC;
use App\Patient as PAT;
use App\User as USER;
use App\Chat as CHAT;
use App\Events\AllChatUsers;
use App\Message as MSG;
use App\Events\CreateMessage;
use Carbon\Carbon;

use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ChatController extends Controller
{

	public function allUsers()
	{
		$oUser 	= auth()->user();
		if($oUser->isDoctor()){
			$chats 		=	CHAT::where('doctor_user_id',$oUser->id)->with('patientUser','latestMessage')->get()->sortByDesc('latestMessage.created_at')->values();
			chatUserImage($chats); 
			return responseBuilder()->success(null,$chats, false);
		}
		if($oUser->isPatient()){
			$chats 		=	CHAT::where('doctor_user_id',$oUser->id)->with('doctorUser','latestMessage')->get()->sortByDesc('latestMessage.created_at')->values();
			chatUserImage($chats);
			return responseBuilder()->success(null,$chats, false);
		}
	}

    public function initChat(Request $input, $patient_id = '')
    {

    	$oUser = auth()->user();
    	if($oUser->isDoctor()){

    		if(empty($patient_id))
    			return responseBuilder()->error(__('Patient missing to start chat'), 403, false);

    		$doctor_usr_id = auth()->user()->id;

    		$patient_usr_id = $patient_id;
    	}

    	if($oUser->isPatient()){
    		$doctor_usr_id = auth()->user()->doctor->user->id;
    		$patient_usr_id = auth()->user()->id;
    	}


    	//check if already have chat initiated
    	$oChanRec = CHAT::where('doctor_user_id', $doctor_usr_id)->where('patient_user_id', $patient_usr_id)->first();

    	if(!empty($oChanRec->id)){
			if($oUser->isDoctor()){
				broadcast( new AllChatUsers($oUser,$oChanRec));
			}
			return responseBuilder()->success('', $oChanRec->id, false); 
		}
    	



    	$aChatTbl = array(
    		'doctor_user_id' 		=> $doctor_usr_id,
    		'patient_user_id' 		=> $patient_usr_id,
    		'is_block'				=> '0',
    		'is_allow'				=> '1',
			'created_by'			=> auth()->user()->id,
			'created_at'    		=>  Carbon::now()->toDateTimeString(),
    	);

		$bChat = CHAT::create($aChatTbl);
		if($oUser->isDoctor()){
			broadcast( new AllChatUsers($oUser,$bChat));
		}
    	if($bChat->id)
    		return responseBuilder()->success('CHAT INITIATED', $bChat->id, false);

    }


    public function getChatMessageCount($chat_id, $internal = false)
    {
    	$iChatId = $chat_id;

    	$iChanRec = CHAT::where('id', $iChatId)->count();

    	if($iChanRec < 1)
    		responseBuilder()->success('', -1, false);

    	$iMsgCount = MSG::where('chat_id', $iChatId)->count();

    	if($internal)
    		return $iMsgCount;

    	return responseBuilder()->success('', $iMsgCount, false);
    }

    /**
     * Return lastest $count entries on chat with $offset
     */
    public function loadChatHistory($chat_id, $count = 10, $offset = 0)
    {
    	$iChatId = $chat_id;

    	$oChat = CHAT::where('id', $chat_id)->first();

    	if($count < 1)
    		$count = 10;

    	$oMessages = MSG::select(['id', 'body', 'created_by', 'created_at'])
    						->where('chat_id', $iChatId)
    						->offset($offset)
							->take($count)
							->latest()
    						->get()
							->reverse()
							->values();


    	// TO mark any messages which are not being dispayed to other users
    	$aToUpdateSeen = array();
    	foreach($oMessages as $i => $msg){
    		if($msg['created_by'] != auth()->user()->id)
    			$aToUpdateSeen[] = $msg['id']; 
    	}

    	if(count($aToUpdateSeen) > 0){

    		//update message to seen

    		MSG::whereIn('id', $aToUpdateSeen)->update(['is_seen' => 1]);

    	}


    	return responseBuilder()->success('', $oMessages, false);

    }


    /**
     * TO send new message in chat
     */
    public function doMessage(Request $input, $chat_id)
    {
    	$aData = $input->all();

    	$iChatId = $chat_id;

    	if(empty($aData['msg']))
    		return responseBuilder()->error(__('Empty Message Not Allowed'), 403, false);
    	
    	if(strlen($aData['msg']) > 3000)
    		return responseBuilder()->error(__('Message Size Error'), 403, false);

    	$oChatHead = CHAT::where('id', $iChatId)->first();


    	if(empty($oChatHead->id))
    		return responseBuilder()->error(__('Not Found Chat'), 403, false);

    	if($oChatHead->is_block == 1){
    		return responseBuilder()->error(__('Chat Blocked'), 403, false);
    	}

    	if( !in_array(auth()->user()->id, array($oChatHead->doctor_user_id, $oChatHead->patient_user_id))  )
    		return responseBuilder()->error(__('Not Allowed To Chat (External User)'), 403, false);

    	$oMsg = MSG::create([
    		'type' 				=> 'text',
    		'body' 				=> $aData['msg'],
    		'user_id' 			=> auth()->user()->id,
			'created_by'		=> auth()->user()->id,
			'created_at'    	=>  Carbon::now()->toDateTimeString(),
    		'chat_id'			=> $iChatId
    	]);

    	if($oMsg->id){
			$oUser 	=	auth()->user();
			broadcast( new CreateMessage($oMsg, $oUser,$iChatId));
			broadcast( new AllChatUsers($oUser,$oChatHead));

			return responseBuilder()->success('SENT','', false);
		}

    	return responseBuilder()->error(__('Unable to send'), 403, false);
    }


    /**
     * Shall work to load the chat of other user in same windows in listener mod
     * and mark new message as seen, so that should not fetch again
     */
    public function listener($chat_id, $last_message_id)
    {

    	$iChatId = $chat_id;

    	$oChat = CHAT::where('id', $iChatId)->first();

    	if(empty($oChat->id))
    		return responseBuilder()->error(__('Not Found Chat'), 403, false);

    	if( !in_array(auth()->user()->id, array($oChat->doctor_user_id, $oChat->patient_user_id))  )
    		return responseBuilder()->error(__('Not Allowed To Chat (External User)'), 403, false);

    	if($this->getChatMessageCount($chat_id, true) < 1 ){
	    	$oMsg = MSG::where('chat_id', $iChatId)
	    				->where('is_seen', 0)
	    				->orderBy('created_at', 'desc')
	    				->get()
	    				->toArray();

    	}else{
	    	$oMsg = MSG::where('chat_id', $iChatId)
	    				->where('is_seen', 0)
	    				->where('id', '>', $last_message_id)
	    				->orderBy('created_at', 'desc')
	    				->get()
	    				->toArray();

    	}


    	if(!empty($oMsg)){

    		foreach($oMsg as $i => $msg){

    			if($msg['created_by'] !== auth()->user()->id)
    				MSG::where('id', $msg['id'])->update(['is_seen' => 1]);

    		}

    	}


    	return responseBuilder()->success('SENT', $oMsg, false);
    }

}
