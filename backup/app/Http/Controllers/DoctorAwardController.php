<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\DoctorAward;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DoctorAwardController extends Controller
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
        if (!Gate::allows('doctor-award-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorAward::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"year",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);
        
        $oAwards = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Awards"]), $oAwards, false);
        $this->urlComponents(config("businesslogic")[10]['menu'][0], $oResponse, config("businesslogic")[10]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('doctor-award-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'name'        => 'required|string|max:50|min:3',
            'year'        => 'required|date',
            'description' => 'present|string|nullable',
            'doctor_id'   => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);

        $oDoctorAward = DoctorAward::create([
            'doctor_id'  => $oInput['doctor_id'],
            'name'       => $oInput['name'],
            'year'       => $oInput['year'],
            'description'=> $oInput['description'],
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'created_at'=> Carbon::now()->toDateTimeString(),
            'updated_at'=> Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorAward= DoctorAward::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oDoctorAward->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Award"]), $oDoctorAward, false);
        
        $this->urlComponents(config("businesslogic")[10]['menu'][1], $oResponse, config("businesslogic")[10]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DoctorAward  $DoctorAward
     * @return \Illuminate\Http\Response
     */
    public function show($award_id)
    {
        $oAward = DoctorAward::with(['createdBy','updatedBy','deletedBy'])->findOrFail($award_id);
        
        if (!Gate::allows('doctor-award-show',$oAward))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Award"]), $oAward, false);
        
        $this->urlComponents(config("businesslogic")[10]['menu'][2], $oResponse, config("businesslogic")[10]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DoctorAward  $DoctorAward
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorAward $DoctorAward)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DoctorAward  $DoctorAward
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'name'        => 'required|string|max:50|min:3',
            'year'        => 'required|date',
            'description' => 'present|string|nullable',
            'doctor_id'   => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);

        $oDoctorAward = DoctorAward::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('doctor-award-update',$oDoctorAward))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorAward = $oDoctorAward->update([
            'doctor_id'  => $oInput['doctor_id'],
            'name'       => $oInput['name'],
            'year'       => $oInput['year'],
            'description'=> $oInput['description'],
            'updated_by'=>  Auth::user()->id,
            'updated_at'=>  Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorAward = DoctorAward::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Award"]), $oDoctorAward, false);
        
        $this->urlComponents(config("businesslogic")[10]['menu'][3], $oResponse, config("businesslogic")[10]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_awards,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allDoctorAward = DoctorAward::findOrFail($aIds);
        
        foreach($allDoctorAward as $oRow)
            if (!Gate::allows('doctor-award-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctorAward = DoctorAward::find($id);
                if($oDoctorAward){
                    $oDoctorAward->update(['deleted_by' => Auth::user()->id]);
                    $oDoctorAward->delete();
                }
            }
        }else{
            $oDoctorAward = DoctorAward::findOrFail($aIds);
        
            $oDoctorAward->update(['deleted_by' => Auth::user()->id]);
            $oDoctorAward->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Award"]));
        $this->urlComponents(config("businesslogic")[10]['menu'][4], $oResponse, config("businesslogic")[10]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$doctor_id)
    {
        if (!Gate::allows('doctor-award-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $doctor_id = decrypt($doctor_id);
        $oInput = $request->all();
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorAward::where('doctor_id',$doctor_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"year",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);

        $oAward = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Award"]), $oAward, false);
        
        $this->urlComponents(config("businesslogic")[10]['menu'][5], $oResponse, config("businesslogic")[10]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_awards,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allDoctorAward= DoctorAward::onlyTrashed()->findOrFail($aIds);
        
        foreach($allDoctorAward as $oRow)
            if (!Gate::allows('doctor-award-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oDoctorAward = DoctorAward::onlyTrashed()->find($id);
                if($oDoctorAward){
                    $oDoctorAward->restore();
                }
            }
        }else{
            $oDoctorAward = DoctorAward::onlyTrashed()->findOrFail($aIds);
            $oDoctorAward->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Award"]));
        
        $this->urlComponents(config("businesslogic")[10]['menu'][6], $oResponse, config("businesslogic")[10]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oDoctorAward = DoctorAward::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('doctor-award-delete',$oDoctorAward))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorAward->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Award"]));
        
        $this->urlComponents(config("businesslogic")[10]['menu'][7], $oResponse, config("businesslogic")[10]['title']);
        
        return $oResponse;
    }
}
