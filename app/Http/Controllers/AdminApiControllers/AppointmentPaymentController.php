<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;
use Twilio\Jwt\AccessToken;
use Twilio\Rest\Client;
use Twilio\Jwt\Grants\VideoGrant;
use App\AppointmentPayment;
use App\Appointment;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Patient;
use App\PaymentRefund;
use App\TimeSlot;
use Exception;
use App\Traits\VideoConsultation;    

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class AppointmentPaymentController extends Controller
{
    use \App\Traits\WebServicesDoc;
    use VideoConsultation;    

    public function index(Request $request)
    {
    	if (!Gate::allows('appointment-payment-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
            
        $oQb = AppointmentPayment::orderByDesc('updated_at')->with(['verifiedBy','bankId']);
        
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"payment_method",$oQb);
        $oQb = QB::whereLike($oInput,"status",$oQb);
        $oQb = QB::whereLike($oInput,"evidence_url",$oQb);

        $oAppointmentPayments = $oQb->paginate();
        foreach ($oAppointmentPayments as $appointmentPayment) {
            $appointmentPayment['appointment_id'] = Appointment::where('id',$appointmentPayment->appointment_id)->with(['doctorId','patientId'])->first();
        }
		$oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Appointment Payment"]), $oAppointmentPayments, false);

        $this->urlRec(34, 0, $oResponse);

		return $oResponse;
    }
	
    public function create(Request $request)
    {
        $oInput = $request->all() ;  
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oValidator = Validator::make($oInput,[
            'record' 			=> 'required|file|mimes:jpeg,jpg,png',
    		'appointment_id'	=> 'required|exists:appointments,id',
    		'bank_id'	        => 'required|exists:bank_accounts,id',
    		'doctor_id'         => 'required|exists:doctors,id',
            'patient_id'        => 'required|exists:patients,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
    	
        $oAppointment = Appointment::where('id',$oInput['appointment_id'])->where('patient_id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oAppointment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);

        // if($oAppointment->status != 'pending' || $oAppointment->status != 'awaiting_confirmation')
        //     return responseBuilder()->error(__('You are not allowed to upload because appointment already proceed'), 404, false);
        
        $nowTime = Carbon::now()->toDateTimeString();
        if($oAppointment->payment_timelimit <= $nowTime){
            
            $oAppointment->update([
                'status' => 'auto_cancel'
            ]);
            return responseBuilder()->error(__('You are not allowed to upload because Payment time limit passed'), 404, false);
        }
        
        $oAppointmentPayment = AppointmentPayment::where('appointment_id',$oInput['appointment_id'])->where('status','!=','canceled')->first();
        if(isset($oAppointmentPayment))
            return responseBuilder()->error(__('message.general.already',["mod"=>"Appointment Payment"]), 404, false);
        
        $oFile = $request->file('record');
    	$mPutFile = Storage::disk('local')->putFile('appointment_payments/'.md5($oInput['patient_id']), $oFile);
    	
    	$oAppointmentPayment = AppointmentPayment::create([
            "appointment_id" => $oInput['appointment_id'],
            "payment_method" => 'bank',
            "pay_date"       => Carbon::now()->toDateTimeString(),
            "bank_id"        => $oInput['bank_id'],
            "status"         => 'pending',
            "mime_type"      => $oFile->getClientMimeType(),
            "file_type"      => $oFile->extension(),
            "file_name"      => substr($oFile->getClientOriginalName(), 0, 250),
            "evidence_url"   => $mPutFile,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);

        $oAppointmentReferenceNumber = $oAppointment->reference_no;

        $oAppointment->update([
            'status' => 'awaiting_confirmation'
        ]);
        $patient = Patient::where('id',$oInput['patient_id'])->first();
        if(isset($patient)){
            $emailTitle = "Evidence Uploaded";
            $message = "Thank you for uploading the receipt of payment. Our representative will get in touch with you upon verifying the transfer & confirming your appointment.";
            if(isset($patient->phone)){
                smsGateway($patient->phone,$message);
            }
            if(isset($patient->email)){
                emailGateway($patient->email,$message,$emailTitle);   
            }
            $message = "$patient->name uploaded the payment evidence against $oAppointmentReferenceNumber. Please Check through Admin side.";
            // smsGateway("03222555601",$message);
           // smsGateway("03222555601",$message);
                $phone_dash = preg_replace("/[^0-9]/", "", '03222555601');
        $message    = str_replace('&', 'and', $message);
        $message    = str_replace('،', 'and', $message);
        $message    = str_replace(' ', '+', $message);
        $str1       = ltrim($phone_dash, '0');
        $phone      = '92'.$str1;
        $sms        = 'http://smsctp3.eocean.us:24555/api?action=sendmessage&username=netsol&password=32JNoi90&recipient='.$phone.'&originator=99095&messagedata='.$message.'';
        $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,$sms);
        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
            
        }
        $oAppointmentPayment = AppointmentPayment::where('id', $oAppointmentPayment->id)->with(['appointmentId','verifiedBy','bankId'])->first();
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Appointment Payment"]), $oAppointmentPayment, false);
    	$this->urlRec(34, 1, $oResponse);    
        
        return $oResponse;
    }
    
    public function show($id)
    {
    	$oAppointmentPayment = AppointmentPayment::with(['appointmentId','verifiedBy','bankId'])->findOrFail($id);

    	if (!Gate::allows('appointment-payment-show', $oAppointmentPayment))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

		$oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Appointment Payment"]), $oAppointmentPayment, false);
        
        $this->urlRec(34, 2, $oResponse);

		return $oResponse;

    }
    
    public function update(Request $request, $id){
        
        $oInput = $request->all();
        
        $oValidator = Validator::make($oInput,[
            'status'        => 'required|in:pending,processed,canceled',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oAppointmentPayment = AppointmentPayment::findOrFail($id); 
        
        $oAppointment = Appointment::where('id',$oAppointmentPayment->appointment_id)->with(['patientId'])->first();
        
        if(!isset($oAppointment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);

        if($oAppointment->status != 'pending' && $oAppointment->status !='awaiting_confirmation')
            return responseBuilder()->error(__('You are not allowed to update because appointment already proceed'), 404, false);

        if (!Gate::allows('appointment-payment-update',$oAppointmentPayment))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oldStatus      = $oAppointment->status;
        $oldAppointment = $oAppointment;
        $oPayment= $oAppointmentPayment->update([
            "status"       => isset($oInput['status'])?$oInput['status']:'pending',
            'verified_by'  =>  Auth::user()->id,
            'verified_at'  =>  Carbon::now()->toDateTimeString(),
            
        ]);
        if($oInput['status'] == 'processed'){
            $oAppointment->update([
                'status' => 'approved',
                'paid_status' => 'paid',
            ]);
            $oAppointment = Appointment::where('id',$oAppointmentPayment->appointment_id)->with(['slotId','patientId','doctorId','centerId','treatmentId'])->first();
            $this->onAppointmentApprove($oAppointment);
            
         
            // appointmentStatusChange($oAppointment,$oldAppointment); 
        
        }elseif($oInput['status'] == 'canceled'){
            $patient = $oAppointment->patientId;
            $emailTitle = "Evidence Failed";
            $n = '\n';
            $message = "Evidence Failed Sorry! There is a problem with uploaded evidence. Kindly check and upload a valid receipt of payment.";
            if(isset($patient->phone)){
                
             $phone_dash = preg_replace("/[^0-9]/", "", $patient->phone);
            $message    = str_replace('&', 'and', $message);
            $message    = str_replace('،', 'and', $message);
            $message    = str_replace(' ', '+', $message);
            $str1       = ltrim($phone_dash, '0');
            $phone      = '92'.$str1;
            $sms        = 'http://smsctp3.eocean.us:24555/api?action=sendmessage&username=netsol&password=32JNoi90&recipient='.$phone.'&originator=99095&messagedata='.$message.'';
            $curl_handle=curl_init();
            curl_setopt($curl_handle,CURLOPT_URL,$sms);
            curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
            curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
            $buffer = curl_exec($curl_handle);
            curl_close($curl_handle);
                //smsGateway($patient->phone,$message);
            }
            if(isset($patient->email)){
                emailGateway($patient->email,$message,$emailTitle);   
            }
            $oAppointment->update([
                'status' => 'pending'
            ]);
        }

        $oAppointmentPayment = AppointmentPayment::with(['appointmentId','verifiedBy','bankId'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Appointment Payment Status"]), $oAppointmentPayment, false);
        
        $this->urlRec(34, 3, $oResponse);
        
        return $oResponse;
    }
    public function render(Request $request, $url)
    {
    	$sFileUrl = decrypt($url);

    	$oAppointmentPayment = AppointmentPayment::where('evidence_url', $sFileUrl)->first();
        
        if(!isset($oAppointmentPayment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
        
        if (!Gate::allows('appointment-payment-render', $oAppointmentPayment))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        // $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Appointment Payment"]), $oAppointmentPayment, false);    	
        // $this->urlRec(34, 4, $oResponse);

        // return response(Storage::disk('s3')->get($sFileUrl))->header('Content-Type', 'image/png');
        $file = Storage::disk('local')->get($sFileUrl);
        $data['image'] = base64_encode($file);
        $data['file_type'] = $oAppointmentPayment->file_type;
        $data['mime_type'] = $oAppointmentPayment->mime_type;
        $data['file_name'] = $oAppointmentPayment->file_name;

        $oResponse = responseBuilder()->success(__('message.EMR.list'), $data, false);

        return $oResponse;
    }

    public function refundAppointments(Request $request)
    {
        if (!Gate::allows('appointment-refund-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oInput = $request->all();

        $oQb =  PaymentRefund::orderByDesc('updated_at');

        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"payment_method",$oQb);
        $oQb = QB::where($oInput,"status",$oQb);
        $oQb = QB::where($oInput,"old_status",$oQb);
        $oQb = QB::where($oInput,"refund_charges",$oQb);

        $oAppointmentRefunds = $oQb->paginate();
        foreach ($oAppointmentRefunds as $appointmentRefund) {
            $appointmentRefund['appointment_id'] = Appointment::where('id',$appointmentRefund->appointment_id)->with(['doctorId','patientId'])->withTrashed()->first();
        }
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Appointment Payment"]), $oAppointmentRefunds, false);

        $this->urlRec(34, 0, $oResponse);

        return $oResponse;
    }

    public function refundPayment(Request $request,$id)
    {
        $oInput = $request->all();
        $oValidator = $this->validateRefundRequest($oInput);

        if ($oValidator['error'] == true) {
            abort(400,$oValidator['message']);
        }
        $oRefundAppointment =  PaymentRefund::findOrFail($id);
        
        if (!Gate::allows('appointment-refund-update',$oRefundAppointment))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if ($oRefundAppointment){
            if (isset($oInput['refund_charges']) && $oRefundAppointment->amount < $oInput['refund_charges']) {
            return responseBuilder()->error('Refund Charges cannot be greater than the Actual Amount' ,403, false);  
            }
            $refundUpdate                   =   $oRefundAppointment->update([
                'status'                    => $oInput['status'],
                'comments'                  => isset($oInput['comments']) ? $oInput['comments'] : $oRefundAppointment->comments,
                'refund_charges'            => isset($oInput['refund_charges']) ? $oInput['refund_charges'] : $oRefundAppointment->refund_charges,
                'payment_method'            => isset($oInput['payment_method']) ? $oInput['payment_method'] : $oRefundAppointment->payment_method,
                'patient_account_number'    => isset($oInput['patient_account_number']) ? $oInput['patient_account_number'] : $oRefundAppointment->patient_account_number,

                //For completed Status
                'paid_by'                   => ($oInput['status']  === 'completed') ? Auth::user()->id :null,
                'paid_datetime'             => ($oInput['status']  === 'completed') ? $oInput['paid_datetime'] :null,

                'updated_by'                => Auth::user()->id,
                'updated_at'                => Carbon::now()->toDateTimeString(),
            ]);
        }
        $oRefundAppointment =  PaymentRefund::findOrFail($id);
        $oRefundAppointment['appointment_id'] = Appointment::where('id',$oRefundAppointment->appointment_id)->with(['doctorId','patientId'])->first();
        if ($refundUpdate) {
            $bSource     =   'admin';
            patientRefundNotification($oRefundAppointment,$bSource);
            return responseBuilder()->success(__('Refund status updated Successfully'), $oRefundAppointment, false);
        }
        return responseBuilder()->error(__('Could not updated Status'), $oRefundAppointment, false);
    }

    private function validateRefundRequest($oInput)
    {
        $oValidator = Validator::make($oInput,[
            'status'                    => 'required|in:initiated,in_progress,completed,rejected',
            'comments'                  => 'present|max:250',
            'refund_charges'            => 'present|max:5|min:0',
            'payment_method'            => 'present',
            'patient_account_number'    => 'present|max:30',
        ]);
        if($oValidator->fails()){
            return [
                'error' => true,
                'message' => $oValidator->errors()->first(),
            ];
        }
    	
        if ($oInput['status'] === 'in_progress' || $oInput['status'] === 'completed') {
            $oValidator = Validator::make($oInput,[
                'refund_charges'            => 'required|max:5|min:0',
                'payment_method'            => 'required|in:ep_cc,ep_otc,ep_ma,jc_otc,jc_ma,jc_cc,bank,cash',
                'patient_account_number'    => 'required|max:30',
                // 'paid_by'                   => 'present|exists:users,id',
                'paid_datetime'             => 'present|date_format:Y-m-d H:i:s',
            ]);
    
            if($oValidator->fails()){
                return [
                    'error' => true,
                    'message' => $oValidator->errors()->first(),
                ];
            }
        }
        return [
            'error' => false,
        ];
    }
    
    
     
}
