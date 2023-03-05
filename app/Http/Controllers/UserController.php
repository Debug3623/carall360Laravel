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

use Auth;
use DB;
use Mail;
use Carbon\Carbon;

use App\Mail\ForgetPassword;
use App\Mail\GeneralAlert;
use App\Jobs\SendEmail;


class UserController extends Controller
{

    use \App\Traits\WebServicesDoc;
    use \Illuminate\Foundation\Auth\ThrottlesLogins;


    public function username()
    {
        return 'username';
    }


    /**
     * Login function takes username and password ass 2 params
     * after attempting to auth, and passed, it will return the token, actor type
     * and profile data for actor, like doctor or patient
     * Login source will take input as 
     */
    public function login(Request $request, $doctor_id = ''){

        
        $oInput = $request->all();
        if(isset($oInput['date']) && isset($oInput['slot'])){
            $selectedDateTime = Carbon::createFromTimestamp(strtotime($oInput['date'] . $oInput['slot']))->toDateTimeString();    
            $currentDateTime = Carbon::now()->toDateTimeString();    
            if($currentDateTime >= $selectedDateTime){
                return responseBuilder()->error(__('message.schedule.previous'), 404, false);
            }
        }
        $sHeaderToken = $request->header('token');
        
        $bIsOtpBasedLogin = request('otp') == 1 ? true : false;

        $sLoginSource = request('source');

        $iDoctorId = '';
        if(!empty($doctor_id))
            $iDoctorId = decrypt($doctor_id);

        //check if the user has too many login attempts.
        if ($this->hasTooManyLoginAttempts($request)) {
            //Fire the lockout event.
            $this->fireLockoutEvent($request);

            //respond the user back after lockout.
            return $this->sendLockoutResponse($request);
        }

        if(empty($sLoginSource))
             return responseBuilder()->error(__('auth.source_not_set'), 401, false);

        if(!isset(config('app.LOGIN_RESTRICT')[$sLoginSource]))
             return responseBuilder()->error(__('auth.invalid_source'), 401, false);

        if(!empty(request('username')) && !empty(request('password')))
        {

            if($bIsOtpBasedLogin)
            {
                //get user id from username provided

                if($sLoginSource == "BOP"){          // Back Staff OTP
                    $oUser = User::select('id', 'password', 'password_store')->where('username', request('username'))->whereNull('doctor_id')->whereNull('patient_id')->first();
                }elseif($sLoginSource == "FDL"){     // Doctor OTP 
                    $oUser = User::select('id', 'password', 'password_store')->where('username', request('username'))->where('patient_id')->whereNotNull('doctor_id')->first();
                }elseif($sLoginSource == "FLP"){     // Patient OTP 
                    $oUser = User::select('id', 'password', 'password_store')->where('username', request('username'))->where('doctor_id',$iDoctorId)->whereNotNull('patient_id')->first();
                }else{                              // Patient OTP
                    if(!empty($iDoctorId)){
                        $oUser = User::select('id', 'password', 'password_store')->where('username', request('username'))->where('doctor_id',$iDoctorId)->whereNotNull('patient_id')->first();
                    }else{
                        $oUser = User::select('id', 'password', 'password_store')->where('username', request('username'))->first();
                    }      
                }
                if(!empty($iDoctorId))
                    $oUser = User::select('id', 'password', 'password_store')
                                ->where('username', request('username'))
                                ->where('doctor_id', $iDoctorId)
                                ->first();
                else
                    $oUser = User::select('id', 'password', 'password_store')
                                ->where('username', request('username'))
                                ->first();
                
                
                if(empty($oUser->id))
                    return responseBuilder()->error(__('auth.otp.not_found'), 401, false);

                $mIsOtpCodeValid = $this->isPhoneCodeValid(  $oUser->id  , request('password'));

                if(!$mIsOtpCodeValid)
                    return responseBuilder()->error(__('auth.otp.expired_code'), 401, false);


                // check if password_store is not empty then user 
                // is being logged in by using the OTP code
                // in this case, by using the password_store
                // and OTP code will be discarded

                User::where('id', $oUser->id)->update(['password_store' => null]);
                //mark used the OTP code generated
                VerificationCode::where('id', $mIsOtpCodeValid)->update(['is_used' => 1]); 

                Auth::loginUsingId($oUser->id);

                return $this->loginResponseWithActiveCheck($request, true, $iDoctorId);
               
            }
            if(isset($oInput['identifier'])){
                $iUserIdForlogin = decrypt($oInput['identifier']);
                $oUser = User::where('id',$iUserIdForlogin)->first();
                $oInput['password'] = $oUser->password; 
                
                Auth::loginUsingId($iUserIdForlogin);
                return $this->loginResponseWithActiveCheck($request, true, $iDoctorId);
            }
	        //dump(request('username') ."~~".request('password'));
            $aAuthParams = array(
                'username' => request('username'), 
                'password' => request('password')
            );

            if(!empty($iDoctorId))
                $aAuthParams['doctor_id'] = $iDoctorId;
            
               

            //dump($aAuthParams);
            if(Auth::attempt($aAuthParams)){

                return $this->loginResponseWithActiveCheck($request, false, $iDoctorId);

            }

        }

        //keep track of login attempts from the user.
        $this->incrementLoginAttempts($request);

        $response = responseBuilder()->error(__('auth.failed'), 401, false);
        $this->urlComponents(config("businesslogic")[101]['menu'][1], $response, config("businesslogic")[101]['title']);
        return $response;
     

    }
    
    
     public function register(Request $request)
    {   
   
            $oInput = $request->all();
            $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
            $oInput['phone'] = formatPhone($oInput['phone']);
            $oValidator = Validator::make($oInput,[
            'name'        => 'required|string|max:50|min:3',
 
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        if(isset($request->image)){
            $imageExtension = $request->image->extension();
            if($imageExtension != "png" && $imageExtension != "jpg" && $imageExtension != "jpeg"){
                abort(400,"The image must be a file of type: jpeg, jpg, png.");
            }
        }
       
        $oValidator = Validator::make($oInput,[
            'email' => 'nullable|email|max:50|unique:patients,email,null,null,doctor_id,'.$oInput['doctor_id'],
            // 'phone' => 'required|digits_between:11,16|unique:patients,phone,null,null,doctor_id,'.$oInput['doctor_id'],
        ]);
       
               
        if(isset($oInput['cnic'])){
            $oCheckCnicAlready = Patient::where('cnic',$oInput['cnic'])->first();
            if($oCheckCnicAlready){
                return responseBuilder()->error(__('CNIC is already exist'), 403, false);
            }
        }
      
      
         //$oInput['patientId']='3450224929376';
         if(isset($oInput['patientId'])){
            $oCheckCnicAlready = Patient::where('cnic',$oInput['patientId'])->first();
            if(!$oCheckCnicAlready){
                return responseBuilder()->error(__('CNIC is not exist'), 403, false);
            }
        }
        
        
        $oDoctor = Doctor::where('id', $oInput['doctor_id'])->first();
  

        if($oDoctor->phone == $oInput['phone']){
            $oResponse = responseBuilder()->error(__('auth.doctor_phone_used'), 403, false);
            return $oResponse; 
        }

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }       
        $oCheckPaientAlready = Patient::where('register_number',$oInput['register_number'])->first();
        if($oCheckPaientAlready){
            return responseBuilder()->error(__('Register number is already exist'), 403, false);
        }

    
        $sPassword = rand(111111,999999);

        $oPatient = Patient::create([
            'doctor_id'     => $oInput['doctor_id'],
            'name'          => strtoupper($oInput['name']),
            'email'         => $oInput['email'],
            'phone'         => $oInput['phone'],
            'borrower_phone' => $oInput['borrower_phone'],
            'cnic'          => $oInput['cnic'],
            'register_number'  => $oInput['register_number'],
            'patientId'     =>  $oInput['patientId'],
            'ref_number'    => strtoupper(uniqid()),
            'country_code'  => isset($oInput['country_code'])? $oInput['country_code']:'PAK', //dafault for every patient
            'city_id'       => $oInput['city_id'], 
            'patient_status' => $oInput['patient_status'],
            'gender'        => $oInput['gender'], 
            'address'       => $oInput['address'], 
            'dob'           => $oInput['dob'], 
            'age'           => $oInput['age'],
            'organization'  => $oInput['organization'],
            'marital_status'=> $oInput['marital_status'], 
            'blood_group'   => $oInput['blood_group'], 
            'remarks'       => $oInput['remarks'], 
            'height'        => $oInput['height'], 
            'weight'        => $oInput['weight'], 
            'created_by'    =>  Auth::user()->id,
            'updated_by'    =>  Auth::user()->id,
            "patient_date"  => $oInput['patient_date'],
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $oUser = User::create([
            'name'              =>  $oInput['name'],
            'phone'             =>  $oInput['phone'],
            'register_number'  => $oInput['register_number'],
            'email'             =>  $oInput['email'],
            'username'          =>  $oInput['phone'],
            'patient_id'        =>  decrypt($oPatient->id),
            'doctor_id'         =>  $oInput['doctor_id'],
            'password'          =>  Hash::make($sPassword),
            'phone_verified'    =>  1,
            'created_by'        =>  Auth::user()->id,
            'updated_by'        =>  Auth::user()->id,
            'created_at'        =>  Carbon::now()->toDateTimeString(),
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
        ]);
        $image = null;
        if(isset($request->image)){

            $imageExtension = $request->image->extension();
            
            if($imageExtension != "png" && $imageExtension != "jpg" && $imageExtension != "jpeg"){

            }
            $oPaths = FileHelper::saveImages($request->image,'patients');

            $media = Media::create([
                'user_id'   => $oUser->id,
                'url'       => $oPaths['url'],
                'alt_tag'   => $oInput['name'],
                'type'      => 'profile',
                'created_by'=> Auth::user()->id,
                'updated_by'=> Auth::user()->id,
                'created_at'=> Carbon::now()->toDateTimeString(),
                'updated_at'=> Carbon::now()->toDateTimeString(),
            ]);
            $image = $media->url;;
        }
        $role_name ="Patient";

        $oRole = Role::where('name',$role_name)->first();
        $oUser->roles()->sync($oRole->id);
       
        $oPatient = Patient::with(['user','doctor','createdBy','updatedBy','restoredBy','deletedBy','cityId','countryCode'])->findOrFail(decrypt($oPatient->id));
        
        $oPatient['image'] = isset($image)? config("app")['url'].$image:null;

        $n = '\n';
        $doctorName = $oPatient->doctor->title.' '.$oPatient->doctor->full_name;
        $url = 'http://clinicall.digitecglobal.com/'.'doctor/'.$oPatient->doctor->url;
        $message = "Welcome to ClinicAll. $doctorName has added you as a patient. Your login details are:".$n.$n."Link: $url".$n."Username: $oPatient->phone".$n."Password: $sPassword";
        $aSmsSent = smsGateway($oPatient->phone, $message, true);
        if($oPatient->email){
            $message = str_replace('\n', '<br>', $message);
            dispatch(new SendEmail(Mail::to($oInput['email'])->send(new GeneralAlert("Welcome to the ClinicALL", $message))));
        }
        $oResponse = responseBuilder()->success(__('message.general.created',
            ["mod"=>"Patient", 'code' => app()->isLocal() ? $sPassword:'']), $oPatient, false);
        
        $this->urlComponents(config("businesslogic")[23]['menu'][1], $oResponse, config("businesslogic")[23]['title']);
        
        return $oResponse;
    }


    /**
     * Check user is active or disabled and returned the response code with appropriate user attributes
     * Work for OTP based as well as regular login/password based authentication
     * $doctor_id is in plain value
     */
    public function loginResponseWithActiveCheck(Request $request, $bLoginViaOtp = false, $doctor_id = '')
    {
       
        $user = Auth::user(); 

        // if($user->isPatient() && empty($doctor_id)){
            
        //     LOG::info("EMPTY URL");
        //     Auth::logout();

        //     $oResponse = responseBuilder()->error(__('auth.patient.invalid_url'), 403, false);
        //     $this->urlRec(101, 7, $oResponse);
            
        //     return $oResponse;
        // }

        //for patient we need to verify the doctor id associated from the patient profile
        if(!empty($doctor_id)){

            $oDoctor = Doctor::select('is_active')->findOrFail($doctor_id);

            if($oDoctor->is_active != 1){
                LOG::info("DOCTOR NOT ACTIVE");
                //doctor is no more active - should logout
                Auth::logout();

                $oResponse = responseBuilder()->error(__('auth.patient.inactive_doctor'), 404, false);
                $this->urlRec(101, 8, $oResponse);
            
                return $oResponse;
            }
        }


        if(!$user->isUserActive()){
            LOG::info("USER INACTIVE");
            Auth::logout();

            $oResponse = responseBuilder()->error(__('auth.inactive'), 404, false);
            $this->urlRec(101, 3, $oResponse);
            
            return $oResponse;
        }


        //check if user is soft deleted
        if($user->isSoftDeleted()){
            LOG::info("USER SOFT DELETED");
            Auth::logout();

            $oResponse = responseBuilder()->error(__('auth.deleted'), 404, false);
            $this->urlRec(101, 3, $oResponse);
            
            return $oResponse;
        }


        /**
         * Restrict Back office staff to login into doctor/patient portal, AND
         * Restrict doctor/patient to login into Back Office panel
         */
        $sLoginSource = request('source');

        $success = array();

        $success['actor'] =  $user->getActor();


        if(!in_array($success['actor'], config('app.LOGIN_RESTRICT')[$sLoginSource])){

            LOG::info("INVALID SOURCE");

            //keep track of login attempts from the user.
            $this->incrementLoginAttempts($request);

            Auth::logout();
            $oResponse = responseBuilder()->error(__('auth.failed'), 401, false);
            // $oResponse = responseBuilder()->success(__('auth.notallowed'), '', false);
            $this->urlComponents(config("businesslogic")[101]['menu'][5], $oResponse, config("businesslogic")[101]['title']);
            
            return $oResponse;


        }
        
        $success['success'] =  true; 
        $success['token'] =  $user->createToken('appToken')->accessToken; 
        $success['actor'] =  $user->getActor();
        
        $success['profile'] =  $user->getProfile($success['actor']);

        if(auth()->user()->isDoctor())
            $success['enc_id'] =  encrypt(auth()->user()->doctor_id);
        // $success['permission'] =  $this->getMyAssignedPermissions(true);
        $aRoles =  $user->roles()->with('permissions')->get();
        foreach($aRoles as $role){
          $role = $role; 
        }
        
        $success['menu'] = $role->menu()->get();
        
        if($bLoginViaOtp)
            $success['otp_login'] =  true; 

        
        if(auth()->user()->isPatient()){
            $oInput = $request->all();
            $oInput['patient_id'] = $user->patient_id;
            $oInput['doctor_id'] = $user->doctor_id;
            $oInput['lead_from'] = "website";
            if(isset($oInput['type']) && isset($oInput['date'])){
                $bookAppointment = bookAppointment($oInput);
            }
        }
        if(isset($bookAppointment)){
            $appointment_id = $bookAppointment->id;
            $url = "patient/payment/".$appointment_id;
        }
        $success['url'] = isset($url)? $url: null;
        $response = responseBuilder()->success(__('auth.success'), $success, false);

        //LOG::info("LOGGED IN\n".print_r($response, true));

        if($bLoginViaOtp)
            $this->urlComponents(config("businesslogic")[101]['menu'][6], $response, config("businesslogic")[101]['title']);
        else
            $this->urlComponents(config("businesslogic")[101]['menu'][0], $response, config("businesslogic")[101]['title']);
        
        return $response;

    }


    /**
     * For OTP based login attempt, validate given OTP code against the requesting login user
     * 
     */
    public function isPhoneCodeValid($user_id, $code)
    {
        
        $oVerify = VerificationCode::where('user_id',$user_id)->where('type', 'phone')->where('code', $code)->first();
        if(empty($oVerify->id)){
          
            $oVerify = VerificationCode::with(['phone' => function($oQuery) use($user_id) {
                            $oQuery->where('id', $user_id);
                        }])
                        ->where('type', 'phone')
                        ->where('code', $code)
                        ->first();
        }
        if(empty($oVerify->id))
            return false;

        //already used code to remember
        if($oVerify->is_used == 1)
            return false;            

        $oExpiryDate = Carbon::parse($oVerify->expiry_timestamp);

        if($oExpiryDate->lt(Carbon::now()))
            return false;

        return $oVerify->id;
    }


    /**
     * Any logged-in user can render its profile and associated data to view
     * in case of doctor and patient, API's extended index should be reffered to view
     * else in case of back end staff exnteded will not be populated
     */
    public function my_profile(Request $request){
        
        $oUser = auth()->user();

        //dd($oUser);

        $aResponseReturn = array();
        $aResponseReturn['primary'] = $oUser;
        
        if(auth()->user()->isDoctor())
            $aResponseReturn['extended'] = auth()->user()->doctor;

        if(auth()->user()->isPatient())
            $aResponseReturn['extended'] = auth()->user()->patient;
        
        $response = responseBuilder()->success('', $aResponseReturn, true);
        $this->urlComponents(config("businesslogic")[101]['menu'][2], $response, config("businesslogic")[101]['title']);
        return $response;
        
    }


    /**
     * Get role assigned to logged in user
     */
    public function my_role(){
        $aUserRoles = $this->user_roles(auth()->user()->id, true);

        $oResponse = responseBuilder()->success('', $aUserRoles, true);
        $this->urlComponents(config("businesslogic")[150]['menu'][0], $oResponse, config("businesslogic")[150]['title']);
        return $oResponse;
    }


    /**
     * List of user role assigned
     * this function can also be used to call from internal function for logged in user role
     */
    public function user_roles($user_id, $bForMyRoel = false){

        $oUser = User::findOrFail($user_id);

        $oRelationRoles = DB::table('role_user')
                        ->select('role_id')
                        ->where('user_id', $user_id)
                        ->get()
                        ->pluck('role_id')
                        ->toArray();

        //dump($oRelationRoles);

        $oRoles = Role::whereIn('id', $oRelationRoles)->get()->toArray();
        
        if($bForMyRoel)
            return $oRoles;

        $oResponse = responseBuilder()->success('', $oRoles, true);
        $this->urlComponents(config("businesslogic")[150]['menu'][0], $oResponse, config("businesslogic")[150]['title']);
        return $oResponse;
    }



    /**
     * List of permission a use has against assigned roles
     */
    public function getMyAssignedPermissions($bGetRaw = false){

        $iUserId =  auth()->user()->id;

        $aUserRoles = $this->user_roles($iUserId, true);

        //dump($aUserRoles);

        $oRoles = User::with(['roles' , 'roles.permissions_active' ])
                        ->where('id', $iUserId)
                        ->first()
                        ->toArray();
        
        if($bGetRaw)
            return $oRoles['roles'];

        $oResponse = responseBuilder()->success('', $oRoles['roles'], true);
        $this->urlComponents(config("businesslogic")[150]['menu'][1], $oResponse, config("businesslogic")[150]['title']);
        return $oResponse;
       
    }


    /**
     * Logout user from system by revoking user token issued
     */
    public function logout(){

        $bIsRevoked = Auth::user()->token()->revoke();
        
        $oResponse = responseBuilder()->success(__('auth.logout'), 1, false);
        $this->urlComponents(config("businesslogic")[101]['menu'][4], $oResponse, config("businesslogic")[101]['title']);
        
        return $oResponse;
    }


    /**
     * Forget password utility
     */
    public function forgetPassword(Request $request, $method = 'email', $doctor_id = '')
    {

        $aData = $request->all();
        //dd($request);

        $iDoctorId = '';
        if(!empty($doctor_id))
            $iDoctorId = decrypt($doctor_id);

        $aAvailableMethods = array('email', 'phone');
        if(!in_array($method, $aAvailableMethods))
            return responseBuilder()->error(__('auth.forget.invalid'), 400, false);


        if($method == 'email'){

            $oValidator = Validator::make($aData,[
                'email' => 'required|email|max:50',
            ]);

            if($oValidator->fails())
                abort(400, $oValidator->errors()->first());
        
            $sEmail = $aData['email'];

            if(!empty($iDoctorId)){
                //user must be patient and associated with the doctor_id
                $oUser = User::with(['patient', 'patient.doctor'])
                            ->where('email', $sEmail)
                            ->first();

                //dd($oUser);

                if(!isset($oUser->patient) || !isset($oUser->patient->doctor))
                    return responseBuilder()->error(__('auth.forget.patient.url'), 400, false);
                

                if($iDoctorId != $oUser->patient->doctor->pk)
                    return responseBuilder()->error(__('auth.forget.patient.url'), 400, false);

            }else{
                $oUser = User::where('email', $sEmail)->first();
            }

            
            if(empty($oUser->id))
                return responseBuilder()->error(__('auth.forget.email_error'), 400, false);

            if($oUser->is_active != 1)
                return responseBuilder()->error(__('auth.forget.user_inactive'), 400, false);

            $iMnutesForCodeValidity = config('app.forget_code_expiry');

            $mCodeGenerated = $this->generateVerificationData($oUser->id, $method, $iMnutesForCodeValidity);

            if(!$mCodeGenerated)
                return responseBuilder()->error(__('auth.forget.unable'), 400, false);


            //generate email
            dispatch(new SendEmail(Mail::to($sEmail)->send(new ForgetPassword($oUser, $sEmail, $mCodeGenerated, $iMnutesForCodeValidity))));

            $oResponse = responseBuilder()->success(__('auth.forget.code_sent'));
            $this->urlComponents(config("businesslogic")[103]['menu'][0], $oResponse, config("businesslogic")[103]['title']);
            return $oResponse;
                

        }

        
    }


    /**
     * In case verification code needs to be generated then entry should be available in corresponding verification
     * table row to be verified when user input
     * $iUserId is user id against which email/phone needs to verify
     * $type is of phone or email
     * $iExpiry takes number of minutes as validity of code
     */
    public function generateVerificationData($iUserId, $type = 'email', $iExpiry = 60)
    {

        $iEmailVerifyCode = '123456';

        $oVeriCodePhone = VerificationCode::create([
            'user_id'           => $iUserId,
            'code'              => $iEmailVerifyCode,
            'type'              => $type,
            'expiry_timestamp'  => Carbon::now()->addMinutes($iExpiry),
            'created_at'        => Carbon::now()->toDateTimeString(),
            'updated_at'        => Carbon::now()->toDateTimeString(),
        ]);            


        if($oVeriCodePhone->id)
            return $iEmailVerifyCode;

        return false;

    }

    /**
     * This function helps to verify the code issued against phone/email available in $value
     * 
     */
    public function resetPassword(Request $request, $identifier, $code)
    {

        $aInput = $request->all();


        $sIdentifier = decrypt($identifier);
        $iCode = decrypt($code);

        $sNewPassword = $aInput['password'];

        if(empty($sNewPassword))
            return responseBuilder()->error(__('auth.forget.invalid_code'), 400, false);


        //dump($iCode." ~ ".$sIdentifier);
        $oVerify = VerificationCode::with(['email' => function($oQuery) use($sIdentifier) {
                            $oQuery->where('email', $sIdentifier);
                        }])
                        ->where('type', 'email')
                        ->where('code', $iCode)
                        ->first();

        //dump($oVerify);

        if(empty($oVerify->id))
            return responseBuilder()->error(__('auth.forget.invalid_code'), 400, false);

        $oExpiryDate = Carbon::parse($oVerify->expiry_timestamp);

        //already used code to remember
        if($oVerify->is_used == 1)
            return responseBuilder()->error(__('auth.forget.used_code'), 400, false);            

        if($oExpiryDate->lt(Carbon::now()))
            return responseBuilder()->error(__('auth.forget.expired_code'), 400, false);            

        //$sNewPassword = \Str::random(8);
        //dump($sNewPassword);

        $bPasswordUpdated = User::where('id', $oVerify->email->id)->update([
            'password' => Hash::make($sNewPassword)
        ]);

        $url = '/login';
        $oUser = User::where('id', $oVerify->email->id)->first();
        if(isset($oUser)){
            $patient = $oUser->patient_id;
            if(isset($patient)){
                $doctor = Doctor::where('id',$oUser->doctor_id)->first();
                $url = isset($doctor)? '/'.$doctor->url :'/login';
            }
        }
        //dump($bPasswordUpdated);

        if($bPasswordUpdated){

            VerificationCode::where('id', $oVerify->id)->update(['is_used' => 1]);

            $sMessage = "Your password reset successfully.";

            //generate email for new password
            dispatch(new SendEmail(Mail::to($sIdentifier)->send(new GeneralAlert("Password Reset Successfully", $sMessage))));  

            $oResponse = responseBuilder()->success(__('auth.forget.password_reset_success'), $url, false);
            $this->urlComponents(config("businesslogic")[103]['menu'][1], $oResponse, config("businesslogic")[103]['title']);
            return $oResponse;

        }
        
        return responseBuilder()->error(__('auth.forget.password_fail'), 400, false);

    }

    public function changePassword(Request $request)
    {
        $aInput = $request->all();
        $oAuth = Auth::user();
        
        $oValidator = Validator::make($aInput,[
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $hashedPassword = Auth::user()->password;
 
        if(Hash::check($request->old_password , $hashedPassword )) {
  
            if(!Hash::check($request->new_password , $hashedPassword)) {
  
               $bPasswordUpdated = User::where('id', $oAuth->id)->update([
                    'password' => Hash::make($aInput['new_password'])
                ]);
    
                if($bPasswordUpdated){
        
                    $sMessage = "Your password reset successfully.";
        
                    if(isset($oAuth->email)){
                        dispatch(new SendEmail(Mail::to($oAuth->email)->send(new GeneralAlert("Password Reset Successfully", $sMessage))));  
                    }
                    $oResponse = responseBuilder()->success(__('auth.forget.password_reset_success'));
                    $this->urlComponents(config("businesslogic")[103]['menu'][1], $oResponse, config("businesslogic")[103]['title']);
                    return $oResponse;
                }
            }else{
                    return responseBuilder()->error(__('auth.old_password_can_not_be_new_password'), 400, false);
                }
            }else{

                $mPhoneCondeVerification = $this->isPhoneCodeValid($oAuth->id,$aInput['old_password']);

                if(!$mPhoneCondeVerification)
                    return responseBuilder()->error(__('auth.otp.expired_code'), 401, false);

                VerificationCode::where('id', $mPhoneCondeVerification)->update(['is_used' => 1]); 
                
                $bPasswordUpdated = User::where('id', $oAuth->id)->update([
                    'password' => Hash::make($aInput['new_password'])
                ]);
    
                if($bPasswordUpdated){
        
                    $sMessage = "Your password reset successfully.";
        
                    if(isset($oAuth->email)){
                        dispatch(new SendEmail(Mail::to($oAuth->email)->send(new GeneralAlert("Password Reset Successfully", $sMessage))));  
                    }
                    $oResponse = responseBuilder()->success(__('auth.forget.password_reset_success'));
                    $this->urlComponents(config("businesslogic")[103]['menu'][1], $oResponse, config("businesslogic")[103]['title']);
                    return $oResponse;
                }
            }
        
        return responseBuilder()->error(__('auth.forget.password_fail'), 400, false);

    }

    /**
     * Generate OTP code for user to login 
     */
    public function generateOTPCode(Request $request)
    {

        $aInput = $request->all();

        $oValidator = Validator::make($aInput,[
            'phone' => 'required|digits_between:10,20',
        ]);

        if($oValidator->fails())
            abort(400, $oValidator->errors()->first());


        $sPhoneNumber = formatPhone($aInput['phone']);
        //dd($sPhoneNumber);

        $sLoginSource = isset($aInput['source'])? $aInput['source'] : null;
        $iDoctorId = isset($aInput['doctor_id'])? decrypt($aInput['doctor_id']) : null;

        if($sLoginSource == "BOP"){          // Back Staff OTP
            $oUser = User::where('phone', $sPhoneNumber)->whereNull('doctor_id')->whereNull('patient_id')->first();
        }elseif($sLoginSource == "FDL"){     // Doctor OTP 
            $oUser = User::where('phone', $sPhoneNumber)->whereNull('patient_id')->whereNotNull('doctor_id')->first();
        }elseif($sLoginSource == "FLP"){     // Patient OTP 
            $oUser = User::where('phone', $sPhoneNumber)->where('doctor_id',$iDoctorId)->whereNotNull('patient_id')->first();
        }else{
            $oUser = User::where('phone', $sPhoneNumber)->first();
        }
        
        if(!isset($oUser))
            return responseBuilder()->error(__('auth.otp.not_found'), 400, false);

        if(empty($oUser->id))
            return responseBuilder()->error(__('auth.otp.not_found'), 400, false);

        if($oUser->is_active != 1)
            return responseBuilder()->error(__('auth.otp.not_active'), 400, false);

        if($oUser->phone_verified != 1)
            return responseBuilder()->error(__('auth.otp.not_verified'), 400, false);


        $mPhoneCode = $this->generateVerificationData($oUser->id, 'phone', config('app.otp_expiry'));

        if(!$mPhoneCode)
            return responseBuilder()->error(__('auth.otp.unable'), 400, false);

        $sTxtMsg = "Your OTP Code ".$mPhoneCode;
        
        DB::beginTransaction();

        try {

            $bIsMsgSent = smsGateway($sPhoneNumber, $sTxtMsg, true);

            //move the password to password_store and save OTP code into the password section
            $aUserPwdUpdate = array();
            $aUserPwdUpdate['password_store'] = Hash::make($mPhoneCode);

            $bGenerated = User::where('id', $oUser->id)->update($aUserPwdUpdate);

            if(!$bGenerated || !$bIsMsgSent['SUC'])
                throw new \Exception('Unable to generate code');

            DB::commit();

        }catch(\Exception $e){

            DB::rollback();
            
            $oResponse = responseBuilder()->error($e->getMessage(), 500, false);
            $this->urlComponents(config("businesslogic")[102]['menu'][1], $oResponse, config("businesslogic")[102]['title']);    
            return $oResponse;

        }
        
        $oResponse = responseBuilder()->success(__('auth.otp.success', ['code' => app()->isLocal() ? $mPhoneCode:'']));
        $this->urlRec(102, 0, $oResponse);
        return $oResponse;
    }


    /**
     * Below function will generate SMS token for logged-in user against his phone number
     * to verify by using SMS method
     */
    public function phoneVerifyRequest(Request $request, $sUserIdEnc = '')
    {
        
        if(Auth::check())
            $oUserIdForVerification = auth()->user();
        else{
            $iUserId = decrypt($sUserIdEnc);
            $oUserIdForVerification = User::where('id', $iUserId)->first();            
        }

        $oUser = $oUserIdForVerification;

        if($oUser->phone_verified == 1){ //already verified number

            $oResponse = responseBuilder()->error(__('auth.already_verified_phone'), 401, false);
            $this->urlRec(104, 2, $oResponse);

            return $oResponse;
        }

        $mCode = $this->generateVerificationData($oUser->id, 'phone', 60);
        
        if(!$mCode)
            return responseBuilder()->error(__('auth.forget.unable'), 401, false);
        
        $sTxtMsg = "PHONE VERIFICATION CODE ".$mCode;

        $aIsMsgSent = smsGateway($oUser->phone, $sTxtMsg, true);

        if($aIsMsgSent['SUC']){
            $oResponse = responseBuilder()->success(__('auth.phone_code_generated', ['code' => app()->isLocal() ? $mCode:'']));
            $this->urlRec(104, 1, $oResponse);
            return $oResponse;
        }

        return responseBuilder()->error(__('auth.forget.unable'), 401, false);
    }

    /**
     * Below function will generate EMAIL token for logged-in user against his/her email address to verify 
     *
     */
    public function emailVerifyRequest(Request $request, $sUserIdEnc = '')
    {

        if(Auth::check())
            $oUserIdForVerification = auth()->user();
        else{
            $iUserId = decrypt($sUserIdEnc);
            $oUserIdForVerification = User::where('id', $iUserId)->first();            
        }

        $oUser = $oUserIdForVerification;

        if(!$oUser->hasEmailAddress()){ //no email address found
            $oResponse = responseBuilder()->error(__('auth.email_not_available'), 401, false);
            $this->urlRec(105, 2, $oResponse);
            return $oResponse;
        }

        if($oUser->email_verified == 1){ //already verified

            $oResponse = responseBuilder()->error(__('auth.already_verified_email'), 401, false);
            $this->urlRec(105, 3, $oResponse);
            return $oResponse;
        }

        //compose and send email to give address
        $mCode = $this->generateVerificationData($oUser->id, 'email', 60);

        $sEmailAddress = $oUser->email;

        $sLink = config('app.email_verification_url')."/".encrypt($sEmailAddress)."/".encrypt($mCode);
        $aButtonHtml = '<a href="'.$sLink.'" class="button button-blue" target="Naymv4BMlrMoQq8Y6jJJSeK" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #3097D1; border-top: 10px solid #3097D1; border-right: 18px solid #3097D1; border-bottom: 10px solid #3097D1; border-left: 18px solid #3097D1" rel="noopener noreferrer">VERIFY MY EMAIL</a>';


        $sMessage  = "Please verify your email address by using link below.<br><br>";
        $sMessage .= $aButtonHtml."<br><br>";
        $sMessage .= "In case above button not working, please open below mentioned link.<br>".$sLink;
        $sMessage .= "<br><br>Kindly ignore this email, if you have not registered/using this email.";

        dispatch(new SendEmail(Mail::to($sEmailAddress)->send(new GeneralAlert("Verify Email Address", $sMessage))));

        $oResponse = responseBuilder()->success(__('auth.email_code_generated'));
        $this->urlRec(105, 1, $oResponse);
        return $oResponse;
    }

    /**
     * As user us not logged in at time of signup, so encrypted user id will we forward to frontend so that 
     * front end return it with user phone verification code
     * and we'll match it from DB at verification utility, if user found logged in then identifier param
     * will be discarded
     */
    public function doVerifyPhone(Request $request, $identifier = '')
    {
       
        $aInput = $request->all();

        $oValidator = Validator::make($aInput,[
            'code' => 'required|digits_between:6,10',
        ]);

        if($oValidator->fails())
            abort(400, $oValidator->errors()->first());

        if(Auth::check())
            $iUserIdForVerification = auth()->user()->id;
        else
            $iUserIdForVerification = decrypt($identifier);  

        //dump($iUserIdForVerification);
        $mPhoneCondeVerification = $this->isPhoneCodeValid($iUserIdForVerification, $aInput['code']);

        if(!$mPhoneCondeVerification)
            return responseBuilder()->error(__('auth.otp.expired_code'), 401, false);

        VerificationCode::where('id', $mPhoneCondeVerification)->update(['is_used' => 1]); 
        LOG::info("USER_ID: ".$iUserIdForVerification);
        User::where('id', $iUserIdForVerification)->update(['phone_verified' => 1]);


        //check if empty password then set this as password for first time login as well
        $oCurrentUser = User::where('id', $iUserIdForVerification)->first();
        //dd($oCurrentUser);
        LOG::info("USER: ".print_r($oCurrentUser, true));

        if(empty($oCurrentUser->password))
            User::where('id', $iUserIdForVerification)->update(['password' => Hash::make($aInput['code'])]);

        $oResponse = responseBuilder()->success(__('auth.success_verified_phone'));
        $this->urlRec(104, 3, $oResponse);
        return $oResponse;
    }


    /**
     * Email verification link clicked and now system verifying the links with identifier and code passed
     */
    public function doVerifyEmail(Request $request, $identifier, $code)
    {
        $sIdentifier = decrypt($identifier);
        $iCode = decrypt($code);


        if(Auth::check()){ //if user is already loggedin

            $oUser = auth()->user();
            
            if(!$oUser->hasEmailAddress()){ //no email address found
                return responseBuilder()->error(__('auth.email_not_available'), 400, false);
            }

            $oVerify = VerificationCode::where('user_id', auth()->user()->id)
                        ->where('type', 'email')
                        ->where('code', $iCode)
                        ->first();            


        }else{ //if user NOT logged in

            $oVerify = VerificationCode::with(['email' => function($oQuery) use($sIdentifier) {
                            $oQuery->where('email', $sIdentifier);
                        }])
                        ->where('type', 'email')
                        ->where('code', $iCode)
                        ->first();
        }

        //dump($oVerify);
        
        if(empty($oVerify->id))
            return responseBuilder()->error(__('auth.forget.invalid_code'), 400, false);

        $oExpiryDate = Carbon::parse($oVerify->expiry_timestamp);

        //already used code to remember
        if($oVerify->is_used == 1)
            return responseBuilder()->error(__('auth.forget.used_code'), 400, false);            

        if($oExpiryDate->lt(Carbon::now()))
            return responseBuilder()->error(__('auth.forget.expired_code'), 400, false);            


        VerificationCode::where('id', $oVerify->id)->update(['is_used' => 1]);
        
        if(Auth::check())
            User::where('id', auth()->user()->id)->update(['email_verified' => 1]);
        else
            User::where('id', $oVerify->email->id)->update(['email_verified' => 1]);

        //generate email for new password
        $sMessage = "Thank you. Your email address verified successfully.";
        dispatch(new SendEmail(Mail::to($sIdentifier)->send(new GeneralAlert("Email Verified Successfully", $sMessage))));  

        $oResponse = responseBuilder()->success(__('auth.success_verified_email'));
        $this->urlRec(105, 4, $oResponse);
        return $oResponse;

    }

    
    /**
     * Is Already user / patient of doctor
     */
    public function isAlreadyUser(Request $request, $doctor_id)
    {

        $iDoctorId = decrypt($doctor_id);
      
        $aData = $request->all();
        
        $sUserName = $aData['username'];

    	$oUser = User::where('doctor_id', $iDoctorId)->where('username', $sUserName)->first();

        $aResponseReturn = array();
        $aResponseReturn['FOUND'] = 0;
        $aResponseReturn['VERIFY'] = 0;

        // dd($oUser);
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

                if(app()->isLocal())
                    $aResponseReturn['CODE'] = $iPhoneVerifyCode;

            }

            
        }

        $oResponse = responseBuilder()->success('', $aResponseReturn);
        return $oResponse;
    }

}
