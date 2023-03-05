<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Helpers\QB;
use App\PatientRiskfactor;
use App\Patient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PatientRiskFactorController extends Controller
{
    use \App\Traits\WebServicesDoc;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request, $patient_id)
    {
        $oInput = $request->all();
        $patient_id = decrypt($patient_id);
        if (!Gate::allows('riskfactor-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oQb = PatientRiskfactor::where('patient_id',$patient_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::where($oInput,"risk_type",$oQb);
        
        $oRiskFactor = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Patient Risk Factorss"]), $oRiskFactor, false);
        $this->urlComponents(config("businesslogic")[25]['menu'][0], $oResponse, config("businesslogic")[25]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('riskfactor-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['patient_id'] = decrypt($oInput['patient_id']);
        $oValidator = Validator::make($oInput,[
            'name'     => 'required|string|max:50|min:3',
            'risk_type'=> 'required|in:allergy,risk_factor',
            'patient_id'=> 'required|exists:patients,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oRiskFactor = PatientRiskfactor::create([
            'patient_id'    => $oInput['patient_id'],
            'name'          => $oInput['name'],
            'risk_type'     => $oInput['risk_type'],
            'created_by'    => Auth::user()->id,
            'updated_by'    => Auth::user()->id,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oRiskFactor= PatientRiskfactor::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oRiskFactor->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Patient Risk Factors"]), $oRiskFactor, false);
        
        $this->urlComponents(config("businesslogic")[25]['menu'][1], $oResponse, config("businesslogic")[25]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PatientRiskfactor  $PatientRiskfactor
     * @return \Illuminate\Http\Response
     */
    public function show($PatientRiskfactor_id)
    {
        $oRiskFactor = PatientRiskfactor::with(['createdBy','updatedBy','deletedBy'])->findOrFail($PatientRiskfactor_id);
        
        if (!Gate::allows('riskfactor-show',$oRiskFactor))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Patient Risk Factors"]), $oRiskFactor, false);
        
        $this->urlComponents(config("businesslogic")[25]['menu'][2], $oResponse, config("businesslogic")[25]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PatientRiskfactor  $PatientRiskfactor
     * @return \Illuminate\Http\Response
     */
    public function edit(PatientRiskfactor $PatientRiskfactor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PatientRiskfactor  $PatientRiskfactor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['patient_id'] = decrypt($oInput['patient_id']);
        $oValidator = Validator::make($oInput,[
            'name'     => 'required|string|max:50|min:3',
            'risk_type'=> 'required|in:allergy,risk_factor',
            'patient_id'=> 'required|exists:patients,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oRiskFactor = PatientRiskfactor::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('riskfactor-update',$oRiskFactor))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oRiskFactor = $oRiskFactor->update([
            'patient_id'    => $oInput['patient_id'],
            'name'          => $oInput['name'],
            'risk_type'     => $oInput['risk_type'],
            'updated_by'    => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oRiskFactor = PatientRiskfactor::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Patient Risk Factors"]), $oRiskFactor, false);
        
        $this->urlComponents(config("businesslogic")[25]['menu'][3], $oResponse, config("businesslogic")[25]['title']);
        
        return $oResponse;
    }

    // Soft Delete patients 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:patient_riskfactors,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allPatientRiskfactor = PatientRiskfactor::findOrFail($aIds);
        
        foreach($allPatientRiskfactor as $oRow)
            if (!Gate::allows('riskfactor-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oRiskFactor = PatientRiskfactor::find($id);
                if($oRiskFactor){
                    $oRiskFactor->update(['deleted_by' => Auth::user()->id]);
                    $oRiskFactor->delete();
                }
            }
        }else{
            $oRiskFactor = PatientRiskfactor::findOrFail($aIds);
        
            $oRiskFactor->update(['deleted_by' => Auth::user()->id]);
            $oRiskFactor->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Patient Risk Factors"]));
        $this->urlComponents(config("businesslogic")[25]['menu'][4], $oResponse, config("businesslogic")[25]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$patient_id)
    {
        $patient_id = decrypt($patient_id);
        if (!Gate::allows('riskfactor-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        $oInput = $request->all();
        
        $oQb = PatientRiskfactor::where('patient_id',$patient_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::where($oInput,"risk_type",$oQb);

        $oRiskFactor = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Patient Risk Factors"]), $oRiskFactor, false);
        
        $this->urlComponents(config("businesslogic")[25]['menu'][5], $oResponse, config("businesslogic")[25]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:patient_riskfactors,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allPatientRiskfactor= PatientRiskfactor::onlyTrashed()->findOrFail($aIds);
        
        foreach($allPatientRiskfactor as $oRow)
            if (!Gate::allows('riskfactor-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oRiskFactor = PatientRiskfactor::onlyTrashed()->find($id);
                if($oRiskFactor){
                    $oRiskFactor->restore();
                }
            }
        }else{
            $oRiskFactor = PatientRiskfactor::onlyTrashed()->findOrFail($aIds);
            $oRiskFactor->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Patient Risk Factors"]));
        
        $this->urlComponents(config("businesslogic")[25]['menu'][6], $oResponse, config("businesslogic")[25]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oRiskFactor = PatientRiskfactor::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('riskfactor-delete',$oRiskFactor))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oRiskFactor->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Patient Risk Factors"]));
        
        $this->urlComponents(config("businesslogic")[25]['menu'][7], $oResponse, config("businesslogic")[25]['title']);
        
        return $oResponse;
    }
    public function patientRiskFactor(Request $request, $patient_id)
    {
        $oInput = $request->all();
        $patient_id = decrypt($patient_id); 
        $oQb = PatientRiskfactor::where('patient_id',$patient_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::where($oInput,"risk_type",$oQb);
        
        $oRiskFactor = $oQb->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Patient Risk Factorss"]), $oRiskFactor, false);
        return $oResponse;
    }
}
