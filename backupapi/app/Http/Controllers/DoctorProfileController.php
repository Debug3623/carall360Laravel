<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Center;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\DoctorAssistant;
use App\DoctorAward;
use App\DoctorCertification;
use App\DoctorExperience;
use App\DoctorQualification;
use App\DoctorSchedule;
use App\DoctorTreatment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\Helpers\FileHelper;
use App\Jobs\SendEmail;
use Mail;
use App\Mail\GeneralAlert;
use App\Media;
use App\Partnership;
use App\Patient;
use App\PaymentSummary;
use App\WebSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
class DoctorProfileController extends Controller
{
    use \App\Traits\WebServicesDoc;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        if (!Gate::allows('doctor-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oAuth = Auth::user();
        $oInput['id'] = isset($oInput['id'])?decrypt($oInput['id']):null;
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;
        }elseif ($oAuth->isDoctor()) {
            $oInput['id'] = $oAuth->doctor_id;
        }

        $oQb = Doctor::orderByDesc('updated_at')->with(['user','createdBy','updatedBy','restoredBy','organization']);
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::where($oInput,"organization_id",$oQb);
        $oQb = QB::whereLike($oInput,"full_name",$oQb);
        $oQb = QB::where($oInput,"gender",$oQb);
        $oQb = QB::whereLike($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"pmdc",$oQb);
        $oQb = QB::whereLike($oInput,"phone",$oQb);
        $oQb = QB::whereLike($oInput,"speciality",$oQb);
        
        $oDoctors = $oQb->paginate(10);
        $oDoctors = userImage($oDoctors);

        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Doctors"]), $oDoctors, false);
        $this->urlComponents(config("businesslogic")[6]['menu'][0], $oResponse, config("businesslogic")[6]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('doctor-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['url'] = isset($oInput['url'])? strtolower(str_replace(' ', '-', $oInput['url'])) : '';
        $oValidator = Validator::make($oInput,[
            'full_name'   => 'required|string|max:50|min:3',
            'gender'      => 'required|in:male,female,transgender',
            'title'       => 'required|in:Dr,Prof,Asst',
            // 'pmdc'        => 'required|max:20|unique:doctors,pmdc',
            'url'         => 'required|max:250|string|unique:doctors,url',
            'phone'       => 'required|digits_between:10,20|unique:doctors,phone|unique:users,phone',
            'email'       => 'present|required|email|unique:doctors,email|unique:users,email|max:50',
            'speciality'  => 'required|max:250|string',
            'about'       => 'present|nullable|string',
            'is_active'   => 'required|in:0,1',
            'dob'         => 'present|nullable|date',
            'practice_start_year'=> 'present|nullable|date',
            'organization_id'=> 'required|exists:organizations,id',
            // 'image' 	  => 'nullable|file|mimes:jpeg,jpg,png',
            // 'banner' 	  => 'nullable|file|mimes:jpeg,jpg,png',
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
        
        if(isset($request->banner)){
            $bannerExtension = $request->banner->extension();
            if($bannerExtension != "png" && $bannerExtension != "jpg" && $bannerExtension != "jpeg"){
                abort(400,"The banner must be a file of type: jpeg, jpg, png.");
            }
        }
        if(isset($oInput['about'])){
            if($oInput['about'] == "null"){
                $oInput['about'] = '';
            }
        }
        
        
         $oCheckPaientAlready = Doctor::where('pmdc',$oInput['pmdc'])->first();
        if($oCheckPaientAlready && $oInput['pmdc'] !=="0001"){
            return responseBuilder()->error(__('Pmdc is already exist'), 403, false);
        }
        
        $sPassword = rand(111111,999999);
        $oDoctor = Doctor::create([
            'full_name'     =>  $oInput['full_name'],
            'gender'        =>  $oInput['gender'],
            'title'         =>  $oInput['title'],
            'pmdc'          =>  $oInput['pmdc'],
            'url'           =>  $oInput['url'],
            'phone'         =>  $oInput['phone'],
            'email'         =>  $oInput['email'],
            'speciality'    =>  $oInput['speciality'],
            'about'         =>  $oInput['about'],
            'dob'           => $oInput['dob'], 
            'is_active'     =>  $oInput['is_active'],
            'practice_start_year'=>  $oInput['practice_start_year'],
            'organization_id'=>  $oInput['organization_id'],
            'created_by'    =>  Auth::user()->id,
            'updated_by'    =>  Auth::user()->id,
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $oUser = User::create([
            'name'          =>  $oInput['full_name'],
            'phone'         =>  $oInput['phone'],
            'email'         =>  $oInput['email'],
            'username'      =>  $oInput['phone'],
            'organization_id'=> $oInput['organization_id'],
            'phone_verified'=>  1,
            'doctor_id'     =>  decrypt($oDoctor->id),
            'password'      =>  Hash::make($sPassword),
            'remember_token'=>  Str::random(50),
            'created_by'    =>  Auth::user()->id,
            'updated_by'    =>  Auth::user()->id,
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $image = null;
        if(isset($request->image)){
            
            $oPaths = FileHelper::saveImages($request->image,'doctors');

            $media = Media::create([
                'user_id'   => $oUser->id,
                'url'       => $oPaths['url'],
                'alt_tag'   => $oInput['full_name'],
                'type'      => "profile",
                'created_by'=> Auth::user()->id,
                'updated_by'=> Auth::user()->id,
                'created_at'=> Carbon::now()->toDateTimeString(),
                'updated_at'=> Carbon::now()->toDateTimeString(),
            ]);
            $image = $media->url;;
        }
        $banner = null;
        if(isset($request->banner)){
            
            $oBannerPaths = FileHelper::saveImages($request->banner,'banners');

            $banner = Media::create([
                'user_id'   => $oUser->id,
                'url'       => $oBannerPaths['url'],
                'alt_tag'   => $oInput['full_name'],
                'type'      => "banner",
                'created_by'=> Auth::user()->id,
                'updated_by'=> Auth::user()->id,
                'created_at'=> Carbon::now()->toDateTimeString(),
                'updated_at'=> Carbon::now()->toDateTimeString(),
            ]);
            $banner = $banner->url;;
        }
        $role_name ="Doctor";

        $oRole = Role::where('name',$role_name)->first();
        $oUser->roles()->sync($oRole->id);

        $oDoctor= Doctor::with(['user','doctorAssistant','doctorAward','doctorCertification','doctorExperience','doctorQualification','createdBy','updatedBy','restoredBy','deletedBy','organization'])->findOrFail(decrypt($oDoctor->id));
       
        $n = '\n';
        $url = config("app")['FRONTEND_URL'].'login';
        $landingPage = config("app")['FRONTEND_URL'].'doctor/'.$oDoctor->url;
        
        $message = "Congratulations on becoming a CareAll360 partner. Your login details are:".$n.$n."Link: ".$url. $n.$n."Username: $oDoctor->phone".$n."Password: $sPassword".$n."Landing Page: $landingPage";
        
        $aSmsSent = smsGateway($oDoctor->phone, $message, true);

        if($oDoctor->email){
            $message = str_replace('\n', '<br>', $message);
            dispatch(new SendEmail(Mail::to($oInput['email'])->send(new GeneralAlert("Welcome to the CareAll360", $message))));
        }

        $oDoctor['image'] = isset($image)? config("app")['url'].$image:null;
        $oDoctor['banner'] = isset($banner)? config("app")['url'].$banner:null;
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Doctors"]), $oDoctor, false);
        
        $this->urlComponents(config("businesslogic")[6]['menu'][1], $oResponse, config("businesslogic")[6]['title']);
        
        return $oResponse;
    }
    // Show Organization details

    public function show($id)
    {
        $id = decrypt($id);
        $oAuth = Auth::user();
        $oDoctor= Doctor::with(['user','doctorAssistant','doctorAward','doctorCertification','doctorExperience','doctorQualification','createdBy','updatedBy','restoredBy','deletedBy','organization'])->findOrFail($id);
        if($oAuth->isClient()){

            $organization_id = $oAuth->organization_id;
            $oOrganizationDoctor= Doctor::where('organization_id',$organization_id)->where('id',$id)->first();
            
            if(!$oOrganizationDoctor){
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            }
        }
        $oUser = $oDoctor->user;
        if($oUser){
            $oDoctor['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
            $oDoctor['banner'] = (count($oUser->bannerPic)>0)? config("app")['url'].$oUser->bannerPic[0]->url:null;
        }else{
            $oDoctor['image'] = null;
            $oDoctor['banner'] = null;
        }
        
        if (!Gate::allows('doctor-show',$oDoctor) && !Gate::allows('doctor-profile-view',$oDoctor))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Doctors"]), $oDoctor, false);
        
        $this->urlComponents(config("businesslogic")[6]['menu'][2], $oResponse, config("businesslogic")[6]['title']);
        
        return $oResponse;
    }

    public function edit(Doctor $oOrganization)
    {
        //
    } 

    // Update Organization details
    
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $id = decrypt($id);
        $oInput['url'] = isset($oInput['url'])? strtolower(str_replace(' ', '-', $oInput['url'])) : '';
        $oValidator = Validator::make($oInput,[
            'full_name'   => 'required|string|max:50|min:3',
            'gender'      => 'required|in:male,female,transgender',
            'title'       => 'required|in:Dr,Prof,Asst',
            // 'pmdc'        => ''.$id,
            'url'         => 'required|max:250|string|unique:doctors,url,'.$id,
            'phone'       => 'required|digits_between:10,20|unique:doctors,phone,'.$id,
            'email'       => 'required|email|max:50|unique:doctors,email,'.$id,
            'speciality'  => 'present|max:250|string|nullable',
            'about'       => 'present|string|nullable',
            'is_active'   => 'required|in:0,1',
            'dob'         => 'present|nullable|date',
            'practice_start_year'=> 'present|date|nullable',
            'organization_id'=> 'nullable|exists:organizations,id',
            // 'image' 	  => 'nullable|file|mimes:jpeg,jpg,png',
            // 'banner' 	  => 'nullable|file|mimes:jpeg,jpg,png',
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
        if(isset($request->banner)){
            $bannerExtension = $request->banner->extension();
            if($bannerExtension != "png" && $bannerExtension != "jpg" && $bannerExtension != "jpeg"){
                abort(400,"The banner must be a file of type: jpeg, jpg, png.");
            }
        }
        $oDoctor = Doctor::findOrFail($id);
       
        $oUser   = User::where('doctor_id',$id)->whereNull('patient_id')->where('organization_id',$oDoctor->organization_id)->first();
        if($oUser){
            $oUserEmailCheck = User::where('id','!=',$oUser->id)->where('email',$oInput['email'])->where('doctor_id',$id)->whereNull('patient_id')->where('organization_id',$oDoctor->organization_id)->first();
            if(isset($oUserEmailCheck)){
                return responseBuilder()->error(__('The email has already been taken.'), 400, false);
            }
            $oUserPhoneCheck = User::where('id','!=',$oUser->id)->where('phone',$oInput['phone'])->where('doctor_id',$id)->whereNull('patient_id')->where('organization_id',$oDoctor->organization_id)->first();
            if(isset($oUserPhoneCheck)){
                return responseBuilder()->error(__('The phone has already been taken.'), 400, false);
            }
       } 
       
           $oCheckPaientAlready = Doctor::where('pmdc',$oInput['pmdc'])->first();
        if($oCheckPaientAlready && $oInput['pmdc'] !=="0001"){
            return responseBuilder()->error(__('Pmdc is already exist'), 403, false);
        }
       
        if (!Gate::allows('doctor-update',$oDoctor) && !Gate::allows('doctor-profile-update',$oDoctor))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctor = $oDoctor->update([
            'full_name'     =>  $oInput['full_name'],
            'gender'        =>  $oInput['gender'],
            'title'         =>  $oInput['title'],
            'pmdc'          =>  $oInput['pmdc'],
            'url'           =>  $oInput['url'],
            'phone'         =>  $oInput['phone'],
            'email'         =>  $oInput['email'],
            'speciality'    =>  $oInput['speciality'],
            'dob'           => $oInput['dob'], 
            'about'         =>  $oInput['about'],
            'is_active'     =>  $oInput['is_active'],
            'practice_start_year' =>  $oInput['practice_start_year'],
            'organization_id'=>  isset($oInput['organization_id'])?$oInput['organization_id']:$oDoctor->organization_id,
            'updated_by'    =>  Auth::user()->id,
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $oUser = User::where('doctor_id',$id)->first();
        
        if($oUser){

            $oOldUser = $oUser;


            if(isset($request->image)){
            
                $oMedia = Media::where('user_id',$oUser->id)->where('type','profile')->first();
                
                if($oMedia){
                    FileHelper::deleteImages($oMedia);
                    $oMedia->forceDelete();
                }
               
                $oPaths = FileHelper::saveImages($request->image,'doctors');
                
                $media = Media::create([
                    'user_id'   => $oUser->id,
                    'url'       => $oPaths['url'],
                    'alt_tag'   => $oInput['full_name'],
                    'type'      => 'profile',
                    'updated_by'=> Auth::user()->id,
                    'updated_at'=> Carbon::now()->toDateTimeString(),
                ]); 
            }
            
            if(isset($request->banner)){
            
                $oBannerMedia = Media::where('user_id',$oUser->id)->where('type','banner')->first();
                
                if($oBannerMedia){
                    FileHelper::deleteImages($oBannerMedia);
                    $oBannerMedia->forceDelete();
                }
                
                $oBannerPaths = FileHelper::saveImages($request->banner,'banners');
                
                $media = Media::create([
                    'user_id'   => $oUser->id,
                    'url'       => $oBannerPaths['url'],
                    'alt_tag'   => $oInput['full_name'],
                    'type'      => 'banner',
                    'updated_by'=> Auth::user()->id,
                    'updated_at'=> Carbon::now()->toDateTimeString(),
                ]); 
            }

            $aUserUpdateData = array(
                'name'          =>  $oInput['full_name'],
                'phone'         =>  $oInput['phone'],
                'email'         =>  $oInput['email'],
                'username'      =>  $oInput['phone'],
                'updated_by'    =>  Auth::user()->id,
                'updated_at'    =>  Carbon::now()->toDateTimeString(),
            );

            //find if email address modified, if yes then switch flag off of email verified
            if($oOldUser->email != $oInput['email']){
                $aUserUpdateData['email_verified'] = 0;
            }

            //find if phone number modified, then switch flag off of phone verified
            if($oOldUser->phone != $oInput['phone']){
                $aUserUpdateData['phone_verified'] = 1;
            }

            $oUser->update($aUserUpdateData);
        }
        
        $oDoctor = Doctor::with(['user','doctorAssistant','doctorAward','doctorCertification','doctorExperience','doctorQualification','createdBy','updatedBy','restoredBy','deletedBy','organization'])->findOrFail($id);
        
        $oUser = $oDoctor->user;
        if($oUser){
            $oDoctor['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
            $oDoctor['banner'] = (count($oUser->bannerPic)>0)? config("app")['url'].$oUser->bannerPic[0]->url:null;
        }else{
            $oDoctor['image'] = null;
            $oDoctor['banner'] = null;
        }
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Doctors"]), $oDoctor, false);
        
        $this->urlComponents(config("businesslogic")[6]['menu'][3], $oResponse, config("businesslogic")[6]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();

        $oInput = DecryptId($oInput);
        
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctors,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $oInput['ids'];
        $allDoctor = Doctor::findOrFail($aIds);
        
        foreach($allDoctor as $oRow)
            if (!Gate::allows('doctor-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
    
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctor = Doctor::find($id);
                if($oDoctor){
                    // Appointment::where('doctor_id',$id)->update(['deleted_by' => Auth::user()->id]);
                    // Appointment::where('doctor_id',$id)->delete();
                    // Patient::where('doctor_id',$id)->update(['deleted_by' => Auth::user()->id]);
                    Patient::where('doctor_id',$id)->delete();
                    // $oDoctor->update(['deleted_by' => Auth::user()->id]);
                    Appointment::where('doctor_id',$id)->delete();
                    Partnership::where('doctor_id',$id)->delete();
                    PaymentSummary::where('doctor_id',$id)->delete();
                    // $oDoctor->update(['is_active' => 0]);
                    $oDoctor->delete();
                    
                }
            }
        }else{
            $oDoctor = Doctor::findOrFail($aIds);
        
            $oDoctor->update(['deleted_by' => Auth::user()->id]);
            $oDoctor->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Doctors"]));
        $this->urlComponents(config("businesslogic")[6]['menu'][4], $oResponse, config("businesslogic")[6]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request)
    {
        if (!Gate::allows('doctor-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        
        $oQb = Doctor::onlyTrashed()->orderBYDesc('deleted_at')->with(['doctorAssistant','doctorAward','doctorCertification','doctorExperience','doctorQualification','createdBy','updatedBy','restoredBy','deletedBy','organization']);
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"organization_id",$oQb);
        $oQb = QB::whereLike($oInput,"full_name",$oQb);
        $oQb = QB::whereLike($oInput,"gender",$oQb);
        $oQb = QB::whereLike($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"pmdc",$oQb);
        $oQb = QB::whereLike($oInput,"phone",$oQb);
        $oQb = QB::whereLike($oInput,"speciality",$oQb);
        
        $oDoctors = $oQb->paginate(10);
        $oDoctors = userImage($oDoctors);
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Doctors"]), $oDoctors, false);
        
        $this->urlComponents(config("businesslogic")[6]['menu'][5], $oResponse, config("businesslogic")[6]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {
        $oInput = $request->all();
        $oInput = DecryptId($oInput);
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctors,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $oInput['ids'];
        $allDoctor = Doctor::onlyTrashed()->findOrFail($aIds);
        
        foreach($allDoctor as $oRow)
            if (!Gate::allows('doctor-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oDoctor = Doctor::onlyTrashed()->find($id);
                if($oDoctor){
                    
                    $deleted_at = $oDoctor->deleted_at;
                    $oDoctor->update([
                        'restored_by' => Auth::user()->id,
                        'restored_at' => Carbon::now()->toDateTimeString(),                  
                        ]);
                    $oDoctor->restore();
                    Patient::where('deleted_at',$deleted_at)->where('doctor_id',$id)->restore();
                    Appointment::where('deleted_at',$deleted_at)->where('doctor_id',$id)->restore();
                    Partnership::where('deleted_at',$deleted_at)->where('doctor_id',$id)->restore();
                    PaymentSummary::where('deleted_at',$deleted_at)->where('doctor_id',$id)->restore();
                    
                    // Appointment::where('deleted_at',$deleted_at)->where('doctor_id',$id)->update([
                    //     'restored_by' => Auth::user()->id,
                    //     'restored_at' => Carbon::now()->toDateTimeString(),                  
                    //     ]);
                    // Appointment::where('deleted_at',$deleted_at)->where('doctor_id',$id)->restore();
                    // Patient::where('deleted_at',$deleted_at)->where('doctor_id',$id)->update([
                    //     'restored_by' => Auth::user()->id,
                    //     'restored_at' => Carbon::now()->toDateTimeString(),                  
                    //     ]);
                    // Patient::where('deleted_at',$deleted_at)->where('doctor_id',$id)->restore();
                }
            }
        }else{
            $oDoctor = Doctor::onlyTrashed()->findOrFail($aIds);
            $oDoctor->update([
                'restored_by' => Auth::user()->id,
                'restored_at' => Carbon::now()->toDateTimeString(),                  
                ]);
            $oDoctor->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Doctor"]));
        
        $this->urlComponents(config("businesslogic")[6]['menu'][6], $oResponse, config("businesslogic")[6]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $id = decrypt($id);
        $oDoctor = Doctor::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('doctor-delete',$oDoctor))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oDoctor->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Doctor"]));
        
        $this->urlComponents(config("businesslogic")[6]['menu'][7], $oResponse, config("businesslogic")[6]['title']);
        
        return $oResponse;
    }

    public function landingPage($url){
        
        $oDoctor= Doctor::where('url',$url)->where('is_active',1)->first();

        if(!isset($oDoctor)){
            
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
        }
        $id = decrypt($oDoctor->id);

        $oDoctor['doctor_assistant'] = DoctorAssistant::where('status',1)->where('doctor_id',$id)->select('name','phone','email')->get();
        $oDoctor['doctor_award'] = DoctorAward::where('doctor_id',$id)->select('name','year','description')->get();
        $oDoctor['doctor_certification'] = DoctorCertification::where('doctor_id',$id)->select('title','institute','country_code','completed_year')->with(['countryCode'])->get();
        $oDoctor['doctor_experience'] = DoctorExperience::where('doctor_id',$id)->select('designation','institute','year_from','year_to','is_current','description')->get();
        $oDoctor['doctor_qualification'] = DoctorQualification::where('doctor_id',$id)->select('title','university','country_code','start_year','end_year')->with(['countryCode'])->get();
        $oDoctor['doctor_treatment'] = DoctorTreatment::where('is_active',1)->where('doctor_id',$id)->select('id','treatment_name','description')->get();
        $oDoctor['doctor_center'] = Center::where('is_active',1)->where('doctor_id',$id)->select('id','name','address','lat','lng','city_id','country_code','is_primary')->with(['countryCode','cityId'])->get();
        $oDoctor['doctor_web_setting'] = WebSetting::where('doctor_id',$id)->select('name','value','icon_class')->get();
        $oDoctor['doctor_schedule'] = DoctorSchedule::where('is_active',1)->where('doctor_id',$id)->select('heading','type','fee','discount_fee','minimum_booking_hours','duration','date_from','date_to','vocation_date_from','vocation_date_to','is_primary','is_vocation')->get();
        
        $oUser = User::where('doctor_id',$id)->with('media')->first();
        
        if($oUser){
            $oDoctor['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
            $oDoctor['banner'] = (count($oUser->bannerPic)>0)? config("app")['url'].$oUser->bannerPic[0]->url:null;
        }else{
            $oDoctor['image'] = null;
            $oDoctor['banner'] = null;
        }
        
        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Doctors Landing Page"]), $oDoctor, false);
        
        $this->urlComponents(config("businesslogic")[6]['menu'][8], $oResponse, config("businesslogic")[6]['title']);
        
        return $oResponse;
    }
    public function allDoctors(){
        $oAuth = Auth::user();
        if($oAuth->isClient()){
            $oDoctors = Doctor::where('organization_id',$oAuth->organization_id)->where('is_active',1)->orderByDesc('updated_at')->get();
            foreach($oDoctors as $oDoctor){
                $oDoctor['doctor_center'] = Center::where('is_active',1)->where('doctor_id',$oDoctor->pk)->select('id','name','address','lat','lng','city_id','country_code','is_primary')->with(['countryCode','cityId'])->get();
            }
        }elseif ($oAuth->isDoctor()) {
            $oDoctors = Doctor::where('doctor_id',$oAuth->doctor_id)->where('is_active',1)->orderByDesc('updated_at')->get();
            foreach($oDoctors as $oDoctor){
                $oDoctor['doctor_center'] = Center::where('is_active',1)->where('doctor_id',$oDoctor->pk)->select('id','name','address','lat','lng','city_id','country_code','is_primary')->with(['countryCode','cityId'])->get();
            }
        }else{
            $oDoctors = Doctor::orderByDesc('updated_at')->where('is_active',1)->get();
            foreach($oDoctors as $oDoctor){
                $oDoctor['doctor_center'] = Center::where('is_active',1)->where('doctor_id',$oDoctor->pk)->select('id','name','address','lat','lng','city_id','country_code','is_primary')->with(['countryCode','cityId'])->get();
            }
        }
        
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"All Doctors"]), $oDoctors, false);
        
        $this->urlComponents(config("businesslogic")[6]['menu'][9], $oResponse, config("businesslogic")[6]['title']);
        
        return $oResponse;
    }    
}
