<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\DoctorAssistant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DoctorAssistantController extends Controller
{
    use \App\Traits\WebServicesDoc;

    public function index(Request $request, $doctor_id)
    {
        if (!Gate::allows('doctor-assistant-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $doctor_id = decrypt($doctor_id);
        
        $oDoctor = AuthUserRoleChecker($doctor_id);
        $oQb = DoctorAssistant::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"phone",$oQb);
        $oQb = QB::whereLike($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"status",$oQb);

        $oAssistant = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Assistants"]), $oAssistant, false);
        $this->urlComponents(config("businesslogic")[9]['menu'][0], $oResponse, config("businesslogic")[9]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('doctor-assistant-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'doctor_id'   => 'required|exists:doctors,id',
            'name'        => 'required|string|max:50|min:3',
            'phone'       => 'required|digits_between:10,20',
            // 'phone'       => 'required|digits_between:10,20|unique:doctor_assistants,phone,null,null,doctor_id,'.$oInput['doctor_id'],
            // 'email'       => 'present|required|email|max:50|unique:doctor_assistants,email,null,null,doctor_id,'.$oInput['doctor_id'],
            'email'       => 'present|required|email|max:50',
            'status'      => 'required|in:0,1',
            
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);
        
        $oDate =DoctorAssistant::where('doctor_id',$oInput['doctor_id'])->where('phone',$oInput['phone'])->first();
        if($oDate){
            abort(400,__('Assistant phone already entered'));
        }
        $oDate = DoctorAssistant::where('doctor_id',$oInput['doctor_id'])->where('email',$oInput['email'])->first();
        if($oDate){
            abort(400,__('Assistant email already entered'));
        }
        $doctorAssistant = DoctorAssistant::create([
            'doctor_id' => $oInput['doctor_id'],
            'name'      => $oInput['name'],
            'phone'     => $oInput['phone'],
            'email'     => $oInput['email'],
            'status'    => $oInput['status'],
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'created_at'=> Carbon::now()->toDateTimeString(),
            'updated_at'=> Carbon::now()->toDateTimeString(),
        ]);
        $doctorAssistant= DoctorAssistant::with(['createdBy','updatedBy','deletedBy'])->findOrFail($doctorAssistant->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Assistants"]), $doctorAssistant, false);
        
        $this->urlComponents(config("businesslogic")[9]['menu'][1], $oResponse, config("businesslogic")[9]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DoctorAssistant  $doctorAssistant
     * @return \Illuminate\Http\Response
     */
    public function show($assistant_id)
    {
        $oAssistant = DoctorAssistant::with(['createdBy','updatedBy','deletedBy'])->findOrFail($assistant_id);
        
        if (!Gate::allows('doctor-assistant-show',$oAssistant))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Assistant"]), $oAssistant, false);
        
        $this->urlComponents(config("businesslogic")[9]['menu'][2], $oResponse, config("businesslogic")[9]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DoctorAssistant  $doctorAssistant
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorAssistant $doctorAssistant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DoctorAssistant  $doctorAssistant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'doctor_id'   => 'required|exists:doctors,id',
            'name'        => 'required|string|max:50|min:3',
            // 'phone'       => 'required|digits_between:10,20|unique:doctor_assistants,phone,'.$id.',id,doctor_id,'.$oInput['doctor_id'],
            // 'email'       => 'present|required|email|max:50|unique:doctor_assistants,email,'.$id.',id,doctor_id,'.$oInput['doctor_id'],
            'phone'       => 'required|digits_between:10,20',
            'email'       => 'present|required|email|max:50',
            'status'      => 'required|in:0,1',
            
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDate = DoctorAssistant::where('doctor_id',$oInput['doctor_id'])->where('id','!=',$id)->where('phone',$oInput['phone'])->first();
        if($oDate){
            abort(400,__('Assistant phone already entered'));
        }
        $oDate = DoctorAssistant::where('doctor_id',$oInput['doctor_id'])->where('id','!=',$id)->where('email',$oInput['email'])->first();
        if($oDate){
            abort(400,__('Assistant email already entered'));
        }
        
        $doctorAssistant = DoctorAssistant::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('doctor-assistant-update',$doctorAssistant))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $doctorAssistant = $doctorAssistant->update([
            'doctor_id' => $oInput['doctor_id'],
            'name'      => $oInput['name'],
            'phone'     => $oInput['phone'],
            'email'     => $oInput['email'],
            'status'    => $oInput['status'],
            'updated_by'=>  Auth::user()->id,
            'updated_at'=>  Carbon::now()->toDateTimeString(),
        ]);
        $doctorAssistant = DoctorAssistant::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Assistant"]), $doctorAssistant, false);
        
        $this->urlComponents(config("businesslogic")[9]['menu'][3], $oResponse, config("businesslogic")[9]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_assistants,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allDoctorAssistant = DoctorAssistant::findOrFail($aIds);
        
        foreach($allDoctorAssistant as $oRow)
            if (!Gate::allows('doctor-assistant-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctorAssistant = DoctorAssistant::find($id);
                if($oDoctorAssistant){
                    $oDoctorAssistant->update(['deleted_by' => Auth::user()->id]);
                    $oDoctorAssistant->delete();
                }
            }
        }else{
            $oDoctorAssistant = DoctorAssistant::findOrFail($aIds);
        
            $oDoctorAssistant->update(['deleted_by' => Auth::user()->id]);
            $oDoctorAssistant->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Assistant"]));
        $this->urlComponents(config("businesslogic")[9]['menu'][4], $oResponse, config("businesslogic")[9]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$doctor_id)
    {
        $oInput = $request->all();
        $doctor_id = decrypt($doctor_id);
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        if (!Gate::allows('doctor-assistant-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oQb = DoctorAssistant::where('doctor_id',$doctor_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"phone",$oQb);
        $oQb = QB::whereLike($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"status",$oQb);

        $oAssistant = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Assistants"]), $oAssistant, false);
        
        $this->urlComponents(config("businesslogic")[9]['menu'][5], $oResponse, config("businesslogic")[9]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_assistants,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allDoctorAssistant = DoctorAssistant::onlyTrashed()->findOrFail($aIds);
        
        foreach($allDoctorAssistant as $oRow)
            if (!Gate::allows('doctor-assistant-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $doctor_assistants = DoctorAssistant::onlyTrashed()->find($id);
                if($doctor_assistants){
                    $doctor_assistants->restore();
                }
            }
        }else{
            $doctor_assistants = DoctorAssistant::onlyTrashed()->findOrFail($aIds);
            $doctor_assistants->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Assistant"]));
        
        $this->urlComponents(config("businesslogic")[9]['menu'][6], $oResponse, config("businesslogic")[9]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $doctor_assistants = DoctorAssistant::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('doctor-assistant-delete',$doctor_assistants))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $doctor_assistants->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Assistant"]));
        
        $this->urlComponents(config("businesslogic")[9]['menu'][7], $oResponse, config("businesslogic")[9]['title']);
        
        return $oResponse;
    }
}
