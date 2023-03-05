<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Twilio\Jwt\AccessToken;
use Twilio\Rest\Client;
use Twilio\Jwt\Grants\VideoGrant;
use Illuminate\Support\Facades\Validator;

class GenerateAccessTokenController extends Controller
{
    protected $sid;
    protected $token;
    protected $key;
    protected $secret;

    public function __construct()
    {
        $this->sid = env('TWILIO_ACCOUNT_SID');
        $this->token = config('services.twilio.token');
        $this->key = env('TWILIO_API_KEY');
        $this->secret = env('TWILIO_API_SECRET');
    }
    public function index()
    {
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
        return view('index', ['rooms' => $rooms]);
    }
    public function createRoom(Request $request)
    {
        $oInput = $request->all();
        
        $oValidator = Validator::make($oInput,[
            'room_name'  => 'required',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $client = new Client($this->sid, $this->token);

        $exists = $client->video->rooms->read([ 'uniqueName' => $request->room_name]);

        if (empty($exists)) {
            $client->video->rooms->create([
                'uniqueName' => $request->room_name,
                'type' => 'group',
                'recordParticipantsOnConnect' => false
            ]);

            \Log::debug("created new room: ".$request->room_name);
        }

        return response()->json(["message"=>"Room Created Successfully"],200);
    }
    public function joinRoom(Request $request)
    {
        $oInput = $request->all();
        
        $oValidator = Validator::make($oInput,[
            'identity'   => 'required',
            'room_name'  => 'required',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $oInput['identity']);

        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($oInput['room_name']);

        $token->addGrant($videoGrant);
        
        return response()->json([ 'token' => $token->toJWT(), 'room_name' => $oInput['room_name'],'identity' => $oInput['identity'] ]);
    }
     public function generate_token(Request $request)
    {
        $oInput = $request->all();
        
        $oValidator = Validator::make($oInput,[
            'identity'   => 'required',
            'room_name'  => 'required',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        // Substitute your Twilio Account SID and API Key details
        $accountSid   = $this->sid;
        $apiKeySid    = $this->key;
        $apiKeySecret = $this->secret;
        $identity = $request->identity;
        $room_name = $request->room_name;
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
}