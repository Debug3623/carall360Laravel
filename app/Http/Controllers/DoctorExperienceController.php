<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\DoctorExperience;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DoctorExperienceController extends Controller
{
    use \App\Traits\WebServicesDoc;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request, $doctor_id)
    {
        if (!Gate::allows('doctor-experience-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $doctor_id = decrypt($doctor_id);
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorExperience::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"designation",$oQb);
        $oQb = QB::whereLike($oInput,"institute",$oQb);
        $oQb = QB::whereBetween($oInput, "year_from", $oQb);
        $oQb = QB::whereBetween($oInput, "year_to", $oQb);
        $oQb = QB::where($oInput,"is_current",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);
        
        $oExperience = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Experience"]), $oExperience, false);
        $this->urlComponents(config("businesslogic")[11]['menu'][0], $oResponse, config("businesslogic")[11]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('doctor-experience-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'designation'   => 'required|string|max:50|min:3',
            'institute'     => 'required|string|max:150|min:3',
            'year_from'     => 'required|date',
            'year_to'       => 'present|nullable|date|after_or_equal:year_from',
            'is_current'    => 'required|in:0,1,2',
            'description'   => 'present|nullable|string',
            'doctor_id'     => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);

        $oDoctorExperience = DoctorExperience::create([
            'doctor_id'     => $oInput['doctor_id'],
            'designation'   => $oInput['designation'],
            'institute'     => $oInput['institute'],
            'year_from'     => $oInput['year_from'],
            'year_to'       => $oInput['year_to'],
            'is_current'    => $oInput['is_current'],
            'description'=> $oInput['description'],
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'created_at'=> Carbon::now()->toDateTimeString(),
            'updated_at'=> Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorExperience= DoctorExperience::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oDoctorExperience->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Experience"]), $oDoctorExperience, false);
        
        $this->urlComponents(config("businesslogic")[11]['menu'][1], $oResponse, config("businesslogic")[11]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DoctorExperience  $DoctorExperience
     * @return \Illuminate\Http\Response
     */
    public function show($experience_id)
    {
        $oExperience = DoctorExperience::with(['createdBy','updatedBy','deletedBy'])->findOrFail($experience_id);

        if (!Gate::allows('doctor-experience-show',$oExperience))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Experience"]), $oExperience, false);
        
        $this->urlComponents(config("businesslogic")[11]['menu'][2], $oResponse, config("businesslogic")[11]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DoctorExperience  $DoctorExperience
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorExperience $DoctorExperience)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DoctorExperience  $DoctorExperience
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'designation'   => 'required|string|max:50|min:3',
            'institute'     => 'required|string|max:150|min:3',
            'year_from'     => 'required|date',
            'year_to'       => 'present|nullable|date|after_or_equal:year_from',
            'is_current'    => 'required|in:0,1,2',
            'description'   => 'present|nullable|string',
            'doctor_id'     => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);
        $oDoctorExperience = DoctorExperience::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('doctor-experience-update',$oDoctorExperience))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorExperience = $oDoctorExperience->update([
            'doctor_id'     => $oInput['doctor_id'],
            'designation'   => $oInput['designation'],
            'institute'     => $oInput['institute'],
            'year_from'     => $oInput['year_from'],
            'year_to'       => $oInput['year_to'],
            'is_current'    => $oInput['is_current'],
            'description'   => $oInput['description'],
            'updated_by'    =>  Auth::user()->id,
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorExperience = DoctorExperience::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Experience"]), $oDoctorExperience, false);
        
        $this->urlComponents(config("businesslogic")[11]['menu'][3], $oResponse, config("businesslogic")[11]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_experiences,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allDoctorExperience = DoctorExperience::findOrFail($aIds);
        foreach($allDoctorExperience as $oRow)
            if (!Gate::allows('doctor-experience-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctorExperience = DoctorExperience::find($id);
                if($oDoctorExperience){
                    $oDoctorExperience->update(['deleted_by' => Auth::user()->id]);
                    $oDoctorExperience->delete();
                }
            }
        }else{
            $oDoctorExperience = DoctorExperience::findOrFail($aIds);
        
            $oDoctorExperience->update(['deleted_by' => Auth::user()->id]);
            $oDoctorExperience->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Experience"]));
        $this->urlComponents(config("businesslogic")[11]['menu'][4], $oResponse, config("businesslogic")[11]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$doctor_id)
    {
        $oInput = $request->all();
        $doctor_id = decrypt($doctor_id);
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        if (!Gate::allows('doctor-experience-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oQb = DoctorExperience::where('doctor_id',$doctor_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"designation",$oQb);
        $oQb = QB::whereLike($oInput,"institute",$oQb);
        $oQb = QB::whereBetween($oInput, "year_from", $oQb);
        $oQb = QB::whereBetween($oInput, "year_to", $oQb);
        $oQb = QB::where($oInput,"is_current",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);

        $oExperience = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Experience"]), $oExperience, false);
        
        $this->urlComponents(config("businesslogic")[11]['menu'][5], $oResponse, config("businesslogic")[11]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_experiences,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allDoctorExperience =  DoctorExperience::onlyTrashed()->findOrFail($aIds);
        
        foreach($allDoctorExperience as $oRow)
            if (!Gate::allows('doctor-experience-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oDoctorExperience = DoctorExperience::onlyTrashed()->find($id);
                if($oDoctorExperience){
                    $oDoctorExperience->restore();
                }
            }
        }else{
            $oDoctorExperience = DoctorExperience::onlyTrashed()->findOrFail($aIds);
            $oDoctorExperience->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Experience"]));
        
        $this->urlComponents(config("businesslogic")[11]['menu'][6], $oResponse, config("businesslogic")[11]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oDoctorExperience = DoctorExperience::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('doctor-experience-delete',$oDoctorExperience))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
    
        $oDoctorExperience->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Experience"]));
        
        $this->urlComponents(config("businesslogic")[11]['menu'][7], $oResponse, config("businesslogic")[11]['title']);
        
        return $oResponse;
    }
}
