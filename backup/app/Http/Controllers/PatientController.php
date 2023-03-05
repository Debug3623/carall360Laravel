<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Chat;
use App\Http\Controllers\Controller;

use App\Patient;
use App\Doctor;
use App\User;
use App\VerificationCode;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Mail;
use Illuminate\Support\Facades\Gate;
use App\Mail\GeneralAlert;
use App\Jobs\SendEmail;
use App\Helpers\FileHelper;
use App\Helpers\QB;
use Intervention\Image\Facades\Image;
use App\Media;
use App\MedicalRecord;
use App\Review;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{

    use \App\Traits\WebServicesDoc;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('patient-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oAuth = Auth::user();
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['id'] = isset($oInput['id'])?decrypt($oInput['id']):null;
        
        $oQb = Patient::orderByDesc('updated_at')->with(['user','createdBy','updatedBy','restoredBy','countryCode','cityId']);

        $oQb = QB::where($oInput,"id",$oQb);
        
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;
            
            $oQb = $oQb->whereHas('doctor', function ($q) use($oInput) {
                $q->where('organization_id', $oInput['organization_id']);
            });
            
        }elseif ($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;    
        }
        if(isset($oInput['blood_group'])){
            $oInput['blood_group'] = ($oInput['blood_group'] === "o") ? "o+" : $oInput['blood_group'];
            $oInput['blood_group'] = ($oInput['blood_group'] === "ab") ? "ab+" : $oInput['blood_group'];
            $oInput['blood_group'] = ($oInput['blood_group'] === "b") ? "b+" : $oInput['blood_group'];
            $oInput['blood_group'] = ($oInput['blood_group'] === "a") ? "a+" : $oInput['blood_group'];
        }
        if(isset($oInput['doctor_name'])){
            $oValidator = Validator::make($oInput,[
                'doctor_name'       => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('doctor', function ($q) use($oInput) {
                $q->where("full_name", 'like', '%'.$oInput['doctor_name'].'%');
            });
        }
        if(isset($oInput['country_name'])){
            $oValidator = Validator::make($oInput,[
                'country_name' => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('countryCode', function ($q) use($oInput) {
                $q->where("name", 'like', '%'.$oInput['country_name'].'%');
            });
        }
        if(isset($oInput['city_name'])){
            $oValidator = Validator::make($oInput,[
                'city_name' => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('cityId', function ($q) use($oInput) {
                $q->where("name", 'like', '%'.$oInput['city_name'].'%');
            });
        }
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"ref_number",$oQb);
        $oQb = QB::whereLike($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"cnic",$oQb);
        $oQb = QB::whereLike($oInput,"address",$oQb);
        $oQb = QB::whereLike($oInput,"phone",$oQb);
        $oQb = QB::where($oInput,"gender",$oQb);
        $oQb = QB::where($oInput,"marital_status",$oQb);
        $oQb = QB::whereLike($oInput,"blood_group",$oQb);
        $oQb = QB::where($oInput,"city_id",$oQb);
        $oQb = QB::where($oInput,"country_code",$oQb);
        
        $oPatients = $oQb->paginate(10);
        $oPatients = userImage($oPatients);
        if(count($oPatients)>0){
            foreach ($oPatients as $patient) {
                $patient['doctor'] = Doctor::where('id',$patient->doctor_id)->select('id','full_name','title','email','phone')->first();
            }
        }

        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Patients"]), $oPatients, false);
        $this->urlComponents(config("businesslogic")[23]['menu'][0], $oResponse, config("businesslogic")[23]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if (!Gate::allows('patient-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oInput['phone'] = formatPhone($oInput['phone']);
        $oValidator = Validator::make($oInput,[
            'name'        => 'required|string|max:50|min:3',
            'gender'      => 'required|in:male,female,transgender',
            'marital_status'=> 'required|in:married,unmarried',
            'blood_group' => 'required|in:a+,a-,b+,b-,ab+,ab-,o+,o-',
            'phone'       => 'required|digits_between:10,20',
            'email'       => 'sometimes|max:50',
            'address'     => 'present|nullable|max:250|string',
            'remarks'     => 'present|nullable|max:250',
            'city_id'     => 'present|nullable|exists:cities,id',
            'country_code'=> 'present|nullable|exists:countries,code',
            'cnic'        => 'present|nullable|max:13|min:13',
            'dob'         => 'present|nullable|date',
            'height'      => 'present|nullable',
            'weight'      => 'present|nullable',
            'doctor_id'   => 'required|exists:doctors,id',
            // 'image' 	  => 'nullable|file|mimes:jpeg,jpg,png',
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
            'phone' => 'required|digits_between:11,16|unique:patients,phone,null,null,doctor_id,'.$oInput['doctor_id'],
        ]);
       

        $oDoctor = Doctor::where('id', $oInput['doctor_id'])->first();

        if($oDoctor->phone == $oInput['phone']){
            $oResponse = responseBuilder()->error(__('auth.doctor_phone_used'), 403, false);
            return $oResponse; 
        }


        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }       
        $oCheckPaientAlready = Patient::where('phone',$oInput['phone'])->where('doctor_id',$oInput['doctor_id'])->first();
        if($oCheckPaientAlready){
            return responseBuilder()->error(__('Patient Is Already Created'), 403, false);
        }
        if(isset($oInput['cnic'])){
            $oCheckCnicAlready = Patient::where('cnic',$oInput['cnic'])->where('doctor_id',$oInput['doctor_id'])->first();
            if($oCheckCnicAlready){
                return responseBuilder()->error(__('Patient Is Already Created'), 403, false);
            }
        }
    
        $sPassword = rand(111111,999999);

        $oPatient = Patient::create([
            'doctor_id'     => $oInput['doctor_id'],
            'name'          => strtoupper($oInput['name']),
            'email'         => $oInput['email'],
            'phone'         => $oInput['phone'],
            'cnic'          => $oInput['cnic'],
            'ref_number'    => strtoupper(uniqid()),
            'country_code'  => isset($oInput['country_code'])? $oInput['country_code']:'PAK', //dafault for every patient
            'city_id'       => $oInput['city_id'], 
            'gender'        => $oInput['gender'], 
            'address'       => $oInput['address'], 
            'dob'           => $oInput['dob'], 
            'marital_status'=> $oInput['marital_status'], 
            'blood_group'   => $oInput['blood_group'], 
            'remarks'       => $oInput['remarks'], 
            'height'        => $oInput['height'], 
            'weight'        => $oInput['weight'], 
            'created_by'    =>  Auth::user()->id,
            'updated_by'    =>  Auth::user()->id,
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $oUser = User::create([
            'name'              =>  $oInput['name'],
            'phone'             =>  $oInput['phone'],
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
        $url = config("app")['FRONTEND_URL'].'doctor/'.$oPatient->doctor->url;
        $message = "Welcome to CliniCALL. $doctorName has added you as a patient. Your login details are:".$n.$n."Link: $url".$n."Username: $oPatient->phone".$n."Password: $sPassword";
        $aSmsSent = smsGateway($oPatient->phone, $message, true);
        if($oPatient->email){
            $message = str_replace('\n', '<br>', $message);
            dispatch(new SendEmail(Mail::to($oInput['email'])->send(new GeneralAlert("Welcome to the CliniCall", $message))));
        }
        $oResponse = responseBuilder()->success(__('message.general.created',
            ["mod"=>"Patient", 'code' => app()->isLocal() ? $sPassword:'']), $oPatient, false);
        
        $this->urlComponents(config("businesslogic")[23]['menu'][1], $oResponse, config("businesslogic")[23]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = decrypt($id);

        $oAuth = Auth::user();
        
        $oQb = Patient::with(['user','doctor','createdBy','updatedBy','restoredBy','deletedBy','cityId','countryCode']);
        
        if($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;
            $oQb      = QB::where($oInput,"doctor_id",$oQb);
            $oPatient = $oQb->findOrFail($id);
        
        }elseif($oAuth->isPatient()) {
            $oPatient = $oQb->findOrFail($oAuth->patient_id);
        }else{
            $oPatient = $oQb->findOrFail($id);
        }
        
        
        $oAuth = Auth::user();

        if($oAuth->isClient()){
            $organization_id = $oAuth->organization_id;
            $oOrganizationPatient = Patient::where('id',$id)->whereHas('doctor', function ($q) use($organization_id) {
                                                $q->where('organization_id', $organization_id);
                                            })->first();
            if(!$oOrganizationPatient){
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            }
        }elseif ($oAuth->isDoctor()) {
            $doctor_id = $oAuth->doctor_id;
            $oDoctorPatient = Patient::where('id',$id)->where('doctor_id',$doctor_id)->first();
            if(!$oDoctorPatient){
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            }
        }
        $oUser = $oPatient->user;
        if($oUser){
            $oPatient['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
        }else{
            $oPatient['image'] = null;
        }
        
        if (!Gate::allows('patient-show',$oPatient)&& !Gate::allows('patient-profile-view',$oPatient))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Patient"]), $oPatient, false);
        
        $this->urlComponents(config("businesslogic")[23]['menu'][2], $oResponse, config("businesslogic")[23]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'name'        => 'required|string|max:50|min:3',
            'gender'      => 'required|in:male,female,transgender',
            'email'       => 'sometimes|max:50',
            'marital_status'=> 'required|in:married,unmarried',
            'blood_group' => 'required|in:a+,a-,b+,b-,ab+,ab-,o+,o-',
            'address'     => 'present|nullable|max:250|string',
            'remarks'     => 'present|nullable|max:250',
            'city_id'     => 'present|nullable|exists:cities,id',
            'country_code'=> 'present|nullable|exists:countries,code',
            'dob'         => 'present|nullable|date',
            'doctor_id'   => 'required|exists:doctors,id',
            'height'      => 'present|nullable',
            'weight'      => 'present|nullable',
            'cnic'        => 'present|nullable|max:13|min:13',
            // 'image' 	  => 'nullable|file|mimes:jpeg,jpg,png',
        ]);
        // $oValidator = Validator::make($oInput,[
        //     'email' => 'nullable|email|max:50|unique:patients,email,null,null,doctor_id,'.$oInput['doctor_id'],
        // ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        if(isset($request->image)){
            $imageExtension = $request->image->extension();
            if($imageExtension != "png" && $imageExtension != "jpg" && $imageExtension != "jpeg"){
                abort(400,"The image must be a file of type: jpeg, jpg, png.");
            }
        }
        if(isset($oInput['cnic'])){
            $oCheckPaientCnicAlready = Patient::where('id','!=',$id)->where('cnic',$oInput['cnic'])->where('doctor_id',$oInput['doctor_id'])->first();
            if($oCheckPaientCnicAlready){
                return responseBuilder()->error(__('Patient Cnic Already Taken'), 403, false);
            }
        }
        $oPatient = Patient::findOrFail($id);
       
        $oUser   = User::where('patient_id',$id)->first();
    
        if (!Gate::allows('patient-update',$oPatient) && !Gate::allows('patient-profile-update',$oPatient))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oPatient = $oPatient->update([
            'doctor_id'     => $oInput['doctor_id'],
            'name'          => strtoupper($oInput['name']),
            'country_code'  => isset($oInput['country_code'])? $oInput['country_code']:'PAK', //dafault for every patient
            'city_id'       => $oInput['city_id'], 
            'email'         => isset($oInput['email'])? $oInput['email']:$oPatient->email, 
            'gender'        => $oInput['gender'], 
            'address'       => $oInput['address'], 
            'dob'           => $oInput['dob'], 
            'cnic'          => $oInput['cnic'], 
            'marital_status'=> $oInput['marital_status'], 
            'blood_group'   => $oInput['blood_group'], 
            'remarks'       => $oInput['remarks'], 
            'height'        => $oInput['height'], 
            'weight'        => $oInput['weight'], 
            'updated_by'    =>  Auth::user()->id,
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        
        if($oUser){

            if(isset($request->image)){
            
                $oMedia = Media::where('user_id',$oUser->id)->where('type','profile')->first();
                
                if($oMedia){
                    FileHelper::deleteImages($oMedia);
                    $oMedia->forceDelete();
                }
               
                $oPaths = FileHelper::saveImages($request->image,'patients');
                
                $media = Media::create([
                    'user_id'   => $oUser->id,
                    'url'       => $oPaths['url'],
                    'alt_tag'   => $oInput['name'],
                    'type'      => 'profile',
                    'updated_by'=> Auth::user()->id,
                    'updated_at'=> Carbon::now()->toDateTimeString(),
                ]); 
            }
            $oUser = $oUser->update([
                'name'              =>  $oInput['name'],
                'doctor_id'         =>  $oInput['doctor_id'],
                'email'             =>  isset($oInput['email'])? $oInput['email']:$oUser->email,
                'updated_by'        =>  Auth::user()->id,
                'updated_at'        =>  Carbon::now()->toDateTimeString(),
            ]);
        }
        
        $oPatient = Patient::with(['user','doctor','createdBy','updatedBy','restoredBy','deletedBy','cityId','countryCode'])->findOrFail($id);
        
        $oUser = $oPatient->user;
        if($oUser){
            $oPatient['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
        }else{
            $oPatient['image'] = null;
        }
       
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Patient"]), $oPatient, false);
        
        $this->urlComponents(config("businesslogic")[23]['menu'][3], $oResponse, config("businesslogic")[23]['title']);
        
        return $oResponse;
    }

    // Soft Delete Patients 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oInput = DecryptId($oInput);
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:patients,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $oInput['ids'];
       
        $allPatients = Patient::findOrFail($aIds);
        
        foreach($allPatients as $oRow)
            if (!Gate::allows('patient-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
    
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oPatient = Patient::find($id);
                $oUser = User::where('patient_id',$id)->first();
                if($oPatient){
                    Appointment::where('patient_id',$id)->delete();
                    MedicalRecord::where('patient_id',$id)->delete();
                    Review::where('patient_id',$id)->delete();

                    $oPatient->update(['deleted_by' => Auth::user()->id]);
                    $oPatient->delete();
                }
                if($oUser){
                    Chat::where('patient_user_id',$oUser->id)->delete();
                    $oUser->update(['deleted_by' => Auth::user()->id]);
                    $oUser->delete();
                }
            }
        }else{
            $oPatient = Patient::findOrFail($aIds);
        
            $oPatient->update(['deleted_by' => Auth::user()->id]);
            $oPatient->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Patient"]));
        $this->urlComponents(config("businesslogic")[23]['menu'][4], $oResponse, config("businesslogic")[23]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request)
    {
        if (!Gate::allows('patient-deleted')&& !Gate::allows('deleted-patient'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['id'] = isset($oInput['id'])?decrypt($oInput['id']):null;
        $oQb = Patient::onlyTrashed()->orderBYDesc('deleted_at')->with(['user','createdBy','updatedBy','restoredBy','deletedBy','cityId','countryCode']);
         
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"cnic",$oQb);
        $oQb = QB::whereLike($oInput,"ref_number",$oQb);
        $oQb = QB::whereLike($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"address",$oQb);
        $oQb = QB::whereLike($oInput,"phone",$oQb);
        $oQb = QB::whereLike($oInput,"gender",$oQb);
        $oQb = QB::whereLike($oInput,"marital_status",$oQb);
        $oQb = QB::whereLike($oInput,"blood_group",$oQb);
        $oQb = QB::whereLike($oInput,"city_id",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        
        $oPatients = $oQb->paginate(10);
        $oPatients = userImage($oPatients);
        if(count($oPatients)>0){
            foreach ($oPatients as $patient) {
                $patient['doctor'] = Doctor::where('id',$patient->doctor_id)->select('id','full_name','title','email','phone')->first();

            }
        }
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Patient"]), $oPatients, false);
        
        $this->urlComponents(config("businesslogic")[23]['menu'][5], $oResponse, config("businesslogic")[23]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {
        $oInput = $request->all();
        $oInput = DecryptId($oInput);
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:patients,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $oInput['ids'];
        
        $allPatient = Patient::onlyTrashed()->findOrFail($aIds);
        
        foreach($allPatient as $oRow)
            if (!Gate::allows('patient-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oPatient = Patient::onlyTrashed()->find($id);
                $oUser = User::onlyTrashed()->where('patient_id',$id)->first();
                if($oPatient){ 
                    $deleted_at = $oPatient->deleted_at;
                    $oPatient->update([
                        'restored_by' => Auth::user()->id,
                        'restored_at' => Carbon::now()->toDateTimeString(),                  
                        ]);
                    $oPatient->restore();
                    
                    Appointment::where('deleted_at',$deleted_at)->where('patient_id',$id)->restore();
                    MedicalRecord::where('deleted_at',$deleted_at)->where('patient_id',$id)->restore();
                }
                if($oUser){
                    Chat::where('patient_user_id',$oUser->id)->restore();
                    $oUser->update([
                        'restored_by' => Auth::user()->id,
                        'restored_at' => Carbon::now()->toDateTimeString(),                  
                        ]);
                    $oUser->restore();
                    
                }
            }
        }else{
            $oPatient = Patient::onlyTrashed()->findOrFail($aIds);
            $oPatient->update([
                'restored_by' => Auth::user()->id,
                'restored_at' => Carbon::now()->toDateTimeString(),                  
                ]);
            $oPatient->restore();
        }
        
        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Patient"]));
        
        $this->urlComponents(config("businesslogic")[23]['menu'][6], $oResponse, config("businesslogic")[23]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $id = decrypt($id);
        $oPatient = Patient::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('patient-delete',$oPatient))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oPatient->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Patient"]));
        
        $this->urlComponents(config("businesslogic")[23]['menu'][7], $oResponse, config("businesslogic")[23]['title']);
        
        return $oResponse;
    }

    public function doctorPatient(Request $request)
    {
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        
        $oQb = Patient::orderByDesc('updated_at')->with(['user']);

        if(!isset($oInput['doctor_id'])){
            if($oAuth->isClient()){
                $oInput['organization_id'] = $oAuth->organization_id;
                
                $oQb = $oQb->whereHas('doctor', function ($q) use($oInput) {
                    $q->where('organization_id', $oInput['organization_id']);
                });
                
            }elseif ($oAuth->isDoctor()) {
                $oInput['doctor_id'] = $oAuth->doctor_id;    
            }
        } 
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oPatients = $oQb->get();
        if(count($oPatients)>0){
            foreach ($oPatients as $patient) {
                $oUser = $patient->user;
                if($oUser){
                    $patient['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
                    $patient['uid']   = $oUser->id;
                }
            }
        }
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Doctor Patients"]), $oPatients, false);
        $this->urlComponents(config("businesslogic")[23]['menu'][8], $oResponse, config("businesslogic")[23]['title']);
        
        return $oResponse;
    }

    /**
     * Signup routine for new partient self registration at doctor portal
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request, $doctor_id)
    {
        
        $iDoctorId = decrypt($doctor_id);

        $aData = $request->all();

        if(empty($aData['phone']))
            return;

        $sPhoneNumber = formatPhone($aData['phone']);

        $iIsAlreadyPatient = Patient::with(['user' => function($oQry){
                                            $oQry->where('phone_verified', 0);
                                        }])
                                        ->where('email', $aData['email'])
                                        ->where('phone', $aData['phone'])
                                        ->where('doctor_id', $iDoctorId)
                                        ->first();

        //dd($iIsAlreadyPatient->pk ." ~~ ".$iIsAlreadyPatient->user->id);

        if(!empty($iIsAlreadyPatient->pk) && !empty($iIsAlreadyPatient->user->id)){

            //dd("returning");
            //mean patient already enrolled but not verified mobile number,
            // so here we have only to sent success code with regenration of sms verification code
            $iPhoneVerifyCode = rand(100000,999999);
            $oVeriCodePhone = VerificationCode::create([
                'user_id'           => $iIsAlreadyPatient->user->id,
                'code'              => $iPhoneVerifyCode,
                'type'              => 'phone',
                'expiry_timestamp'  => Carbon::now()->addMinutes(60), //1 hr expiry time
            ]);

            $aSmsSent = smsGateway($iIsAlreadyPatient->user->phone, "CODE ".$iPhoneVerifyCode, true);

            $oPatientRelation = Patient::with('user')->findOrFail(decrypt($iIsAlreadyPatient->id))->toArray();
            $oNewCreatedResource = array();

            //as user us not logged in at time of signup, so we'll encrypt the user id returned by user table
            //this will we forward to frontend so that front end return it with user phone verification code
            //and we'll match it from DB at verification utility, if user found logged in then identifier param
            //will be discarded
            //dd($oNewCreatedResource);
            $sEncryptedUserId = $oPatientRelation['user']['id'];
            $oNewCreatedResource['identifier'] = encrypt($sEncryptedUserId);
            

            //dd($oNewCreatedResource);
            $oResponse = responseBuilder()->success(__('message.patient.signup-already', ['code' => app()->isLocal() ? $iPhoneVerifyCode:'']), $oNewCreatedResource, false);
            
            $this->urlRec(201, 3, $oResponse);
            
            return $oResponse;
        }
        

        //new signup starts from here

        $aValidationRules = array(
            'name'          => 'required|string|max:50',
            'email'         => 'nullable|email|max:50|unique:patients,email,null,null,doctor_id,'.$iDoctorId,
            'phone'         => 'required|digits_between:11,16|unique:patients,phone,null,null,doctor_id,'.$iDoctorId, 
        );

        $oValidation = Validator::make($aData, $aValidationRules);

        if($oValidation->fails())
            abort(400, $oValidation->errors()->first());

        /*
        if($oValidation->fails()){
            $aValidationErrors = $oValidation->errors()->toArray();

            $oResponse = responseBuilder()->error(__('message.general.validation_fail'), 200, false, $aValidationErrors);
            $this->urlRec(201, 2, $oResponse);

            return $oResponse;
        }
        */


        //is requested doctor is valid and active in system
        $iDoctorCnt = Doctor::where('id', $iDoctorId)
                            ->where('is_active', 1)
                            ->whereNull('deleted_at')
                            ->first();

        //if doctor is not active or deleted from system
        if(empty($iDoctorCnt->id)){

            $oResponse = responseBuilder()->error(__('auth.doctor_inactive'), 403, false);
            $this->urlRec(3, 0, $oResponse);

            return $oResponse;
        }

        if($iDoctorCnt->phone == $sPhoneNumber){
            $oResponse = responseBuilder()->error(__('auth.doctor_phone_used'), 403, false);
            //$this->urlRec(3, 0, $oResponse);

            return $oResponse;    
        }

        //dd($iDoctorCnt->phone." == ".$sPhoneNumber);



        DB::beginTransaction();

        $bEmailProvided = false;
        if(!empty($aData['email']))
            $bEmailProvided = true;


        try {


            $sPassword = uniqid();
            $iPhoneVerifyCode = rand(100000,999999);


            $oPatient = Patient::create([

                'doctor_id'     => $iDoctorId,
                'name'          => strtoupper($aData['name']),
                'email'         => $bEmailProvided ? $aData['email'] : null,
                'phone'         => $sPhoneNumber,
                'ref_number'    => strtoupper(uniqid()),
                'country_code'  => 'PAK', //dafault for every patient
                'created_by'    => null,
                'updated_by'    => null,
            ]);


            $oUser = User::create([
                'name'              => strtoupper($aData['name']), 
                'email'             => $bEmailProvided ? $aData['email'] : null,
                'phone'             => $sPhoneNumber,
                'username'          => $sPhoneNumber,
                'password'          => isset($aData['password'])? Hash::make($aData['password']):'', //by default empty password                
                'doctor_id'         => $iDoctorId,
                'patient_id'        => decrypt($oPatient->id),
                'organization_id'   => null,
                'created_by'        => null,
                'updated_by'        => null,
            ]);

            //dd($oUser);

            //patient role assignment
            $oRole = Role::where('name', 'Patient')->first();
            $oUser->roles()->sync($oRole->id);

            $oVeriCodePhone = VerificationCode::create([
                'user_id'           => $oUser->id,
                'code'              => $iPhoneVerifyCode,
                'type'              => 'phone',
                'expiry_timestamp'  => Carbon::now()->addMinutes(60), //1 hr expiry time
            ]);

            $aSmsSent = smsGateway($sPhoneNumber, "CODE ".$iPhoneVerifyCode, true);

            
            if($bEmailProvided){
                $iEmailVerifyCode = rand(100000,999999);

                $oVeriCodePhone = VerificationCode::create([
                    'user_id'           => $oUser->id,
                    'code'              => $iEmailVerifyCode,
                    'type'              => 'email',
                    'expiry_timestamp'  => Carbon::now()->addMinutes(60), //1 hr expiry time
                ]);

                $sLink = config('app.email_verification_url')."/".encrypt($aData['email'])."/".encrypt($iEmailVerifyCode);
                $aButtonHtml = '<a href="'.$sLink.'" class="button button-blue" target="Naymv4BMlrMoQq8Y6jJJSeK" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #3097D1; border-top: 10px solid #3097D1; border-right: 18px solid #3097D1; border-bottom: 10px solid #3097D1; border-left: 18px solid #3097D1" rel="noopener noreferrer">VERIFY MY EMAIL</a>';


                $sMessage  = "Please verify your email address by using link below.<br><br>";
                $sMessage .= $aButtonHtml."<br><br>";
                $sMessage .= "In case above button not working, please open below mentioned link.<br>".$sLink;
                $sMessage .= "<br><br>Kindly ignore this email, if you have not registered/using this email.";

                dispatch(new SendEmail(Mail::to($aData['email'])->send(new GeneralAlert("Verify Email Address", $sMessage))));

            }


            DB::commit();

            $oPatientRelation = Patient::with('user')->findOrFail(decrypt($oPatient->id))->toArray();

            $oNewCreatedResource = array();
            //dd($oNewCreatedResource);

            
            //as user us not logged in at time of signup, so we'll encrypt the user id returned by user table
            //this will we forward to frontend so that front end return it with user phone verification code
            //and we'll match it from DB at verification utility, if user found logged in then identifier param
            //will be discarded
            $sEncryptedUserId = $oPatientRelation['user']['id'];
            $oNewCreatedResource['identifier'] = encrypt($sEncryptedUserId);

            $oResponse = responseBuilder()->success(__('message.patient.signup-success', ['code' => app()->isLocal() ? $iPhoneVerifyCode:'']), $oNewCreatedResource, false);
            
            $this->urlRec(3, 0, $oResponse);
            
            return $oResponse;


        }catch(\Exception $e){

            DB::rollback();
            //dd($e);
            $oResponse = responseBuilder()->error(__('message.general.exception'), 200, false);
            
            $this->urlRec(201, 1, $oResponse);
            return $oResponse;
        }

    }

}
