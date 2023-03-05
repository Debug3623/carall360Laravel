<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\DoctorQualification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DoctorQualificationController extends Controller
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
        if (!Gate::allows('doctor-qualification-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorQualification::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy','countryCode']);
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"title",$oQb);
        $oQb = QB::whereLike($oInput,"university",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        $oQb = QB::whereBetween($oInput,"start_year",$oQb);
        $oQb = QB::whereBetween($oInput,"end_year",$oQb);
        
        $oQualifications = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Qualifications"]), $oQualifications, false);
        $this->urlComponents(config("businesslogic")[13]['menu'][0], $oResponse, config("businesslogic")[13]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('doctor-qualification-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'title'         => 'required|string|max:50|min:3|unique:doctor_qualifications,title,null,null,doctor_id,'.$oInput['doctor_id'],
            'university'    => 'required|string|max:150|min:3',
            'country_code'  => 'required|string|max:3',
            'start_year'    => 'required|date',
            'end_year'      => 'required|date|after_or_equal:start_year',
            'doctor_id'     => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDate = DoctorQualification::where('doctor_id',$oInput['doctor_id'])->where('title',$oInput['title'])->first();
        if($oDate){
            abort(400,__('Doctor Qualification title already entered'));
        }
        $oDoctorQualification = DoctorQualification::create([
            'doctor_id'     => $oInput['doctor_id'],
            'title'         => $oInput['title'],
            'university'    => $oInput['university'],
            'country_code'  => $oInput['country_code'],
            'start_year'    => $oInput['start_year'],
            'end_year'      => $oInput['end_year'],
            'created_by'    => Auth::user()->id,
            'updated_by'    => Auth::user()->id,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorQualification= DoctorQualification::with(['createdBy','updatedBy','deletedBy','countryCode'])->findOrFail($oDoctorQualification->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Qualification"]), $oDoctorQualification, false);
        
        $this->urlComponents(config("businesslogic")[13]['menu'][1], $oResponse, config("businesslogic")[13]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DoctorQualification  $DoctorQualification
     * @return \Illuminate\Http\Response
     */
    public function show($Qualification_id)
    {
        $oQualification = DoctorQualification::with(['createdBy','updatedBy','deletedBy','countryCode'])->findOrFail($Qualification_id);
        
        if (!Gate::allows('doctor-qualification-show',$oQualification))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Qualification"]), $oQualification, false);
        
        $this->urlComponents(config("businesslogic")[13]['menu'][2], $oResponse, config("businesslogic")[13]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DoctorQualification  $DoctorQualification
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorQualification $DoctorQualification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DoctorQualification  $DoctorQualification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'title'         => 'required|string|max:50|min:3',
            'university'    => 'required|string|max:150|min:3',
            'country_code'  => 'required|string|max:3',
            'start_year'    => 'required|date',
            'end_year'      => 'required|date|after_or_equal:start_year',
            'doctor_id'     => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oDate = DoctorQualification::where('doctor_id',$oInput['doctor_id'])->where('id','!=',$id)->where('title',$oInput['title'])->first();
        if($oDate){
            abort(400,__('Doctor Qualification title already entered'));
        }
        $oDoctorQualification = DoctorQualification::with(['createdBy','updatedBy','deletedBy','countryCode'])->findOrFail($id);
        
        if (!Gate::allows('doctor-qualification-update',$oDoctorQualification))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorQualification = $oDoctorQualification->update([
            'doctor_id'     => $oInput['doctor_id'],
            'title'         => $oInput['title'],
            'university'    => $oInput['university'],
            'country_code'  => $oInput['country_code'],
            'start_year'    => $oInput['start_year'],
            'end_year'      => $oInput['end_year'],
            'updated_by'    => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorQualification = DoctorQualification::with(['createdBy','updatedBy','deletedBy','countryCode'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Qualification"]), $oDoctorQualification, false);
        
        $this->urlComponents(config("businesslogic")[13]['menu'][3], $oResponse, config("businesslogic")[13]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_qualifications,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allDoctorQualification = DoctorQualification::findOrFail($aIds);
        
        foreach($allDoctorQualification as $oRow)
            if (!Gate::allows('doctor-qualification-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctorQualification = DoctorQualification::find($id);
                if($oDoctorQualification){
                    $oDoctorQualification->update(['deleted_by' => Auth::user()->id]);
                    $oDoctorQualification->delete();
                }
            }
        }else{
            $oDoctorQualification = DoctorQualification::findOrFail($aIds);
        
            $oDoctorQualification->update(['deleted_by' => Auth::user()->id]);
            $oDoctorQualification->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Qualification"]));
        $this->urlComponents(config("businesslogic")[13]['menu'][4], $oResponse, config("businesslogic")[13]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$doctor_id)
    {
        $doctor_id = decrypt($doctor_id);
        if (!Gate::allows('doctor-qualification-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        $oInput = $request->all();
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorQualification::where('doctor_id',$doctor_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy','countryCode']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"title",$oQb);
        $oQb = QB::whereLike($oInput,"university",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        $oQb = QB::whereBetween($oInput,"start_year",$oQb);
        $oQb = QB::whereBetween($oInput,"end_year",$oQb);

        $oQualification = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Qualification"]), $oQualification, false);
        
        $this->urlComponents(config("businesslogic")[13]['menu'][5], $oResponse, config("businesslogic")[13]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_qualifications,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allDoctorQualification= DoctorQualification::onlyTrashed()->findOrFail($aIds);
        
        foreach($allDoctorQualification as $oRow)
            if (!Gate::allows('doctor-qualification-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oDoctorQualification = DoctorQualification::onlyTrashed()->find($id);
                if($oDoctorQualification){
                    $oDoctorQualification->restore();
                }
            }
        }else{
            $oDoctorQualification = DoctorQualification::onlyTrashed()->findOrFail($aIds);
            $oDoctorQualification->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Qualification"]));
        
        $this->urlComponents(config("businesslogic")[13]['menu'][6], $oResponse, config("businesslogic")[13]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oDoctorQualification = DoctorQualification::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('doctor-qualification-delete',$oDoctorQualification))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorQualification->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Qualification"]));
        
        $this->urlComponents(config("businesslogic")[13]['menu'][7], $oResponse, config("businesslogic")[13]['title']);
        
        return $oResponse;
    }
}
