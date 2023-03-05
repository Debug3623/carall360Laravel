<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Appointment;
use App\Diagnostic;
use Carbon\Carbon;
use App\Helpers\QB;
use App\PrescriptionDiagnostic;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DiagnosticPrescriptionController extends Controller
{
    use \App\Traits\WebServicesDoc;

    public function index(Request $request)
    {
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        if (!Gate::allows('diagnostic-prescription-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oQb = PrescriptionDiagnostic::orderByDesc('updated_at')->with(['appointmentId','diagnosticId','patientId','doctorId','createdBy','updatedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;
            
            $oQb = $oQb->whereHas('doctorId', function ($q) use($oInput) {
                $q->where('organization_id', $oInput['organization_id']);
            });
        }elseif ($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;    
        }elseif ($oAuth->isPatient()) {
            $oInput['patient_id'] = $oAuth->patient_id;    
        }
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"patient_id",$oQb);
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::where($oInput,"diagnostic_id",$oQb);
        $oQb = QB::whereLike($oInput,"diagnostic_name",$oQb);
        
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date',
                'date_to'         => 'date|after_or_equal:date_from',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereBetween("result_date",[$oInput['date_from'],$oInput['date_to']]);
        }
        // $oQb = QB::whereBetween($oInput,"result_date",$oQb);

        $oPrescriptions = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Diagnostic Prescriptions"]), $oPrescriptions, false);
        $this->urlComponents(config("businesslogic")[31]['menu'][0], $oResponse, config("businesslogic")[31]['title']);
        return $oResponse;

    }
    public function store(Request $request){
        $oInput = $request->all();
        
        if (!Gate::allows('diagnostic-prescription-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oValidator = Validator::make($oInput,[
            'diagnostic_name'=> 'required|string|max:500',
            'diagnostic_id'  => 'nullable|exists:diagnostics,id',
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id'      => 'required|exists:doctors,id',
            'patient_id'     => 'required|exists:patients,id',
            'result_date'    => 'nullable|date|after:yesterday',
            'description'    => 'present|nullable',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oAppointment = Appointment::where('id',$oInput['appointment_id'])->where('patient_id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oAppointment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
        
        $oPrescription = PrescriptionDiagnostic::create([
            'diagnostic_name'=> $oInput['diagnostic_name'], 
            'diagnostic_id' => $oInput['diagnostic_id'], 
            'appointment_id'=> $oInput['appointment_id'], 
            'doctor_id'     => $oInput['doctor_id'], 
            'patient_id'    => $oInput['patient_id'],
            'result_date'   => $oInput['result_date'],
            'description'   => $oInput['description'],
            'created_by'    => Auth::user()->id,
            'updated_by'    => Auth::user()->id,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        
        $oPrescription = PrescriptionDiagnostic::with(['appointmentId','diagnosticId','patientId','doctorId','createdBy','updatedBy'])->findOrFail($oPrescription->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Diagnostic Prescription"]), $oPrescription, false);
        $this->urlComponents(config("businesslogic")[31]['menu'][1], $oResponse, config("businesslogic")[31]['title']);
        return $oResponse;
    }
    public function show($id)
    {
        $oPrescription = PrescriptionDiagnostic::orderByDesc('updated_at')->with(['appointmentId','diagnosticId','patientId','doctorId','createdBy','updatedBy'])->findOrFail($id);
        
        if (!Gate::allows('diagnostic-prescription-show',$oPrescription))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Diagnostic Prescription"]), $oPrescription, false);
        
        $this->urlComponents(config("businesslogic")[31]['menu'][2], $oResponse, config("businesslogic")[31]['title']);
        
        return $oResponse;
    }
    public function update(Request $request, $id)
    {
        $oInput = $request->all();

        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oValidator = Validator::make($oInput,[
            'diagnostic_name'=> 'required|string|max:500',
            'diagnostic_id'  => 'nullable|exists:diagnostics,id',
            'appointment_id'=> 'required|exists:appointments,id',
            'doctor_id'     => 'required|exists:doctors,id',
            'patient_id'    => 'required|exists:patients,id',
            'result_date'   => 'required|date|after:yesterday',
            'description'   => 'present|nullable',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oAppointment = Appointment::where('id',$oInput['appointment_id'])->where('patient_id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oAppointment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
    
        $oPrescription = PrescriptionDiagnostic::findOrFail($id); 
        
        if (!Gate::allows('diagnostic-prescription-update',$oPrescription))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oPrescriptionUpdate= $oPrescription->update([
            'diagnostic_name'=> $oInput['diagnostic_name'],
            'diagnostic_id' => $oInput['diagnostic_id'], 
            'appointment_id'=> $oInput['appointment_id'], 
            'doctor_id'     => $oInput['doctor_id'], 
            'patient_id'    => $oInput['patient_id'],
            'result_date'   => $oInput['result_date'],
            'description'   => $oInput['description'],
            'updated_by'    => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
            
        ]);
        $oPrescription = PrescriptionDiagnostic::with(['appointmentId','diagnosticId','patientId','doctorId','createdBy','updatedBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Diagnostic Prescription"]), $oPrescription, false);
        
        $this->urlComponents(config("businesslogic")[31]['menu'][3], $oResponse, config("businesslogic")[31]['title']);
        
        return $oResponse;
    }
    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:prescription_diagnostics,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allPrescription = PrescriptionDiagnostic::findOrFail($aIds);
        
        foreach($allPrescription as $oRow)
            if (!Gate::allows('diagnostic-prescription-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oPrescription = PrescriptionDiagnostic::find($id);
                if($oPrescription){
                    $oPrescription->delete();
                }
            }
        }else{
            $oPrescription = PrescriptionDiagnostic::findOrFail($aIds);
            $oPrescription->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Diagnostic Prescription"]));
        $this->urlComponents(config("businesslogic")[31]['menu'][4], $oResponse, config("businesslogic")[31]['title']);
        
        return $oResponse;
    }
    public function allDiagnostics(Request $request)
    {
        $oInput = $request->all();
        
        $oQb = Diagnostic::where('is_active',1)->orderByDesc('updated_at');
        $oQb = QB::whereLike($oInput,"name",$oQb);
        
        $oDiagnostic = $oQb->select('id','name','preinstruction')->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Diagnostics"]), $oDiagnostic, false);
        $this->urlComponents(config("businesslogic")[31]['menu'][5], $oResponse, config("businesslogic")[31]['title']);
        
        return $oResponse;
    }
    public function allDiagnosticsPrescription(Request $request)
    {
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oQb = PrescriptionDiagnostic::orderByDesc('updated_at')->with(['appointmentId','diagnosticId','patientId','doctorId','createdBy','updatedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;
            $oQb = $oQb->whereHas('doctorId', function ($q) use($oInput) {
                $q->where('organization_id', $oInput['organization_id']);
            });
        }elseif ($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;    
        }elseif ($oAuth->isPatient()) {
            $oInput['patient_id'] = $oAuth->patient_id;    
        }
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"patient_id",$oQb);
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::where($oInput,"diagnostic_id",$oQb);
        $oQb = QB::whereLike($oInput,"diagnostic_name",$oQb);
        $oQb = QB::whereBetween($oInput,"result_date",$oQb);

        $oPrescriptions = $oQb->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Diagnostic Prescriptions"]), $oPrescriptions, false);
        return $oResponse;

    }
}
