<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Doctor;
use App\Patient;
use App\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Auth;
use DB;
use Mail;
use Carbon\Carbon;
use App\Mail\ForgetPassword;
use App\Mail\GeneralAlert;
use App\Jobs\SendEmail;


class VendorController extends Controller
{

    use \App\Traits\WebServicesDoc;
    use \Illuminate\Foundation\Auth\ThrottlesLogins;


    public function username()
    {
        return 'username';
    }
    
        
    /**
     * Is Already user / patient of doctor
     */
    public function isAlreadyUser(Request $request, $doctor_id)
    {

        $iDoctorId = decrypt($request->doctor_id);
      
        $aData = $request->all();
        
        $sUserName = $aData['username'];
        
    	$oUser = User::where('doctor_id', $iDoctorId)->where('username', $sUserName)->first();

        $aResponseReturn = array();
        $aResponseReturn['FOUND'] = 0;
        $aResponseReturn['VERIFY'] = 0;

        
        if(!empty($oUser->patient_id)){
            $aResponseReturn['FOUND'] = 1;
            $aResponseReturn['VERIFY'] = $oUser->phone_verified;

            if($oUser->phone_verified != 1){
                //if not verified, then generate and share the SMS code to patient for verification
                $iPhoneVerifyCode = rand(100000,999999);
                $oVeriCodePhone = VerificationCode::create([
                    'user_id'           => $oUser->id,
                    'code'              => $iPhoneVerifyCode,
                    'type'              => 'phone',
                    'expiry_timestamp'  => Carbon::now()->addMinutes(60), //1 hr expiry time
                ]);

                $aSmsSent = smsGateway($oUser->phone, "CODE ".$iPhoneVerifyCode, true);

                $aResponseReturn['CODE_SENT'] = $aSmsSent['SUC'];
                
                $aResponseReturn['identifier'] = encrypt($oUser->id);
                
                //if(app()->isLocal())
                    $aResponseReturn['CODE'] = $iPhoneVerifyCode;
                    
            }

            
        }

        $oResponse = responseBuilder()->success('', $aResponseReturn);
       
        return $oResponse;
    }
   

}
