<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Exception;
use App\Center;
use App\Appointment;
use App\AppointmentPayment as AP;
use App\AppointmentPayment;
use App\Doctor;
use App\DoctorTreatment;
use DB;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Mail;
 
use App\Mail\GeneralAlert;
use App\Patient;
use App\PatientRiskfactor;
use Twilio\Jwt\AccessToken;
use Twilio\Rest\Client;
use Twilio\Jwt\Grants\VideoGrant;
use App\TimeSlot;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    use \App\Traits\WebServicesDoc;
    //use \App\Traits\VideoConsultation;    
    use \App\Traits\JazzCashPaymentGateway;
    use \App\Traits\EasypaisaPaymentGateway; 
    private $sid;
    private $token;
    private $key;
    private $secret;




    public function index(Request $request)
    {
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oQb = Appointment::orderByDesc('updated_at')->with(['slotId','centerId','MedicalRecord','AppointmentPayment','refund','createdBy','updatedBy','deletedBy','restoredBy']);

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
        if(isset($oInput['type'])){
            $today_date = Carbon::now()->toDateString();
            
            if($oInput['type'] == "upcoming"){
                $oQb = $oQb->where("appointment_date",">",$today_date);        
            }
            if($oInput['type'] == "today"){
                $oQb = $oQb->where("appointment_date","=",$today_date);
            }
            if($oInput['type'] == "previous"){
                $oQb = $oQb->where("appointment_date","<",$today_date);
            }
        }
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date',
                'date_to'         => 'date|after_or_equal:date_from',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereBetween("appointment_date",[$oInput['date_from'],$oInput['date_to']]);
        }
        $oQb = QB::filters($oInput,$oQb);
        
        $oQb = QB::whereLike($oInput,"lead_from",$oQb);
        $oQb = QB::where($oInput,"appointment_type",$oQb);
        $oQb = QB::where($oInput,"status",$oQb);
        $oQb = QB::whereLike($oInput,"appointment_fee",$oQb);
        $oQb = QB::where($oInput,"paid_status",$oQb);
        $oQb = QB::whereLike($oInput,"original_free",$oQb);
        $oQb = QB::whereLike($oInput,"tele_url",$oQb);
        $oQb = QB::where($oInput,"treatment_id",$oQb);
        $oQb = QB::where($oInput,"center_id",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"patient_id",$oQb);
        $oQb = QB::where($oInput,"slot_id",$oQb);
        $oQb = QB::whereLike($oInput,"reference_no",$oQb);
        $oQb = QB::where($oInput,"is_settled",$oQb);
        $oQb = QB::where($oInput,"is_reviewed",$oQb);
        $oQb = QB::whereBetween($oInput,"payment_timelimit",$oQb);
        
        $oAppointments = $oQb->paginate(10);
        foreach ($oAppointments as $appointment) {
            $patient      = Patient::where('id',$appointment->patient_id)->first();
            $doctor       = Doctor::where('id',$appointment->doctor_id)->first();
            $treatment_id = DoctorTreatment::where('id',$appointment->treatment_id)->withTrashed()->select('id','treatment_name')->first();
            $oPatientUser = User::where('patient_id',$appointment->patient_id)->first();
            $oDoctorUser  = User::where('doctor_id',$appointment->doctor_id)->whereNull('patient_id')->where('organization_id',$doctor->organization_id)->first();
            
            if($oPatientUser){
                $patient['image'] = (count($oPatientUser->profilePic)>0)? config("app")['url'].$oPatientUser->profilePic[0]->url:null;
            }
            if($oDoctorUser){
                $doctor['image'] = (count($oDoctorUser->profilePic)>0)? config("app")['url'].$oDoctorUser->profilePic[0]->url:null;
            }
            $appointment['patient_id'] = $patient;
            $appointment['doctor_id'] = $doctor;
            $appointment['treatment_id'] = $treatment_id;
        }
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Appointments"]), $oAppointments, false);
        $this->urlComponents(config("businesslogic")[26]['menu'][0], $oResponse, config("businesslogic")[26]['title']);
        return $oResponse;

    }
    public function store(Request $request){
        $oInput = $request->all();
        
        if (!Gate::allows('appointment-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oValidator = Validator::make($oInput,[
            'lead_from'     => 'required|in:doctor,careall,website,patient',
            'paid_status'   => 'required|in:paid,unpaid',
            'status'        => 'required|in:pending,approved,cancel_by_doctor,cancel_by_patient,no_show,done,ongoing,follow_up,reschedule,refund,auto_cancel',
            'type'          => 'required|in:online,physical',
            'fee'           => 'required|max:15|min:0',
            'discount_fee'  => 'required|max:15|min:0',
            'doctor_remarks'=> 'present|nullable|max:250',
            'date'          => 'required|date|after:yesterday',
            'treatment_id'  => 'present|nullable|exists:doctor_treatments,id',
            'center_id'     => 'present|nullable|exists:centers,id',
            'doctor_id'     => 'required|exists:doctors,id',
            'patient_id'    => 'required|exists:patients,id',
            'slot_id'       => 'required|exists:time_slots,id',
            'slot'          => 'required',
            'parent_id'     => 'present|nullable|exists:appointments,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oPatient = Patient::where('id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oPatient))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);

        $selectedDateTime = Carbon::createFromTimestamp(strtotime($oInput['date'] . $oInput['slot']))->toDateTimeString();    
        $currentDateTime = Carbon::now()->toDateTimeString();    
        if($currentDateTime >= $selectedDateTime){
            return responseBuilder()->error(__('message.schedule.previous'), 404, false);
        }
        $oAppointment = bookAppointment($oInput);
        
        $oAppointment = Appointment::with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($oAppointment->id);
        
        
          //$sTransRef = strtoupper($sTransRef);
          
          if(!empty($oAppointment->employee_no))
          {
        $aAppPaymentData = array();
        $aAppPaymentData['appointment_id'] = $oAppointment->id;
        $aAppPaymentData['payment_method'] = 'bank';
        // $aAppPaymentData['transaction_ref'] = $sTransRef;
           $aAppPaymentData['evidence_url'] = 'appointment_payments/ea5d2f1c4608232e07d3aa3d998e5135/jPgLtJssqhhXB9UmLUXbXNtHnlfrBPmYFoukDDij.png';
        $aAppPaymentData['status'] = 'init';
        $aAppPaymentData['file_name'] = ' 45.PNG';
        $aAppPaymentData['status'] = 'pending';
        $aAppPaymentData['pay_date'] = \Carbon\Carbon::now()->toDateTimeString();
        $aAppPaymentData['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $bAppPayment = AP::create($aAppPaymentData);
}
        //dd($bAppPayment);
    
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Appointment"]), $oAppointment, false);
        $this->urlComponents(config("businesslogic")[26]['menu'][1], $oResponse, config("businesslogic")[26]['title']);
        return $oResponse;
    }
    public function show($id)
    {

        $oAuth = Auth::user();

        $oQb = Appointment::orderByDesc('updated_at')->with(['slotId','centerId','treatmentId','MedicalRecord','refund','AppointmentPayment','createdBy','updatedBy','deletedBy','restoredBy','Reviews']);
        
        if($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;
            $oQb = QB::where($oInput,"doctor_id",$oQb);  
        
        }elseif($oAuth->isPatient()) {
            $oInput['patient_id'] = $oAuth->patient_id;
            $oQb = QB::where($oInput,"patient_id",$oQb);
        }
        $oAppointment = $oQb->findOrFail($id);
        
        if (!Gate::allows('appointment-show',$oAppointment))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oAppointment = AppointmentDetail($oAppointment);
        
        
        $oAppointmentPayment = AppointmentPayment::where('appointment_id',$id)->whereIn('status',['awaiting_confirmation','pending'])->first();
            
        if(isset($oAppointmentPayment)){
            $oAppointment['payment_status'] = "waiting";
        }else{
            $oAppointment['payment_status'] = "no_status";
        }
        if($oAppointment->status != 'pending' || $oAppointment->status != 'follow_up'){
            $oAppointment['payment_status'] = "no_status";
        }
        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Appointment"]), $oAppointment, false);
        
        $this->urlComponents(config("businesslogic")[26]['menu'][2], $oResponse, config("businesslogic")[26]['title']);
        
        return $oResponse;
    }
    
    public function update(Request $request, $id)
    {
        $oInput = $request->all();

        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        $oValidator = Validator::make($oInput,[
            'lead_from'     => 'required|in:doctor,careall,website,patient',
            'paid_status'   => 'required|in:paid,unpaid',
            'status'        => 'required|in:pending,approved,cancel_by_doctor,cancel_by_patient,no_show,done,ongoing,follow_up,reschedule,refund,auto_cancel',
            'type'          => 'required|in:online,physical',
            'fee'           => 'required|max:500|min:0',
            'discount_fee'  => 'required|max:500|min:0',
            'doctor_remarks'=> 'present|nullable|max:250',
            'date'          => 'required|date|after:yesterday',
            'treatment_id'  => 'present|nullable|exists:doctor_treatments,id',
            'center_id'     => 'present|nullable|exists:centers,id',
            'doctor_id'     => 'required|exists:doctors,id',
            'patient_id'    => 'required|exists:patients,id',
            'slot_id'       => 'required|exists:time_slots,id',
            'slot'          => 'required',
            'parent_id'     => 'present|nullable|exists:appointments,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oPatient = Patient::where('id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        
        if(!isset($oPatient))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
            
        $selectedDateTime = Carbon::createFromTimestamp(strtotime($oInput['date'] . $oInput['slot']))->toDateTimeString();    
        $currentDateTime = Carbon::now()->toDateTimeString();    
        if($currentDateTime >= $selectedDateTime){
            return responseBuilder()->error(__('message.schedule.previous'), 404, false);
        }
        $oAppointment = Appointment::findOrFail($id); 
        $oldStatus    = $oAppointment->status;
        $oldAppointment    = $oAppointment;

        if(in_array($oInput['status'],Appointment::$UPDATEDEDSTATUS)){
            
            if(in_array($oldStatus,Appointment::$APPROVEDSTATUS)){
                return responseBuilder()->error(__('This appointment can not be updated. You can create new one.'), 404, false);
            }
        }
        if($oldStatus == 'done')
            return responseBuilder()->error(__('This appointment can not be updated. You can create new one.'), 404, false);

        $current_time = Carbon::now()->toDateTimeString();
        $appointment_time = Carbon::createFromTimestamp(strtotime($oInput['date'] . $oInput['slot']))->toDateTimeString();
        $timestamp = (strtotime($current_time)+strtotime($appointment_time))/2 ;
        $payment_timelimit = date('Y-m-d H:i:s',$timestamp);
       
        if (!Gate::allows('appointment-update',$oAppointment))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $appointment = $oAppointment->update([
            "lead_from"         => $oInput['lead_from'],
            "treatment_id"      => $oInput['treatment_id'],
            "center_id"         => $oInput['center_id'],
            "original_fee"      => $oInput['fee'],
            "paid_status"       => isset($oInput['paid_status'])?$oInput['paid_status']:'unpaid',
            "status"            => isset($oInput['status'])?$oInput['status']:'pending',
            "doctor_remarks"    => isset($oInput['doctor_remarks'])?$oInput['doctor_remarks']:null,
            "parent_id"         => isset($oInput['parent_id'])?$oInput['parent_id']:null,
            "slot_id"           => $oInput['slot_id'],
            "patient_id"        => $oInput['patient_id'],
            "doctor_id"         => $oInput['doctor_id'],
            "appointment_type"  => $oInput['type'],
             "employee_no"  => $oInput['employee_no'],
            "appointment_fee"   => $oInput['discount_fee'],
            "payment_timelimit" => $payment_timelimit,
            "appointment_date"  => $oInput['date'],
            'updated_by'        =>  Auth::user()->id,
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
            
        ]);
        $oAppointment = Appointment::with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($id);
        
        appointmentStatusChange($oAppointment,$oldAppointment);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Appointment"]), $oAppointment, false);
        
        $this->urlComponents(config("businesslogic")[26]['menu'][3], $oResponse, config("businesslogic")[26]['title']);
        
        return $oResponse;
    }
    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:appointments,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allAppointment = Appointment::findOrFail($aIds);
        
        foreach($allAppointment as $oRow)
            if (!Gate::allows('appointment-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oAppointment = Appointment::find($id);
                if($oAppointment){
                    $oAppointment->update(['deleted_by' => Auth::user()->id]);
                    $oAppointment->delete();
                }
            }
        }else{
            $oAppointment = Appointment::findOrFail($aIds);
        
            $oAppointment->update(['deleted_by' => Auth::user()->id]);
            $oAppointment->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Appointment"]));
        $this->urlComponents(config("businesslogic")[26]['menu'][4], $oResponse, config("businesslogic")[26]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request)
    {
        $oInput = $request->all();
        
        if (!Gate::allows('appointment-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oAuth = Auth::user();
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        
        $oQb = Appointment::onlyTrashed()->orderByDesc('deleted_at')->with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy']);

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
        if(isset($oInput['type'])){
            $today_date = Carbon::now()->toDateString();
            
            if($oInput['type'] == "upcoming"){
                $oQb = $oQb->where("appointment_date",">",$today_date);        
            }
            if($oInput['type'] == "today"){
                $oQb = $oQb->where("appointment_date","=",$today_date);
            }
            if($oInput['type'] == "previous"){
                $oQb = $oQb->where("appointment_date","<",$today_date);
            }
        }
        $oQb = QB::whereLike($oInput,"lead_from",$oQb);
        $oQb = QB::whereLike($oInput,"appointment_type",$oQb);
        $oQb = QB::whereLike($oInput,"status",$oQb);
        $oQb = QB::whereLike($oInput,"appointment_fee",$oQb);
        $oQb = QB::whereLike($oInput,"paid_status",$oQb);
        $oQb = QB::whereLike($oInput,"original_free",$oQb);
        $oQb = QB::whereLike($oInput,"tele_url",$oQb);
        $oQb = QB::where($oInput,"treatment_id",$oQb);
        $oQb = QB::where($oInput,"center_id",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"patient_id",$oQb);
        $oQb = QB::where($oInput,"slot_id",$oQb);
        $oQb = QB::where($oInput,"reference_no",$oQb);
        $oQb = QB::where($oInput,"is_settled",$oQb);
        $oQb = QB::where($oInput,"is_reviewed",$oQb);
        $oQb = QB::whereBetween($oInput,"appointment_date",$oQb);
        $oQb = QB::whereBetween($oInput,"payment_timelimit",$oQb);

        $oAppointment = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Appointments"]), $oAppointment, false);
        
        $this->urlComponents(config("businesslogic")[26]['menu'][5], $oResponse, config("businesslogic")[26]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:appointments,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allAppointment = Appointment::onlyTrashed()->findOrFail($aIds);
        
        foreach($allAppointment as $oRow)
            if (!Gate::allows('appointment-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oAppointment = Appointment::onlyTrashed()->find($id);
                $oAppointment->update(['restored_by' => Auth::user()->id]);
                if($oAppointment){
                    $oAppointment->restore();
                }
            }
        }else{
            $oAppointment = Appointment::onlyTrashed()->findOrFail($aIds);
            $oAppointment->update(['restored_by' => Auth::user()->id]);
            $oAppointment->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Appointment"]));
        
        $this->urlComponents(config("businesslogic")[26]['menu'][6], $oResponse, config("businesslogic")[26]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $appointment = Appointment::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('appointment-delete',$appointment))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $appointment->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Appointment"]));
        
        $this->urlComponents(config("businesslogic")[26]['menu'][7], $oResponse, config("businesslogic")[26]['title']);
        
        return $oResponse;
    }
    public function updateStatus(Request $request, $id){
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'status'        => 'required|in:pending,approved,cancel_by_doctor,cancel_by_patient,no_show,done,ongoing,follow_up,reschedule,refund,auto_cancel,awaiting_confirmation',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oAppointment   = Appointment::findOrFail($id); 
        $oldAppointment = $oAppointment;
        $oldStatus      = $oAppointment->status;
        $bNew           =   false;
        $bRefund        =   false;
        if ($oldStatus === 'done' && $oInput['status'] === 'done') {
            $oAppointment = Appointment::with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($id);
            $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Appointment Status"]), $oAppointment, false);
            $this->urlComponents(config("businesslogic")[26]['menu'][8], $oResponse, config("businesslogic")[26]['title']);
            return $oResponse;
        }
        if ($oldStatus === $oInput['status']) {
            return responseBuilder()->error(__('The selected appointment status is same as the Previous one. Select Different!'), 404, false);
        }
        if ($oldStatus === 'pending') {
            if(in_array($oInput['status'],Appointment::$NEGPENDING)){
                return responseBuilder()->error(__('The appointment status cannot be updated to the selected one.'), 404, false);
            } else {
                //Change status of appointment
                updateAppointmentStatus($oAppointment,$oInput,$oldStatus);
            }
        } else if ($oldStatus === 'approved') {
            if(in_array($oInput['status'],Appointment::$NEGAPPROVED)){
                return responseBuilder()->error(__('The appointment status cannot be updated to the selected one.'), 404, false);
            } else if($oInput['status'] === 'reschedule'){
                //Update Reschedule
                $oNewAppointment = rescheduleStatusUpdate($oAppointment,$oInput,$oldStatus);
                $this->onAppointmentApprove($oNewAppointment);
                $oAppointment   =   $oNewAppointment;
                $bNew    =   true;
                $oResponse = responseBuilder()->success(__('message.general.reschedule',["mod"=>"Appointment"]), $oNewAppointment, false);
            } else if($oInput['status'] === 'refund'){
                //Refund Code
                if ($oAppointment->paid_status == 'unpaid') {
                    return responseBuilder()->error(__('Could not Process your Request for an Unpaid Appointment'), 404, false);
                }
                $bRefund = refundStatusUpdate($oAppointment,$oInput,$oldStatus);
                if ($bRefund) {
                    $oAppointment = Appointment::with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($id);
                    return responseBuilder()->success(__('Your Request for Refund has been submitted. Team will respond to your Request soon!'), $oAppointment, false);
                }
                return responseBuilder()->error(__('Could not Process your Request'), 404, false);

            } else if($oInput['status'] === 'follow_up'){
                //Follow Up status - Paid type could be Free or Paid.
                //If type is Free - New appointment's status would be paid and the amount would be zero
                //For Paid Type - New Appointment's status would be unpaid and the amount will come from the input
                //change the status of previous appointment to Done
                $oNewAppointment = followUpStatusUpdate($oAppointment,$oInput,$oldStatus);
                $this->onAppointmentApprove($oNewAppointment);
                $oAppointment   =   $oNewAppointment;
                $bNew    =   true;
                $oResponse = responseBuilder()->success(__('message.general.followOn',["mod"=>"Appointment"]), $oNewAppointment, false);
            }  else {
                //Change status of appointment
                updateAppointmentStatus($oAppointment,$oInput,$oldStatus);
            }
        } else if ($oldStatus === 'cancel_by_doctor' || $oldStatus === 'cancel_by_patient' || $oldStatus === 'cancel_by_adminStaff') {
            if(in_array($oInput['status'],Appointment::$NEGCANCELBYDOCTOR) || in_array($oInput['status'],Appointment::$NEGCANCELBYPATIENT) || in_array($oInput['status'],Appointment::$NEGCANCELBYADMINSTAFF)){
                return responseBuilder()->error(__('The appointment status cannot be updated to the selected one.'), 404, false);
            } else if($oInput['status'] === 'reschedule'){
                //Update Reschedule
                $oNewAppointment = rescheduleStatusUpdate($oAppointment,$oInput,$oldStatus);
                $this->onAppointmentApprove($oNewAppointment);
                $oAppointment   =   $oNewAppointment;
                $bNew    =   true;
            } else if($oInput['status'] === 'refund'){
                //Refund Code
                if ($oAppointment->paid_status == 'unpaid') {
                    return responseBuilder()->error(__('Could not Process your Request for an Unpaid Appointment'), 404, false);
                }
                $bRefund = refundStatusUpdate($oAppointment,$oInput,$oldStatus);
                if ($bRefund) {
                    $oAppointment = Appointment::with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($id);
                    return responseBuilder()->success(__('Your Request for Refund has been submitted. Team will respond to your Request soon!'), $oAppointment, false);
                }
                return responseBuilder()->error(__('Could not Process your Request'), 404, false);

            }else {
                //Change status of appointment
                updateAppointmentStatus($oAppointment,$oInput,$oldStatus);
            }
        } else if ($oldStatus === 'no_show') {
            if(in_array($oInput['status'],Appointment::$NEGNOSHOW)){
                return responseBuilder()->error(__('The appointment status cannot be updated to the selected one.'), 404, false);
            } else if($oInput['status'] === 'reschedule'){
                //Update Reschedule
                $oNewAppointment = rescheduleStatusUpdate($oAppointment,$oInput,$oldStatus);
                $this->onAppointmentApprove($oNewAppointment);
                $oAppointment   =   $oNewAppointment;
                $bNew    =   true;
            } else if($oInput['status'] === 'refund'){
                //Refund Code
                if ($oAppointment->paid_status == 'unpaid') {
                    return responseBuilder()->error(__('Could not Process your Request for an Unpaid Appointment'), 404, false);
                }
                $bRefund = refundStatusUpdate($oAppointment,$oInput,$oldStatus);
                if ($bRefund) {
                    $oAppointment = Appointment::with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($id);
                    return responseBuilder()->success(__('Your Request for Refund has been submitted. Team will respond to your Request soon!'), $oAppointment, false);
                }
                return responseBuilder()->error(__('Could not Process your Request'), 404, false);
            }else {
                //Change status of appointment
                updateAppointmentStatus($oAppointment,$oInput,$oldStatus);
            }
        } else if ($oldStatus === 'done') {
            if(in_array($oInput['status'],Appointment::$NEGDONE)){
                return responseBuilder()->error(__('The appointment status cannot be updated to the selected one.'), 404, false);
            } else if($oInput['status'] === 'follow_up'){
                //Update Paid status and change status of appointment
                $oNewAppointment = followUpStatusUpdate($oAppointment,$oInput,$oldStatus);
                $this->onAppointmentApprove($oNewAppointment);
                $oAppointment   =   $oNewAppointment;
                $bNew    =   true;
                $oResponse = responseBuilder()->success(__('message.general.followOn',["mod"=>"Appointment"]), $oNewAppointment, false);
            } else {
                //Change status of appointment
                updateAppointmentStatus($oAppointment,$oInput,$oldStatus);
            }
        } else if ($oldStatus === 'follow_up') {
            if(in_array($oInput['status'],Appointment::$NEGFOLLOWUP)){
                return responseBuilder()->error(__('The appointment status cannot be updated to the selected one.'), 404, false);
            } else {
                //Change status of appointment
                updateAppointmentStatus($oAppointment,$oInput,$oldStatus);
            }
        } else if ($oldStatus === 'reschedule') {
            if(in_array($oInput['status'],Appointment::$NEGRESCHEDULE)){
                return responseBuilder()->error(__('The appointment status cannot be updated to the selected one.'), 404, false);
            } else {
                //Change status of appointment
                updateAppointmentStatus($oAppointment,$oInput,$oldStatus);
            }
        }else if ($oldStatus === 'refund') {
            if(in_array($oInput['status'],Appointment::$NEGREFUND)){
                return responseBuilder()->error(__('The appointment status cannot be updated to the selected one.'), 404, false);
            } else {
                //Change status of appointment
                updateAppointmentStatus($oAppointment,$oInput,$oldStatus);
            }
        } else if ($oldStatus === 'auto_cancel') {
            if(in_array($oInput['status'],Appointment::$NEGAUTOCANCEL)){
                return responseBuilder()->error(__('The appointment status cannot be updated to the selected one.'), 404, false);
            } else {
                //Change status of appointment
                updateAppointmentStatus($oAppointment,$oInput,$oldStatus);
            }
        } else if ($oldStatus === 'awaiting_confirmation') {
            if(in_array($oInput['status'],Appointment::$NEGAWAITINGCONFIRMATION)){
                return responseBuilder()->error(__('The appointment status cannot be updated to the selected one.'), 404, false);
            } else if($oInput['status'] === 'approved' || $oInput['status'] === 'pending'){
                //Update Paid status and change status of appointment
                $oNewAppointment = followUpStatusUpdate($oAppointment,$oInput,$oldStatus);
                $this->onAppointmentApprove($oNewAppointment);
                $oAppointment   =   $oNewAppointment;
                $bNew    =   true;
                $oResponse = responseBuilder()->success(__('message.general.followOn',["mod"=>"Appointment"]), $oNewAppointment, false);
            } else {
                //Change status of appointment
                updateAppointmentStatus($oAppointment,$oInput,$oldStatus);
            }
        }
        if ($bNew == false) {
            $oAppointment = Appointment::with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($id);
        }
        
        appointmentStatusChange($oAppointment,$oldAppointment);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Appointment Status"]), $oAppointment, false);
        
        $this->urlComponents(config("businesslogic")[26]['menu'][8], $oResponse, config("businesslogic")[26]['title']);
        
        return $oResponse;
    }
    public function parentAppointment(Request $request)
    {
        $oInput = $request->all();
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oQb = Appointment::whereNull('parent_id')->orderByDesc('updated_at')->with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy']);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"patient_id",$oQb);
        $oAppointments = $oQb->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Parent Appointments"]), $oAppointments, false);
        $this->urlComponents(config("businesslogic")[26]['menu'][9], $oResponse, config("businesslogic")[26]['title']);
        return $oResponse;

    }
    public function videoConsultation(Request $request){
        
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'tele_url'     => 'required|exists:appointments,tele_url',
            // 'tele_password'=> 'required|exists:appointments,tele_password',
            'type'         => 'required',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oAuth = Auth::user();
        
        if ($oAuth->isDoctor()) {
            
            $oAppointment = Appointment::where('tele_url',$oInput['tele_url'])->where('status','!=','done')->with(['patientId','doctorId','treatmentId'])->first(); 
            if(isset($oAppointment)){
                if($oAuth->doctor_id != $oAppointment->doctor_id){
                    Auth::user()->token()->revoke();
                    $oResponse = responseBuilder()->success(__('auth.invalid_source'), 403, false);
                    return $oResponse;
                }
            }
        }elseif ($oAuth->isPatient()) {
            $oAppointment = Appointment::where('tele_url',$oInput['tele_url'])->where('status','!=','done')->with(['patientId','doctorId','treatmentId'])->first(); 
            if(isset($oAppointment)){
                if($oAuth->patient_id != $oAppointment->patient_id){
                    Auth::user()->token()->revoke();
                    $oResponse = responseBuilder()->success(__('auth.invalid_source'), 403, false);
                    return $oResponse;
                }
            }
        }
        // $oAppointment = Appointment::where('tele_url',$oInput['tele_url'])->with(['patientId','doctorId','treatmentId'])->first();
        // $oAppointment = Appointment::where('tele_url',$oInput['tele_url'])->where('status','!=','done')->with(['patientId','doctorId','treatmentId'])->first();
        // $oAppointment = Appointment::where('tele_url',$oInput['tele_url'])->where('tele_password',$oInput['tele_password'])->with(['patientId','doctorId'])->first();
        if(!isset($oAppointment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);

        $nowTime = Carbon::now()->toDateTimeString();
        
        $timeSlot= TimeSlot::where('id',$oAppointment->slot_id)->first();
        $appointment_time = Carbon::createFromTimestamp(strtotime($oAppointment->appointment_date . $timeSlot->slot))->toDateTimeString();
        
        // if($appointment_time >= $nowTime){
        //     $message = "You are not allow to continue write now. Try to login at $appointment_time";
        //     return responseBuilder()->error(__($message), 404, false);
        // }
        // $added_time = Carbon::createFromTimestamp(strtotime($oAppointment->appointment_date . $timeSlot->slot))->addHours(2)->toDateTimeString();
        // if($added_time < $nowTime){
        //     $message = "Your Consultation time is passed. Book another appointment to continue.";
        //     return responseBuilder()->error(__($message), 404, false);
        // }
        $identity = $oInput['type'].'-'.strtotime($nowTime);
        if($oInput['type'] == 'patient'){
            if(!isset($oAppointment->patient_token)){
                $token    = $this->joinRoom($identity , $oAppointment->reference_no);
                $oAppointment->update(['patient_token' => $token]);
            }else{
                $token    = $oAppointment->patient_token;
            }
        }elseif($oInput['type'] == 'doctor'){
            if(!isset($oAppointment->doctor_token)){
                $token    = $this->joinRoom($identity , $oAppointment->reference_no);
                $oAppointment->update(['doctor_token' => $token]);
            }else{
                $token    = $oAppointment->doctor_token;
            }
        }else{
            $token    = $this->joinRoom($identity , $oAppointment->reference_no);
        }
        
        $patient = $oAppointment->patientId;
        $remarks = [];
        $patient_riskfactor = [];
        if(isset($patient)){
            $patient_riskfactor = PatientRiskfactor::where('patient_id',$patient->pk)->get();
            $appointment_remarks = Appointment::where('patient_id',$patient->pk)->where('status','done')->get(); 
            $remarks = $appointment_remarks->pluck('doctor_remarks');    
        }
        $oAppointment['remarks'] = $remarks;
        $oAppointment['patient_riskfactor'] = $patient_riskfactor;
        
        return response()->json([ 'token' => $token, 'room_name' => $oAppointment->reference_no,'identity' => $identity, 'appointment' =>$oAppointment]);
    }
    public function doctorRemarks(Request $request, $id){
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'doctor_remarks'=> 'required|max:1000',
            'treatment_id'  => 'present|nullable|exists:doctor_treatments,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oAppointment = Appointment::findOrFail($id); 
        
        $appointment  = $oAppointment->update([
            "doctor_remarks" => $oInput['doctor_remarks'],
            "treatment_id"   => $oInput['treatment_id'],
            'updated_by'     => Auth::user()->id,
            'updated_at'     => Carbon::now()->toDateTimeString(),
            
        ]);
        $oAppointment = Appointment::with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($id);       
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Appointment Remarks"]), $oAppointment, false);
        return $oResponse;
    }
    /**
     * initiate payment request flow - by patient
     */
    public function init_payment(Request $request, $method, $appointment_id)
    {
        //$iAppointmentId = decrypt($appointment_id);
        //$sPaymentMethod = decrypt($method);

        $iAppointmentId = $appointment_id;
        $sPaymentMethod = $method;

        $aData = $request->all();

        $oValidator = Validator::make($aData,[
            '_mobile' => 'nullable|digits:11',
            '_cnic'   => 'nullable|digits:6',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }


        $aPaymentMethods = array('ep_cc','ep_otc','ep_ma','jc_otc','jc_ma','jc_cc','bank','cash');

        

        if(!in_array($sPaymentMethod, $aPaymentMethods))
            return responseBuilder()->error('Invalid Payment Init Request', 403, false);

        //generate reference number for appointment

        $oAppointment = Appointment::with(['patient', 'patient.user'])->findOrFail($iAppointmentId);
        //dd($oAppointment);


        


        if(auth()->user()->isPatient()){
            if($oAppointment->patient_id != auth()->user()->patient_id)
                return responseBuilder()->error('Not Authorized', 403, false);
        }

        if($oAppointment->paid_status == 'paid')
            return responseBuilder()->error('Already Paid', 403, false);

        $aStatusNotInInit = array('approved','cancel_by_doctor','cancel_by_patient','no_show','done','ongoing','follow_up','reschedule','refund','auto_cancel');
        

        //check for payment timelimit
        $oPaymentLimit = \Carbon\Carbon::parse($oAppointment->payment_timelimit);
        if($oPaymentLimit->lt(\Carbon\Carbon::now()))
            return responseBuilder()->error('Timelimit Expired', 403, false);

        if(in_array($oAppointment->paid_status, $aStatusNotInInit))
            return responseBuilder()->error('Status Invalid for Payment', 403, false);



        //before initiating new transaction, set all previous transactions to void
        ///AP::where('appointment_id', $iAppointmentId)->update(['status' => 'void']);


        $sTransRef = $iAppointmentId.Str::random(5).$oAppointment->doctor_id.$oAppointment->patient_id;

        $sTransRef = strtoupper($sTransRef);

        $aAppPaymentData = array();
        $aAppPaymentData['appointment_id'] = $iAppointmentId;
        $aAppPaymentData['payment_method'] = $sPaymentMethod;
        $aAppPaymentData['transaction_ref'] = $sTransRef;
        $aAppPaymentData['status'] = 'init';
        $aAppPaymentData['created_at'] = \Carbon\Carbon::now()->toDateTimeString();


        $bAppPayment = AP::create($aAppPaymentData);

        //dd($bAppPayment);

        if(empty($bAppPayment->id))
            return responseBuilder()->error('Unable to INIT', 403, false);


        $aManualRequest = array();
        $aManualRequest['pp_Amount'] = $oAppointment->appointment_fee;
        $aManualRequest['pp_BillReference'] = $oAppointment->reference_no;
        $aManualRequest['pp_Description'] = 'Payment Request';
        $aManualRequest['pp_TxnRefNo'] = $sTransRef;
        $aManualRequest['pp_TxnExpiryDateTime'] = $oPaymentLimit->format("YmdHis");

        if($sPaymentMethod == 'jc_ma'){

            //incase user want to provide alternate jazzcash account number
            if(!empty($aData['_mobile'])){
                $aManualRequest['pp_MobileNumber'] = $aData['_mobile'];

                if($oAppointment->patient->user->phone != $aData['_mobile'])
                    $aManualRequest['pp_CNIC'] = $aData['_cnic'];
                else
                    $aManualRequest['pp_CNIC'] = substr($oAppointment->patient->cnic, -6);//"345678";    

            }else{
                $aManualRequest['pp_MobileNumber'] = $oAppointment->patient->user->phone;
                $aManualRequest['pp_CNIC'] = substr($oAppointment->patient->cnic, -6);//"345678";
            }


            

            
        }
        if($sPaymentMethod == 'jc_otc'){

            $aManualRequest['pp_TxnType'] = "OTC";

            $aManualRequest['ppmpf_1'] = $oAppointment->patient->user->phone;
            if(!empty($aData['_mobile']))
                $aManualRequest['ppmpf_1'] = $aData['_mobile'];                
             
        }


        if($sPaymentMethod == 'ep_ma'){
            $aManualRequest['orderId']                  = $sTransRef; //$oAppointment->reference_no;
            $aManualRequest['transactionAmount']        = $oAppointment->appointment_fee;

            if(!empty($aData['_mobile']))
                $aManualRequest['mobileAccountNo']      = $aData['_mobile'];
            else
                $aManualRequest['mobileAccountNo']      = $oAppointment->patient->user->phone; 

            if(!empty($aData['_email']))
                $aManualRequest['emailAddress']         = $aData['_email'];
            else
                $aManualRequest['emailAddress']         = $oAppointment->patient->user->email;

        }

    	if($sPaymentMethod == 'ep_otc'){
            

            $aManualRequest['orderId']                 = $sTransRef; //$oAppointment->reference_no;
    	    $aManualRequest['transactionAmount']       = $oAppointment->appointment_fee;
    	    $aManualRequest['msisdn'] 		           = $oAppointment->patient->user->phone;
            $aManualRequest['ExpiryDateTime']          = $oPaymentLimit->format("Ymd His");

            if(!empty($aData['_email']))
                $aManualRequest['emailAddress']         = $aData['_email'];
            else
                $aManualRequest['emailAddress']         = $oAppointment->patient->user->email;



    	}

        $sLeadingGate = substr($sPaymentMethod, 0,2);
        //dd($sLeadingGate);
        if($sLeadingGate == 'jc'){ //if jazzcash

            if($sPaymentMethod != 'jc_otc')
                $aManualRequest['ppmpf_2'] = $bAppPayment->id;
            //$aManualRequest['ppmpf_3'] = 'CLINICALL';

            return $this->jc_transaction($sPaymentMethod, $aManualRequest);
        }


        if($sLeadingGate == 'ep'){
            
            $aManualRequest['optional2'] = $bAppPayment->id;


            return $this->ep_transaction($sPaymentMethod, $aManualRequest);
        }
    }







    public function ep_transaction($sPaymentMethod, $aPreparedDataset){
        
        if($sPaymentMethod == 'ep_ma'){

            $aTxnResponse = $this->epMAPayment($aPreparedDataset);

            $sResponseDescription = "Unable to process transaction";

            if(isset($aTxnResponse['RETURN']['responseDesc']))
                $sResponseDescription = $aTxnResponse['RETURN']['responseDesc'];


            if($aTxnResponse['STATUS'] == 'OK' && $aTxnResponse['RETURN']['responseCode'] == '000'){

                $iPkIdAppointmentPayments = $aTxnResponse['RETURN']['optional2'];

                $oAppointmentDataId = AP::select('appointment_id')->where('id', $iPkIdAppointmentPayments)->first();

                DB::beginTransaction();

                try{


                    $aUpdateAppointment = array();
                    $aUpdateAppointment['paid_status']  = 'paid';
                    $aUpdateAppointment['status']       = 'approved';

                    $mAppResp = Appointment::where('id', $oAppointmentDataId->appointment_id)->update($aUpdateAppointment);
                    
                    $oAppointmentApproved = Appointment::where('id',$oAppointmentDataId->appointment_id)->with(['slotId','patientId','doctorId','centerId','treatmentId'])->first();
                    $this->onAppointmentApprove($oAppointmentApproved);
                    //dd($mAppResp);

                    $aUpdateTransaction = array();
                    $aUpdateTransaction['status']                       = 'processed';
                    $aUpdateTransaction['pay_date']                     = \Carbon\Carbon::now()->toDateTimeString();
                    $aUpdateTransaction['thirdparty_payment_status']    = $aTxnResponse['RETURN']['responseCode'];
                    $aUpdateTransaction['thirdparty_id']                = $aTxnResponse['RETURN']['transactionId'];
                    $aUpdateTransaction['thirdparty_response']          = serialize($aTxnResponse['RETURN']);


                    AP::where('id', $iPkIdAppointmentPayments)->update($aUpdateTransaction);

                   
                    DB::commit();

                    $oResponse = responseBuilder()->success($aTxnResponse['RETURN']['responseDesc'], '', false);
                
                    ///$this->urlRec(3, 0, $oResponse);
                
                    return $oResponse;


                }catch(\Exception $oException){


                    DB::rollback();

                    //dd($oException);

                    return responseBuilder()->error($oException->getMessage(), 403, false);
                }

            }

            return responseBuilder()->error($sResponseDescription, 403, false);



        }

        //end ep - ma

        if($sPaymentMethod == 'ep_otc'){

            $aTxnResponse = $this->epOTCPayment($aPreparedDataset);


            $sResponseDescription = "Unable to process transaction";

            if(isset($aTxnResponse['RETURN']['responseDesc']))
                $sResponseDescription = $aTxnResponse['RETURN']['responseDesc'];

            if($aTxnResponse['STATUS'] == 'OK' && $aTxnResponse['RETURN']['responseCode'] == '0000'){

                $iPkIdAppointmentPayments = $aTxnResponse['RETURN']['optional2'];

                $sResponseDescription = "Payment token (".$aTxnResponse['RETURN']['paymentToken'].") generated successfully for over the counter payment.";

                $aUpdateTransaction = array();
                $aUpdateTransaction['status']                       = 'awaiting_confirmation';
                $aUpdateTransaction['thirdparty_payment_status']    = $aTxnResponse['RETURN']['responseCode'];
                $aUpdateTransaction['thirdparty_id']                = $aTxnResponse['RETURN']['paymentToken'];
                $aUpdateTransaction['thirdparty_response']          = serialize($aTxnResponse['RETURN']);


                AP::where('id', $iPkIdAppointmentPayments)->update($aUpdateTransaction);

                $aRetrival = array('REFERNCE' => $aTxnResponse['RETURN']['paymentToken']);

                $oResponse = responseBuilder()->success($sResponseDescription, $aRetrival, false);

                $this->appointmentEmail($iPkIdAppointmentPayments);
                
                ///$this->urlRec(3, 0, $oResponse);
                
                return $oResponse;
            }

            return responseBuilder()->error($sResponseDescription, 403, false);

        }

    }


    public function jc_transaction($sPaymentMethod, $aPreparedDataset)
    {

        if($sPaymentMethod == 'jc_ma'){

            $aTxnResponse = $this->jcMwallet($aPreparedDataset);

            $sErrorDescription = "Unable to process transaction";
            /* Successfull transaction done and mark the appointment as paid */
            if($aTxnResponse['STATUS'] == 'OK' && $aTxnResponse['RETURN']['pp_ResponseCode'] == '000'){

                /**
                 * child table - appointment_payment PK id available in ppmpf_2
                 * so by using the above child table entry -- we can get parent table entry
                 */

                $iPkIdAppointmentPayments = $aTxnResponse['RETURN']['ppmpf_2'];

                $oAppointmentDataId = AP::select('appointment_id')->where('id', $iPkIdAppointmentPayments)->first();

                DB::beginTransaction();

                try{


                    $aUpdateAppointment = array();
                    $aUpdateAppointment['paid_status']  = 'paid';
                    $aUpdateAppointment['status']       = 'approved';

                    $mAppResp = Appointment::where('id', $oAppointmentDataId->appointment_id)->update($aUpdateAppointment);
                    
                    $oAppointmentApproved = Appointment::where('id',$oAppointmentDataId->appointment_id)->with(['slotId','patientId','doctorId','centerId','treatmentId'])->first();
                    $this->onAppointmentApprove($oAppointmentApproved);
                    //dd($mAppResp);

                    $aUpdateTransaction = array();
                    $aUpdateTransaction['status']                       = 'processed';
                    $aUpdateTransaction['pay_date']                     = \Carbon\Carbon::now()->toDateTimeString();
                    $aUpdateTransaction['thirdparty_payment_status']    = $aTxnResponse['RETURN']['pp_ResponseCode'];
                    $aUpdateTransaction['thirdparty_id']                = $aTxnResponse['RETURN']['pp_RetreivalReferenceNo'];
                    $aUpdateTransaction['thirdparty_response']          = serialize($aTxnResponse['RETURN']);


                    AP::where('id', $iPkIdAppointmentPayments)->update($aUpdateTransaction);

                   
                    DB::commit();

                    $oResponse = responseBuilder()->success($aTxnResponse['RETURN']['pp_ResponseMessage'], '', false);
                
                    ///$this->urlRec(3, 0, $oResponse);
                
                    return $oResponse;


                }catch(\Exception $oException){


                    DB::rollback();

                    //dd($oException);

                    return responseBuilder()->error($oException->getMessage(), 403, false);
                }


                
                $sErrorDescription = $aTxnResponse['RETURN']['pp_ResponseMessage'];
            } //mark paid end

            return responseBuilder()->error($sErrorDescription, 403, false);

        } //JS_MA transaction ends 


        if($sPaymentMethod == 'jc_otc'){


            $aTxnResponse = $this->jcOtc($aPreparedDataset);

            //dd($aTxnResponse);

            $iPkIdAppointmentPayments = $aTxnResponse['RETURN']['ppmpf_2'];

            if($aTxnResponse['STATUS'] == 'OK'){

                $aUpdateTransaction = array();
                $aUpdateTransaction['status']                       = 'awaiting_confirmation';
                //$aUpdateTransaction['pay_date']                     = \Carbon\Carbon::now()->toDateTimeString();
                $aUpdateTransaction['thirdparty_payment_status']    = $aTxnResponse['RETURN']['pp_ResponseCode'];
                $aUpdateTransaction['thirdparty_id']                = $aTxnResponse['RETURN']['pp_RetreivalReferenceNo'];
                $aUpdateTransaction['thirdparty_response']          = serialize($aTxnResponse['RETURN']);


                AP::where('id', $iPkIdAppointmentPayments)->update($aUpdateTransaction);

                $aRetrival = array('REFERNCE' => $aTxnResponse['RETURN']['pp_RetreivalReferenceNo']);

                $oResponse = responseBuilder()->success($aTxnResponse['RETURN']['pp_ResponseMessage'], $aRetrival, false);

                $this->appointmentEmail($iPkIdAppointmentPayments);
                
                ///$this->urlRec(3, 0, $oResponse);
                
                return $oResponse;
            }
        
            return responseBuilder()->error("Unable to process OTC transaction request", 403, false);

        } //end JC-OTC


        if($sPaymentMethod == 'jc_cc'){

            /**
             * THis will works only in redirection mood
             * customer will first be computed hash of the amount and other details
             * then customer will be redirected to the payment gateway for requeting of acutal cr/dr number expiry and other details
             * once cusotmer enter s/he will be taken back to ppost back url/function
             */

            $aTxnResponse = $this->jc_card($aPreparedDataset);

            //dump($aTxnResponse);

            $oResponse = responseBuilder()->success($aTxnResponse, '', false);
            ///$this->urlRec(3, 0, $oResponse);
            return $oResponse;

        }//end JC-CARD
        

        

        

    }



    /**
     * Actual transaction from the vendor system to get and sync with payments
     */
    public function getTransactionStatus(Request $request, $appointment_id, $transaction_id)
    {
        //dump("@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@");
        //dump($request);
        //dump($appointment_id);
        //dump($transaction_id);

        //dd(";;;;;;;;;;;;;;;");

        //$iAppointmentId = decrypt($appointment_id);
        $iAppointmentId = $appointment_id;

        $oAppointment = Appointment::with(['patient', 'patient.user'])->findOrFail($iAppointmentId);
        //dd($oAppointment);

        if(empty($oAppointment->id))
            return responseBuilder()->error('No appointment record found', 403, false);


        $oPaymentsChild = AP::where('appointment_id', $iAppointmentId)
                            ->where('transaction_ref', $transaction_id)
                            ->first();
        

        //dump($iAppointmentId." ~~ ".$transaction_id);
        //dump($oPaymentsChild);

        if(empty($oPaymentsChild->id))
            return responseBuilder()->error('No appointment transaction record found', 403, false);


        if($oAppointment->paid_status == 'paid')
            return responseBuilder()->success('Appointment already in paid status', '', false);



        /**
         * If transaction in unapid at server and got status of paid then mark its as paid
         */

        $aRequestForStatus = array();
        
        $aPaymentGatewayParty = substr($oPaymentsChild->payment_method, 0,2);

        if($aPaymentGatewayParty == 'jc'){
            $aRequestForStatus['pp_TxnRefNo'] = $oPaymentsChild->transaction_ref;
            //dd($aRequestForStatus);
            $aTxnResponse = $this->jcStatusInquiry($aRequestForStatus);
            LOG::info(print_r($aTxnResponse, true));


            if(!empty($aTxnResponse['RETURN']['pp_PaymentResponseCode'])){

                if( in_array($aTxnResponse['RETURN']['pp_PaymentResponseCode'], array('121', '000')) ){

                    return $this->markAppointmentAsPaid($oAppointment->id, $oPaymentsChild->id, $aTxnResponse['RETURN']['pp_ResponseCode'], $aTxnResponse['RETURN']['pp_PaymentResponseMessage'], $aTxnResponse['RETURN']);
                }

            }


            return responseBuilder()->error($aTxnResponse['RETURN']['pp_PaymentResponseMessage'], 403, false); 

        }
        elseif($aPaymentGatewayParty == 'ep'){

            $aRequestForStatus['orderId'] = $transaction_id;//$oAppointment->reference_no;
            $aTxnResponse = $this->epStatusInquiry($aRequestForStatus);

            LOG::info(print_r($aTxnResponse, true));

            if(isset($aTxnResponse['transactionStatus']) && in_array($aTxnResponse['transactionStatus'], array("PAID")) ){
                
                return $this->markAppointmentAsPaid($oAppointment->id, $oPaymentsChild->id, $aTxnResponse['responseCode'], $aTxnResponse['transactionStatus'], $aTxnResponse);
            }

            if($aTxnResponse['responseCode'] == '0000')
                return responseBuilder()->error($aTxnResponse['transactionStatus'], 403, false);

            return responseBuilder()->error("Unable to get status from Easypaisa payment gateway.", 403, false);
                
        }

        //else if payment not done simply respond with actual status
        return responseBuilder()->error("Invalid Method or Payment Channel", 403, false);

    }


    /**
     * TO update transaction -- appointment payment status with conjunction of status inquiry update
     * from the 3rd party payment gateway.
     */
    public function markAppointmentAsPaid($iAppointmentId, $iTranChildId, $iResponseCode, $sRespMsg, $aReturnData)
    {

        try{


            $aUpdateAppointment = array();
            $aUpdateAppointment['paid_status']  = 'paid';
            $aUpdateAppointment['status']       = 'approved';

            $mAppResp = Appointment::where('id', $iAppointmentId)->update($aUpdateAppointment);
            
            $oAppointmentApproved = Appointment::where('id',$iAppointmentId)->with(['slotId','patientId','doctorId','centerId','treatmentId'])->first();

            $this->onAppointmentApprove($oAppointmentApproved);
            //dd($mAppResp);

            $aUpdateTransaction = array();
            $aUpdateTransaction['status']                       = 'processed';
            $aUpdateTransaction['pay_date']                     = \Carbon\Carbon::now()->toDateTimeString();
            $aUpdateTransaction['thirdparty_payment_status']    = $iResponseCode;
            $aUpdateTransaction['thirdparty_id']                = $sRespMsg;
            $aUpdateTransaction['thirdparty_response']          = serialize($aReturnData);


            AP::where('id', $iTranChildId)->update($aUpdateTransaction);

            LOG::info(print_r($aUpdateTransaction, true));
            DB::commit();

            $oResponse = responseBuilder()->success($sRespMsg, '', false);

            $this->appointmentEmail($iTranChildId);
        
            ///$this->urlRec(3, 0, $oResponse);
        
            return $oResponse;


        }catch(\Exception $oException){


            DB::rollback();

            //dd($oException);

            return responseBuilder()->error($oException->getMessage(), 403, false);
        }



    }


    /**
     * After credit card / debit card transaction gateway will send back customer to this function
     * this works in redirection mood
     */
    public function jazzPostBackUrl(Request $request)
    {
        //dump("This is return back URL for JazzCash Txn");

        $aPostBackData = $request->all();

        LOG::info("Jazzcash RETURN BACK---------------------------------START");
        LOG::info(print_r($request->all(), true));

        //dd(env('JC_FRONTURL'));

        //dump($aPostBackData);

        $sHumandReableRespo = "Unable to process transaction";

        $iChildId = $aPostBackData['ppmpf_2'];
        $transaction_id = $aPostBackData['pp_TxnRefNo'];

	
	$oPaymentsChild = AP::where('id', $iChildId)
                            ->where('transaction_ref', $transaction_id)
			    ->first();


	$iAppointmentId = $oPaymentsChild->appointment_id;

        $oAppointment = Appointment::with(['patient', 'patient.user'])->findOrFail($iAppointmentId);
        //dd($oAppointment);

        if(empty($oAppointment->id))
            return responseBuilder()->error('No appointment record found', 403, false);



        if(empty($oPaymentsChild->id))
            return responseBuilder()->error('No appointment transaction record found', 403, false);

        $sHumandReableRespo = $aPostBackData['pp_ResponseMessage'];

        if(isset($aPostBackData['pp_ResponseCode']) &&  in_array($aPostBackData['pp_ResponseCode'], array(000)) && $oAppointment->paid_status == 'unpaid'){

                
            try{


                $aUpdateAppointment = array();
                $aUpdateAppointment['paid_status']  = 'paid';
                $aUpdateAppointment['status']       = 'approved';

                $mAppResp = Appointment::where('id', $oAppointment->id)->update($aUpdateAppointment);
                
                $oAppointmentApproved = Appointment::where('id',$oAppointment->id)->with(['slotId','patientId','doctorId','centerId','treatmentId'])->first();
                $this->onAppointmentApprove($oAppointmentApproved);
                //dd($mAppResp);

                $aUpdateTransaction = array();
                $aUpdateTransaction['status']                       = 'processed';
                $aUpdateTransaction['pay_date']                     = \Carbon\Carbon::now()->toDateTimeString();
                $aUpdateTransaction['thirdparty_payment_status']    = $aPostBackData['pp_ResponseCode'];
                $aUpdateTransaction['thirdparty_id']                = $aPostBackData['pp_RetreivalReferenceNo'];
                $aUpdateTransaction['thirdparty_response']          = serialize($aPostBackData);


                AP::where('id', $oPaymentsChild->id)->update($aUpdateTransaction);

               
                DB::commit();

                //$oResponse = responseBuilder()->success($aPostBackData['pp_ResponseMessage'], '', false);

                //$this->appointmentEmail($oPaymentsChild->id);
            
                ///$this->urlRec(3, 0, $oResponse);
            
                //return $oResponse;


            }catch(\Exception $oException){


                DB::rollback();

                //dd($oException);

                //return responseBuilder()->error($oException->getMessage(), 403, false);
            }
        }

	$sUrlToRedirect = env('JC_FRONTURL')."/".$oAppointment->id."?msg=".$sHumandReableRespo;

        //redirect customer to the front end url
        //dump("Redirection starts.....");
        //dd("frontend url ". $sUrlToRedirect);
	return \Redirect::to($sUrlToRedirect);
    }

    /**
     * A listner for jazzcash transaction to process transaction not activly tracked by controller
     */
    public function jc_listner(Request $request)
    {
        $aPostBackData = $request->all();

        LOG::info("Jazzcash Listner---------------------------------START");

        LOG::info(print_r($request->all(), true));
        



        LOG::info("Jazzcash Listner---------------------------------END");

        dd($aPostBackData);
    }

    /**
     * will send email confirmation to patient if payment confirmed
     */
    private function appointmentEmail($appointment_id){



        if(auth()->user()->email_verified != 1)
            return;


        $oAppointment = Appointment::where('id', $appointment_id)->first();

        if(!isset($oAppointment)){
            return true;
        }
            
        
        $sEmailSubject = "Appointment Paid Successfully (".$oAppointment->reference_no.")";

        $sBody = "Appointment Successfully paid and confirmed";

        $sBody = $sBody.$this->getAppointmentPresentationDetails($appointment_id);

        $sEmailAdd = auth()->user()->email;
        dispatch(new SendEmail(Mail::to($sEmailAdd)->send(new GeneralAlert($sEmailSubject, $sBody))));
        
    }

    private function getAppointmentPresentationDetails($appointment_id)
    {
        $oAppointment = Appointment::findOrFail($appointment_id);

        if(!isset($oAppointment)){
            return true;
        }
        $sTabs = "\t\t\t\t";
        $sAppointmentDetailsMsg  = "<br><br>DOCTOR NAME: $sTabs <b>".$oAppointment->appointment_date."</b><br/>";
        $sAppointmentDetailsMsg .= "APPOINTMENT DATE: $sTabs <b>".$oAppointment->appointment_date."</b><br/>";
        $sAppointmentDetailsMsg .= "APPOINTMENT TYPE: $sTabs <b>".$oAppointment->appointment_type."</b><br/>";
        $sAppointmentDetailsMsg .= "FEE: $sTabs <b>".$oAppointment->appointment_date."</b><br/>";
        $sAppointmentDetailsMsg .= "DISCOUNTED FEE: $sTabs <b>".$oAppointment->appointment_date."</b><br/>";
        $sAppointmentDetailsMsg .= "PAYMANET STATUS: $sTabs <b>".$oAppointment->paid_status."</b><br/>";        
        if($oAppointment->paid_status == 'paid'){

            $sAppointmentDetailsMsg .= "PAID VIA: $sTabs <b>".$oAppointment->appointment_date."</b><br/>";
        }


        return $sAppointmentDetailsMsg;

    }
    
     private function envFiles (){
        //Live 
        $this->sid = env('TWILIO_ACCOUNT_SID','AC5f1f9b58450d014ca7fab513bd877793');
        $this->token = env('TWILIO_ACCOUNT_TOKEN','dfa566b3a0bd2cfb4186c41ea9359aa7');
        $this->key = env('TWILIO_API_KEY','SK7a9aecf409fbd0ab9b5d438be4311185');
        $this->secret = env('TWILIO_API_SECRET','fRkruI5Q1l8WcTW4HM6AgaV22sqwHU4y');
        
    
        
        // Staging
        // $this->sid = env('TWILIO_ACCOUNT_SID','ACe1fa9c3a56e24061276fb549740ecdbe');
        // $this->token = env('TWILIO_ACCOUNT_TOKEN','d5e70fb4ad0ff7ae899f43d7d1b99b4c');
        // $this->key = env('TWILIO_API_KEY','SK5910d189402a003fe84e07e8d29538b3');
        // $this->secret = env('TWILIO_API_SECRET','Wkxesc5sQ974e3HdM0Rmuw4sSDudXt0N');
        // Dev
        // $this->sid = env('TWILIO_ACCOUNT_SID','AC6692ea20d88ccdfb9b2da5d0012ad82e');
        // $this->token = env('TWILIO_ACCOUNT_TOKEN','c66714a5bb2d94e27ebc579c84b1dcfa');
        // $this->key = env('TWILIO_API_KEY','SK3ce52ff4553299d13d06d75aac11aaa1');
        // $this->secret = env('TWILIO_API_SECRET','uNIvPjZjSJXHiBl3uGXjmtv0vPYCojTy');
    }

    // List of All Created Rooms
    public function listOfRooms()
    {
       $this->envFiles(); 
        $rooms = [];
        try {
            $client = new Client($this->sid, $this->token);
            $allRooms = $client->video->rooms->read([]);

                $rooms = array_map(function($room) {
                return $room->uniqueName;
                }, $allRooms);

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return $rooms;
    }
    public function createRoom($room_name)
    {
        $this->envFiles();

        $client = new Client($this->sid, $this->token);

        $exists = $client->video->rooms->read([ 'uniqueName' => $room_name]);

        if (empty($exists)) {
            $client->video->rooms->create([
                'uniqueName' => $room_name,
                'type' => 'group',
                'recordParticipantsOnConnect' => false
            ]);
        }

        return response()->json(["message"=>"Room Created Successfully"],200);
    }
    public function joinRoom($identity , $room_name)
    {
        $this->envFiles();

        $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);

        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($room_name);

        $token->addGrant($videoGrant);
       
        return $token->toJWT();
        return response()->json([ 'token' => $token->toJWT(), 'room_name' => $room_name,'identity' => $identity ]);
    }
     public function generate_token($identity , $room_name)
    {
        $this->envFiles();
        // Substitute your Twilio Account SID and API Key details
        $accountSid   = $this->sid;
        $apiKeySid    = $this->key;
        $apiKeySecret = $this->secret;
        // Create an Access Token
        $token = new AccessToken(
            $accountSid,
            $apiKeySid,
            $apiKeySecret,
            3600,
            $identity,
            $room_name
        );

        // Grant access to Video
        $grant = new VideoGrant();
        // $grant->setRoom('');
        $token->addGrant($grant);

        // Serialize the token as a JWT
        $result=[ 
            "identity" => $identity,
            "token"=> $token->toJWT()
        ];

        return response()->json($result);
    }
    public function onAppointmentApprove($appointment){
        
        $newStatus = $appointment->status;
        
        $patientEmail  =  $appointment->patientId->email;
        $patientPhone  =  $appointment->patientId->phone;
        $patientName   =  $appointment->patientId->name;
        $patientId     =  $appointment->patientId->pk;
        $patientIdentity =   $appointment->reference_no.'patient'.$patientId;

        $doctorEmail  =  $appointment->doctorId->email;
        $doctorPhone  =  $appointment->doctorId->phone;
        $doctorName   =  $appointment->doctorId->full_name;   
        $doctorBaseUrl=  $appointment->doctorId->url;   
        $doctorId     =  $appointment->doctorId->pk;
        $doctorTitle  =  $appointment->doctorId->title;
        $doctorIdentity =   $appointment->reference_no.'doctor'.$doctorId;
        
        $room =   $appointment->reference_no;

        $timeSlot     = TimeSlot::where('id',$appointment->slot_id)->first();
        $date         = Carbon::parse($appointment->appointment_date);                                                // Appointment date
        $fdate        = $date->format('jS F Y');
        $time         = date('h:i A', strtotime($timeSlot->slot));
        $n            = '\n';
        if($appointment->appointment_type == 'physical'){
            $patientMessage = "Your Physical appointment is confirmed with the following details:".$n.$n."Doctor Name: $doctorTitle. $doctorName.".$n."Date: $fdate ".$n."Time: $time";
            $doctorMessage  = "Your Physical appointment is confirmed with the following details:".$n.$n."Patient Name: $patientName.".$n."Date: $fdate ".$n."Time: $time";
            
            if(isset($patientMessage) && isset($patientPhone)){
                smsGateway($patientPhone, $patientMessage);
            }
            if(isset($patientMessage) && isset($patientEmail)){
                $patientMessage = str_replace('\n', '<br>', $patientMessage);
                $emailTitle = "Physical Appointment";
                emailGateway($patientEmail, $patientMessage, $emailTitle);
            }
            if(isset($doctorMessage) && isset($doctorPhone)){
                smsGateway($doctorPhone, $doctorMessage);
            }
            if(isset($doctorMessage) && isset($doctorEmail)){
                $doctorMessage = str_replace('\n', '<br>', $doctorMessage);
                $emailTitle = "Physical Appointment";
                emailGateway($doctorEmail, $doctorMessage, $emailTitle);
            }
        }else{
            $this->createRoom($room);
            $url  = uniqid();
            $doctorUrl    = config("app")['FRONTEND_URL'].'online_consultancy/'.$url;
            $patientUrl   = config("app")['FRONTEND_URL'].'online_consultancy/'.$doctorBaseUrl.'/'.$url;

            $patientMessage = "Your online appointment is confirmed with the following details:".$n.$n."Doctor Name: $doctorTitle. $doctorName.".$n."Date: $fdate ".$n."Time: $time ".$n."Consultation Link: $patientUrl";
            $doctorMessage  = "Your online appointment is confirmed with the following details:".$n.$n."Patient Name: $patientName.".$n."Date: $fdate ".$n."Time: $time ".$n."Consultation Link: $doctorUrl";
            
            if(isset($patientMessage) && isset($patientPhone)){
                smsGateway($patientPhone, $patientMessage);
            }
            if(isset($patientMessage) && isset($patientEmail)){
                $patientMessage = str_replace('\n', '<br>', $patientMessage);
                $emailTitle = "Online Consultation";
                emailGateway($patientEmail, $patientMessage, $emailTitle);
            }
            if(isset($doctorMessage) && isset($doctorPhone)){
                smsGateway($doctorPhone, $doctorMessage);
            }
            if(isset($doctorMessage) && isset($doctorEmail)){
                $doctorMessage = str_replace('\n', '<br>', $doctorMessage);
                $emailTitle = "Online Consultation";
                emailGateway($doctorEmail, $doctorMessage, $emailTitle);
            }
            $appointment->update([
                'tele_url' => $url,
                'tele_password' => $room,
            ]);
        }
        
        return $newStatus; 
    }
    

}
