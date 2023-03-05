<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\DoctorCertification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DoctorCertificationController extends Controller
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
        
        if (!Gate::allows('doctor-certification-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            
        $doctor_id = decrypt($doctor_id);
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorCertification::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy','countryCode']);
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"title",$oQb);
        $oQb = QB::whereLike($oInput,"institute",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        $oQb = QB::whereBetween($oInput,"completed_year",$oQb);
        
        $oCertifications = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Certifications"]), $oCertifications, false);
        $this->urlComponents(config("businesslogic")[12]['menu'][0], $oResponse, config("businesslogic")[12]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('doctor-certification-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'title'         => 'required|string|max:50|min:3',
            'completed_year'=> 'required|date',
            'institute'     => 'required|string|max:150|min:3',
            'country_code'  => 'required|string|max:3',
            'doctor_id'     => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);
        
        $oDate = DoctorCertification::where('doctor_id',$oInput['doctor_id'])->where('title',$oInput['title'])->first();
        if($oDate){
            abort(400,__('Certification title already entered'));
        }
        $oDoctorCertification = DoctorCertification::create([
            'doctor_id'     => $oInput['doctor_id'],
            'title'         => $oInput['title'],
            'completed_year'=> $oInput['completed_year'],
            'institute'     => $oInput['institute'],
            'country_code'  => $oInput['country_code'],
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'created_at'=> Carbon::now()->toDateTimeString(),
            'updated_at'=> Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorCertification= DoctorCertification::with(['createdBy','updatedBy','deletedBy','countryCode'])->findOrFail($oDoctorCertification->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Certification"]), $oDoctorCertification, false);
        
        $this->urlComponents(config("businesslogic")[12]['menu'][1], $oResponse, config("businesslogic")[12]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DoctorCertification  $DoctorCertification
     * @return \Illuminate\Http\Response
     */
    public function show($certification_id)
    {
        $oCertification = DoctorCertification::with(['createdBy','updatedBy','deletedBy','countryCode'])->findOrFail($certification_id);
        
        if (!Gate::allows('doctor-certification-show',$oCertification))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Certification"]), $oCertification, false);
        
        $this->urlComponents(config("businesslogic")[12]['menu'][2], $oResponse, config("businesslogic")[12]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DoctorCertification  $DoctorCertification
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorCertification $DoctorCertification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DoctorCertification  $DoctorCertification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'title'         => 'required|string|max:50|min:3|unique:doctor_certifications,title,'.$id.',id,doctor_id,'.$oInput['doctor_id'],
            'completed_year'=> 'required|date',
            'institute'     => 'required|string|max:150|min:3',
            'country_code'  => 'required|string|max:3',
            'doctor_id'     => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDate = DoctorCertification::where('doctor_id',$oInput['doctor_id'])->where('id','!=',$id)->where('title',$oInput['title'])->first();
        if($oDate){
            abort(400,__('Certification title already entered'));
        }
        $oDoctorCertification = DoctorCertification::with(['createdBy','updatedBy','deletedBy','countryCode'])->findOrFail($id);
        
        if (!Gate::allows('doctor-certification-update',$oDoctorCertification))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorCertification = $oDoctorCertification->update([
            'doctor_id'     => $oInput['doctor_id'],
            'title'         => $oInput['title'],
            'completed_year'=> $oInput['completed_year'],
            'institute'     => $oInput['institute'],
            'country_code'  => $oInput['country_code'],
            'updated_by'    =>  Auth::user()->id,
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorCertification = DoctorCertification::with(['createdBy','updatedBy','deletedBy','countryCode'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Certification"]), $oDoctorCertification, false);
        
        $this->urlComponents(config("businesslogic")[12]['menu'][3], $oResponse, config("businesslogic")[12]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_certifications,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allDoctorCertification = DoctorCertification::findOrFail($aIds);
        
        foreach($allDoctorCertification as $oRow)
            if (!Gate::allows('doctor-certification-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctorCertification = DoctorCertification::find($id);
                if($oDoctorCertification){
                    $oDoctorCertification->update(['deleted_by' => Auth::user()->id]);
                    $oDoctorCertification->delete();
                }
            }
        }else{
            $oDoctorCertification = DoctorCertification::findOrFail($aIds);
        
            $oDoctorCertification->update(['deleted_by' => Auth::user()->id]);
            $oDoctorCertification->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Certification"]));
        $this->urlComponents(config("businesslogic")[12]['menu'][4], $oResponse, config("businesslogic")[12]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$doctor_id)
    {
        if (!Gate::allows('doctor-certification-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        $oInput = $request->all();
        $doctor_id = decrypt($doctor_id);
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorCertification::where('doctor_id',$doctor_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy','countryCode']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"title",$oQb);
        $oQb = QB::whereLike($oInput,"institute",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        $oQb = QB::whereBetween($oInput,"completed_year",$oQb);

        $oCertification = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Certification"]), $oCertification, false);
        
        $this->urlComponents(config("businesslogic")[12]['menu'][5], $oResponse, config("businesslogic")[12]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_certifications,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allDoctorCertification= DoctorCertification::onlyTrashed()->findOrFail($aIds);
        
        foreach($allDoctorCertification as $oRow)
            if (!Gate::allows('doctor-certification-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oDoctorCertification = DoctorCertification::onlyTrashed()->find($id);
                if($oDoctorCertification){
                    $oDoctorCertification->restore();
                }
            }
        }else{
            $oDoctorCertification = DoctorCertification::onlyTrashed()->findOrFail($aIds);
            $oDoctorCertification->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Certification"]));
        
        $this->urlComponents(config("businesslogic")[12]['menu'][6], $oResponse, config("businesslogic")[12]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oDoctorCertification = DoctorCertification::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('doctor-certification-delete',$oDoctorCertification))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorCertification->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Certification"]));
        
        $this->urlComponents(config("businesslogic")[12]['menu'][7], $oResponse, config("businesslogic")[12]['title']);
        
        return $oResponse;
    }
}
