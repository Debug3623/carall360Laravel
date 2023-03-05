<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\WebSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Mail\GeneralAlert;
use App\Jobs\SendEmail;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class WebSettingController extends Controller
{
    use \App\Traits\WebServicesDoc;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request, $doctor_id)
    {
        $oInput = $request->all();
        $doctor_id = decrypt($doctor_id);
        if (!Gate::allows('web-setting-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = WebSetting::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"value",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        
        $oWebSetting = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Web Settings"]), $oWebSetting, false);
        $this->urlComponents(config("businesslogic")[19]['menu'][0], $oResponse, config("businesslogic")[19]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('web-setting-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'name'     => 'required|string|max:50|min:3',
            'value'    => 'required|string|max:50|min:3',
            'is_active'=> 'required|in:0,1',
            'doctor_id'=> 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);

        $oWebSetting = WebSetting::create([
            'doctor_id'     => $oInput['doctor_id'],
            'name'          => $oInput['name'],
            'value'         => $oInput['value'],
            'icon_class'    => isset($oInput['icon_class'])?$oInput['icon_class']:null,
            'is_active'     => $oInput['is_active'],
            'created_by'    => Auth::user()->id,
            'updated_by'    => Auth::user()->id,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oWebSetting= WebSetting::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oWebSetting->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Web Setting"]), $oWebSetting, false);
        
        $this->urlComponents(config("businesslogic")[19]['menu'][1], $oResponse, config("businesslogic")[19]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WebSetting  $WebSetting
     * @return \Illuminate\Http\Response
     */
    public function show($WebSetting_id)
    {
        $oWebSetting = WebSetting::with(['createdBy','updatedBy','deletedBy'])->findOrFail($WebSetting_id);
        
        if (!Gate::allows('web-setting-show',$oWebSetting))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Web Setting"]), $oWebSetting, false);
        
        $this->urlComponents(config("businesslogic")[19]['menu'][2], $oResponse, config("businesslogic")[19]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WebSetting  $WebSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(WebSetting $WebSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WebSetting  $WebSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'name'     => 'required|string|max:50|min:3',
            'value'    => 'required|string|max:50|min:3',
            'is_active'=> 'required|in:0,1',
            'doctor_id'=> 'required|exists:doctors,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);

        $oWebSetting = WebSetting::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('web-setting-update',$oWebSetting))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oWebSetting = $oWebSetting->update([
            'doctor_id'     => $oInput['doctor_id'],
            'name'          => $oInput['name'],
            'value'         => $oInput['value'],
            'icon_class'    => isset($oInput['icon_class'])?$oInput['icon_class']:null,
            'is_active'     => $oInput['is_active'],
            'updated_by'    => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oWebSetting = WebSetting::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Web Setting"]), $oWebSetting, false);
        
        $this->urlComponents(config("businesslogic")[19]['menu'][3], $oResponse, config("businesslogic")[19]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:web_settings,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allWebSetting = WebSetting::findOrFail($aIds);
        
        foreach($allWebSetting as $oRow)
            if (!Gate::allows('web-setting-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oWebSetting = WebSetting::find($id);
                if($oWebSetting){
                    $oWebSetting->update(['deleted_by' => Auth::user()->id]);
                    $oWebSetting->delete();
                }
            }
        }else{
            $oWebSetting = WebSetting::findOrFail($aIds);
        
            $oWebSetting->update(['deleted_by' => Auth::user()->id]);
            $oWebSetting->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Web Setting"]));
        $this->urlComponents(config("businesslogic")[19]['menu'][4], $oResponse, config("businesslogic")[19]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$doctor_id)
    {
        $doctor_id = decrypt($doctor_id);
        if (!Gate::allows('web-setting-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        $oInput = $request->all();
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = WebSetting::where('doctor_id',$doctor_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"value",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);

        $oWebSetting = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Web Setting"]), $oWebSetting, false);
        
        $this->urlComponents(config("businesslogic")[19]['menu'][5], $oResponse, config("businesslogic")[19]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:web_settings,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allWebSetting= WebSetting::onlyTrashed()->findOrFail($aIds);
        
        foreach($allWebSetting as $oRow)
            if (!Gate::allows('web-setting-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oWebSetting = WebSetting::onlyTrashed()->find($id);
                if($oWebSetting){
                    $oWebSetting->restore();
                }
            }
        }else{
            $oWebSetting = WebSetting::onlyTrashed()->findOrFail($aIds);
            $oWebSetting->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Web Setting"]));
        
        $this->urlComponents(config("businesslogic")[19]['menu'][6], $oResponse, config("businesslogic")[19]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oWebSetting = WebSetting::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('web-setting-delete',$oWebSetting))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oWebSetting->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Web Setting"]));
        
        $this->urlComponents(config("businesslogic")[19]['menu'][7], $oResponse, config("businesslogic")[19]['title']);
        
        return $oResponse;
    }
    public function contactForm(Request $request){
        
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'name'     => 'required|string|max:50|min:3',
            'phone'    => 'required|digits_between:10,20',
            'email'    => 'required|email|max:50',
            'message'  => 'required|max:500|string',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $name= $oInput['name'];
        $phone= $oInput['phone'];
        $email= $oInput['email'];
        $message= $oInput['message'];
        $sMessage  = "$name send request to contact.<br><br>";
        $sMessage .= "Contact info are: <br><br>";
        $sMessage .= "<b>Phone:</b> $phone <br>";
        $sMessage .= "<b>Email:</b> $email <br>";
        $sMessage .= "<b>Message:</b> $message <br>";
        dispatch(new SendEmail(Mail::to('info@hospitall.tech')->send(new GeneralAlert("Contact Request", $sMessage))));
        // dispatch(new SendEmail(Mail::to('shafqat.ali@hospitall.tech')->send(new GeneralAlert("Contact Request", $sMessage))));
        $Message  = "Thank you for contacting us. Our team will respond you soon.";
        if (isset($phone)) {
            smsGateway($phone, $Message);
        }
        $oResponse = responseBuilder()->success(__('Email Sent Successfully'));
        
        $this->urlComponents(config("businesslogic")[19]['menu'][8], $oResponse, config("businesslogic")[19]['title']);
        
        return $oResponse;
    }
    public function listOfSocialLinks()
    {
        $oWebSetting = WebSetting::$SOCIALLINK;

        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Social Links"]), $oWebSetting, false);
        $this->urlComponents(config("businesslogic")[19]['menu'][9], $oResponse, config("businesslogic")[19]['title']);
        
        return $oResponse;
    }
}
