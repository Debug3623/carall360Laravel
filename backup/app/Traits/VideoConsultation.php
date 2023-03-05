<?php

namespace App\Traits;

use App\TimeSlot;
use Carbon\Carbon;
use Exception;
use Twilio\Jwt\AccessToken;
use Twilio\Rest\Client;
use Twilio\Jwt\Grants\VideoGrant;

trait VideoConsulatation {
    private $sid;
    private $token;
    private $key;
    private $secret;

    private function envFiles (){
        //Live 
        $this->sid = env('TWILIO_ACCOUNT_SID','AC5f1f9b58450d014ca7fab513bd877793');
        $this->token = env('TWILIO_ACCOUNT_TOKEN','c0c82636f97e0ed7f2294f7a5b2e74b0');
        $this->key = env('TWILIO_API_KEY','SKf0c5b6b45656d491d95717c7477a166f');
        $this->secret = env('TWILIO_API_SECRET','5AnqlWHmeBI9A2sbRWTEsquvoxNNlyiI');
        // Staging
        // $this->sid = env('TWILIO_ACCOUNT_SID','ACe1fa9c3a56e24061276fb549740ecdbe');
        // $this->token = env('TWILIO_ACCOUNT_TOKEN','d5e70fb4ad0ff7ae899f43d7d1b99b4c');
        // $this->key = env('TWILIO_API_KEY','SK5910d189402a003fe84e07e8d29538b3');
        // $this->secret = env('TWILIO_API_SECRET','Wkxesc5sQ974e3HdM0Rmuw4sSDudXt0N');
        // Dev
        // $this->sid = env('TWILIO_ACCOUNT_SID','AC6692ea20d88ccdfb9b2da5d0012ad82e');
        // $this->token = env('TWILIO_ACCOUNT_TOKEN','c66714a5bb2d94e27ebc579c84b1dcfa');
        // $this->key = env('TWILIO_API_KEY','SK3ce52ff4553299d13d06d75aac11aaa1');
        // $this->secret = env('TWILIO_API_SECRET','uNIvPjZjSJXHiBl3uGXjmtv0vPYCojTy');
    }

    // List of All Created Rooms
    public function listOfRooms()
    {
       $this->envFiles(); 
        $rooms = [];
        try {
            $client = new Client($this->sid, $this->token);
            $allRooms = $client->video->rooms->read([]);

                $rooms = array_map(function($room) {
                return $room->uniqueName;
                }, $allRooms);

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return $rooms;
    }
    public function createRoom($room_name)
    {
        $this->envFiles();

        $client = new Client($this->sid, $this->token);

        $exists = $client->video->rooms->read([ 'uniqueName' => $room_name]);

        if (empty($exists)) {
            $client->video->rooms->create([
                'uniqueName' => $room_name,
                'type' => 'group',
                'recordParticipantsOnConnect' => false
            ]);
        }

        return response()->json(["message"=>"Room Created Successfully"],200);
    }
    public function joinRoom($identity , $room_name)
    {
        $this->envFiles();

        $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);

        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($room_name);

        $token->addGrant($videoGrant);
       
        return $token->toJWT();
        return response()->json([ 'token' => $token->toJWT(), 'room_name' => $room_name,'identity' => $identity ]);
    }
     public function generate_token($identity , $room_name)
    {
        $this->envFiles();
        // Substitute your Twilio Account SID and API Key details
        $accountSid   = $this->sid;
        $apiKeySid    = $this->key;
        $apiKeySecret = $this->secret;
        // Create an Access Token
        $token = new AccessToken(
            $accountSid,
            $apiKeySid,
            $apiKeySecret,
            3600,
            $identity,
            $room_name
        );

        // Grant access to Video
        $grant = new VideoGrant();
        // $grant->setRoom('');
        $token->addGrant($grant);

        // Serialize the token as a JWT
        $result=[ 
            "identity" => $identity,
            "token"=> $token->toJWT()
        ];

        return response()->json($result);
    }
    public function onAppointmentApprove($appointment){
        $newStatus = $appointment->status;
        
        $patientEmail  =  $appointment->patientId->email;
        $patientPhone  =  $appointment->patientId->phone;
        $patientName   =  $appointment->patientId->name;
        $patientId     =  $appointment->patientId->pk;
        $patientIdentity =   $appointment->reference_no.'patient'.$patientId;

        $doctorEmail  =  $appointment->doctorId->email;
        $doctorPhone  =  $appointment->doctorId->phone;
        $doctorName   =  $appointment->doctorId->full_name;   
        $doctorBaseUrl=  $appointment->doctorId->url;   
        $doctorId     =  $appointment->doctorId->pk;
        $doctorTitle  =  $appointment->doctorId->title;
        $doctorIdentity =   $appointment->reference_no.'doctor'.$doctorId;
        
        $room =   $appointment->reference_no;

        $timeSlot     = TimeSlot::where('id',$appointment->slot_id)->first();
        $date         = Carbon::parse($appointment->appointment_date);                                                // Appointment date
        $fdate        = $date->format('jS F Y');
        $time         = date('h:i A', strtotime($timeSlot->slot));
        $n            = '\n';
        if($appointment->appointment_type == 'physical'){
            $patientMessage = "Your Physical appointment is confirmed with the following details:".$n.$n."Doctor Name: $doctorTitle. $doctorName.".$n."Date: $fdate ".$n."Time: $time";
            $doctorMessage  = "Your Physical appointment is confirmed with the following details:".$n.$n."Patient Name: $patientName.".$n."Date: $fdate ".$n."Time: $time";
            
            if(isset($patientMessage) && isset($patientPhone)){
                smsGateway($patientPhone, $patientMessage);
            }
            if(isset($patientMessage) && isset($patientEmail)){
                $patientMessage = str_replace('\n', '<br>', $patientMessage);
                $emailTitle = "Physical Appointment";
                emailGateway($patientEmail, $patientMessage, $emailTitle);
            }
            if(isset($doctorMessage) && isset($doctorPhone)){
                smsGateway($doctorPhone, $doctorMessage);
            }
            if(isset($doctorMessage) && isset($doctorEmail)){
                $doctorMessage = str_replace('\n', '<br>', $doctorMessage);
                $emailTitle = "Physical Appointment";
                emailGateway($doctorEmail, $doctorMessage, $emailTitle);
            }
        }else{
            $this->createRoom($room);
            $url  = uniqid();

            $doctorUrl    = config("app")['FRONTEND_URL'].'online_consultancy/'.$url;
            $patientUrl   = config("app")['FRONTEND_URL'].'online_consultancy/'.$doctorBaseUrl.'/'.$url;

            $patientMessage = "Your online appointment is confirmed with the following details:".$n.$n."Doctor Name: $doctorTitle. $doctorName.".$n."Date: $fdate ".$n."Time: $time ".$n."Consultation Link: $patientUrl";
            $doctorMessage  = "Your online appointment is confirmed with the following details:".$n.$n."Patient Name: $patientName.".$n."Date: $fdate ".$n."Time: $time ".$n."Consultation Link: $doctorUrl";
            
            if(isset($patientMessage) && isset($patientPhone)){
                smsGateway($patientPhone, $patientMessage);
            }
            if(isset($patientMessage) && isset($patientEmail)){
                $patientMessage = str_replace('\n', '<br>', $patientMessage);
                $emailTitle = "Online Consultation";
                emailGateway($patientEmail, $patientMessage, $emailTitle);
            }
            if(isset($doctorMessage) && isset($doctorPhone)){
                smsGateway($doctorPhone, $doctorMessage);
            }
            if(isset($doctorMessage) && isset($doctorEmail)){
                $doctorMessage = str_replace('\n', '<br>', $doctorMessage);
                $emailTitle = "Online Consultation";
                emailGateway($doctorEmail, $doctorMessage, $emailTitle);
            }
            $appointment->update([
                'tele_url' => $url,
                'tele_password' => $room,
            ]);
        }
        
        return $newStatus; 
    }
}