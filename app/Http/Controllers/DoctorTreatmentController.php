<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Http\Controllers\Controller;

use App\DoctorTreatment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\Treatment;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DoctorTreatmentController extends Controller
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
        if (!Gate::allows('doctor-treatment-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorTreatment::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"treatment_name",$oQb);
        $oQb = QB::where($oInput,"treatment_id",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);
        
        $oTreatments = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Treatments"]), $oTreatments, false);
        $this->urlComponents(config("businesslogic")[14]['menu'][0], $oResponse, config("businesslogic")[14]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('doctor-treatment-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'treatment_name'=> 'required|string|max:50|min:3',
            'description'   => 'present|nullable|string',
            'is_active'     => 'required|in:0,1',
            'treatment_id'  => 'present|nullable|exists:treatments,id',
            'doctor_id'     => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);

        $oDate = $oDoctor->doctorTreatment()->where('treatment_name',$oInput['treatment_name'])->first();
        if($oDate){
            abort(400,__('Treatment name already entered'));
        }
        $oDoctorTreatment = DoctorTreatment::create([
            'doctor_id'     => $oInput['doctor_id'],
            'treatment_name'=> $oInput['treatment_name'],
            'description'   => $oInput['description'],
            'is_active'     => $oInput['is_active'],
            'treatment_id'  => $oInput['treatment_id'],
            'created_by'    => Auth::user()->id,
            'updated_by'    => Auth::user()->id,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorTreatment= DoctorTreatment::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oDoctorTreatment->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Treatment"]), $oDoctorTreatment, false);
        
        $this->urlComponents(config("businesslogic")[14]['menu'][1], $oResponse, config("businesslogic")[14]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DoctorTreatment  $DoctorTreatment
     * @return \Illuminate\Http\Response
     */
    public function show($Treatment_id)
    {
        $oTreatment = DoctorTreatment::with(['createdBy','updatedBy','deletedBy'])->findOrFail($Treatment_id);
        
        if (!Gate::allows('doctor-treatment-show',$oTreatment))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Treatment"]), $oTreatment, false);
        
        $this->urlComponents(config("businesslogic")[14]['menu'][2], $oResponse, config("businesslogic")[14]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DoctorTreatment  $DoctorTreatment
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorTreatment $DoctorTreatment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DoctorTreatment  $DoctorTreatment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'treatment_name'=> 'required|string|max:50|min:3',
            'description'   => 'present|nullable|string',
            'is_active'     => 'required|in:0,1',
            //'treatment_id'  => 'present|nullable|exists:treatments,id',
            'doctor_id'     => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);
        
        $oDate = $oDoctor->doctorTreatment()->where('id','!=',$id)->where('treatment_name',$oInput['treatment_name'])->first();
        if($oDate){
            abort(400,__('Treatment name already entered'));
        }
        $oDoctorTreatment = DoctorTreatment::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('doctor-treatment-update',$oDoctorTreatment))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorTreatment = $oDoctorTreatment->update([
            'doctor_id'     => $oInput['doctor_id'],
            'treatment_name'=> $oInput['treatment_name'],
            'description'   => $oInput['description'],
            'is_active'     => $oInput['is_active'],
            'treatment_id'  => $oInput['treatment_id'],
            'updated_by'    => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorTreatment = DoctorTreatment::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Treatment"]), $oDoctorTreatment, false);
        
        $this->urlComponents(config("businesslogic")[14]['menu'][3], $oResponse, config("businesslogic")[14]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_treatments,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allDoctorTreatment = DoctorTreatment::findOrFail($aIds);
        
        foreach($allDoctorTreatment as $oRow)
            if (!Gate::allows('doctor-treatment-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctorTreatment = DoctorTreatment::find($id);
                $oAppointment = Appointment::where('treatment_id',$id)->first();
                if(isset($oAppointment)){
                   abort(400,'You are not allow to delete that treatment. Treatment is used in Appointment'); 
                }
                if($oDoctorTreatment){
                    $oDoctorTreatment->update(['deleted_by' => Auth::user()->id]);
                    $oDoctorTreatment->delete();
                }
            }
        }else{
            $oDoctorTreatment = DoctorTreatment::findOrFail($aIds);
        
            $oDoctorTreatment->update(['deleted_by' => Auth::user()->id]);
            $oDoctorTreatment->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Treatment"]));
        $this->urlComponents(config("businesslogic")[14]['menu'][4], $oResponse, config("businesslogic")[14]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$doctor_id)
    {
        $doctor_id = decrypt($doctor_id);
        if (!Gate::allows('doctor-treatment-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        $oInput = $request->all();
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorTreatment::where('doctor_id',$doctor_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"treatment_name",$oQb);
        $oQb = QB::where($oInput,"treatment_id",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);

        $oTreatment = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Treatment"]), $oTreatment, false);
        
        $this->urlComponents(config("businesslogic")[14]['menu'][5], $oResponse, config("businesslogic")[14]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_treatments,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allDoctorTreatment= DoctorTreatment::onlyTrashed()->findOrFail($aIds);
        
        foreach($allDoctorTreatment as $oRow)
            if (!Gate::allows('doctor-treatment-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oDoctorTreatment = DoctorTreatment::onlyTrashed()->find($id);
                if($oDoctorTreatment){
                    $oDoctorTreatment->restore();
                }
            }
        }else{
            $oDoctorTreatment = DoctorTreatment::onlyTrashed()->findOrFail($aIds);
            $oDoctorTreatment->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Treatment"]));
        
        $this->urlComponents(config("businesslogic")[14]['menu'][6], $oResponse, config("businesslogic")[14]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oDoctorTreatment = DoctorTreatment::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('doctor-treatment-delete',$oDoctorTreatment))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorTreatment->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Treatment"]));
        
        $this->urlComponents(config("businesslogic")[14]['menu'][7], $oResponse, config("businesslogic")[14]['title']);
        
        return $oResponse;
    }
    public function allTreatments(Request $request)
     {
         $oInput = $request->all();

         $oQb = Treatment::where('is_active',1)->orderByDesc('updated_at');
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         
         $oTreatment = $oQb->get();
        
         $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"All Treatments"]), $oTreatment, false);
         $this->urlComponents(config("businesslogic")[14]['menu'][8], $oResponse, config("businesslogic")[14]['title']);
         
         return $oResponse;
     }
     public function allDoctorTreatments(Request $request, $doctor_id)
    {
        $doctor_id = decrypt($doctor_id);       

        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oTreatments = DoctorTreatment::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->select('id','treatment_name')->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Treatments"]), $oTreatments, false);
        $this->urlComponents(config("businesslogic")[14]['menu'][9], $oResponse, config("businesslogic")[14]['title']);
        
        return $oResponse;
    }
}
