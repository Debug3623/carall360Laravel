<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Appointment;
use App\DoctorReceipt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\PaymentSummary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceAlert;
use App\Jobs\SendEmail;
class PaymentSummaryController extends Controller
{
    use \App\Traits\WebServicesDoc; 

    public function index(Request $request)
    {
        // if (!Gate::allows('payment-summary-index') && !Gate::allows('revenue'))
        //     return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oAuth = Auth::user();
        $oInput = $request->all();
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;

        $oQb = PaymentSummary::orderByDesc('updated_at')->with(['doctorId','paidBy','createdBy','updatedBy','deletedBy','restoredBy']);
        
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;
            
            $oQb = $oQb->whereHas('doctorId', function ($q) use($oInput) {
                $q->where('organization_id', $oInput['organization_id']);
            });
            
        }elseif ($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;    
        }
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::whereLike($oInput,"system_amount",$oQb);
        $oQb = QB::whereLike($oInput,"status",$oQb);
        $oQb = QB::whereLike($oInput,"paid",$oQb);
        $oQb = QB::whereLike($oInput,"payment_method",$oQb);
        $oQb = QB::whereLike($oInput,"account_number",$oQb);
        $oQb = QB::whereLike($oInput,"actual_amount",$oQb);
        $oQb = QB::whereLike($oInput,"outstanding",$oQb);
        $oQb = QB::whereLike($oInput,"total_commission",$oQb);
        
        $aPaymentSummary = $oQb->paginate(20);
        
        $oResponse = responseBuilder()->success(__('message.general.list',['mod'=>'Payment Summary']), $aPaymentSummary, false);
        $this->urlComponents(config("businesslogic")[36]['menu'][0], $oResponse, config("businesslogic")[36]['title']);
        return $oResponse;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $oAuth = Auth::user();
        
        $oQb = PaymentSummary::with(['doctorId','paidBy','createdBy','updatedBy','deletedBy','restoredBy']);
        if($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;  
            $oQb = QB::where($oInput,"doctor_id",$oQb);
        }
        $oPaymentSummary = $oQb->findOrFail($id);
        $oDoctorReceipts =   DoctorReceipt::where('summary_id',$id)->get();

        foreach ($oDoctorReceipts as $oDoctorReceipt) {
            $oAppointment = Appointment::with(['slotId','centerId','treatmentId'])->where('id',$oDoctorReceipt->appointment_id)->first();
            if(isset($oAppointment)){
                $oAppointment = AppointmentDetail($oAppointment);
            }
            $oDoctorReceipt['appointment_id'] = $oAppointment;
        }
        $oPaymentSummary['DoctorReceipts'] = $oDoctorReceipts;
        $oResponse = responseBuilder()->success(__('message.general.detail',['mod'=>'Payment Summary']), $oPaymentSummary, false);
        
        $this->urlComponents(config("businesslogic")[36]['menu'][2], $oResponse, config("businesslogic")[36]['title']);
        
        return $oResponse;
    }

    public function edit(PaymentSummary $paymentSummary)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $oInput = $request->all();
    
        $oValidator = Validator::make($oInput,[
            'status'        => 'required|in:payable,collectable',
            'paid'          => 'required|in:in_progress,completed',
            'transfer_datetime'=> 'required|date_format:Y-m-d H:i:s',
            'payment_method'=> 'required|in:cash,bank,cheque,wallet',
            'record' 	    => 'nullable|file|mimes:jpeg,jpg,png',
            'account_number'=> 'present|nullable|max:30',
            'comments'      => 'present|nullable',

        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oPaymentSummary = PaymentSummary::where('paid','in_progress')->findOrFail($id); 
        
        if (!Gate::allows('payment-summary-update',$oPaymentSummary)){
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        }
        $evidence_url = isset($oPaymentSummary->evidence_url) ? decrypt($oPaymentSummary->evidence_url) : null;
        
        $oFile = $request->file('record');
        if(isset($oFile)){
            if(isset($evidence_url)){
                Storage::disk('s3')->delete($evidence_url);
            }
            $mPutFile = Storage::disk('s3')->putFile('doctor_payment/'.md5($oPaymentSummary->doctor_id.date('U')), $oFile);
        }
        
        $oPaymentSummary = $oPaymentSummary->update([
            'status'         =>  $oInput['status'],
            'paid'           =>  $oInput['paid'],
            'payment_method' =>  $oInput['payment_method'],
            'account_number' =>  $oInput['account_number'],
            'evidence_url'   =>  isset($mPutFile)? $mPutFile : $evidence_url,
            "mime_type"      =>  isset($oFile)? $oFile->getClientMimeType(): $oPaymentSummary->mime_type,
            "file_type"      =>  isset($oFile)?$oFile->extension(): $oPaymentSummary->file_type,
            "file_name"      =>  isset($oFile)?substr($oFile->getClientOriginalName(), 0, 250): $oPaymentSummary->file_name,
            'paid_by'        =>  Auth::user()->id,
            'comments'       =>  $oInput['comments'],
            'transfer_datetime'=>  $oInput['transfer_datetime'],
            'updated_by'     =>  Auth::user()->id,
            'updated_at'     =>  Carbon::now()->toDateTimeString(),
        ]);
        $oPaymentSummary = PaymentSummary::with(['doctorId','paidBy','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($id);
        
        $oDoctorReceipts = DoctorReceipt::where('summary_id',$id)->get();        
        $oPaymentSummary['oDoctorReceipts'] =  $oDoctorReceipts;        
        
        if($oInput['paid'] == 'completed'){
            foreach ($oDoctorReceipts as $oDoctorReceipt) {
                $oDoctorReceipt->update([
                    'is_settled' => 1
                ]);
            }
            $this->doctorEmail($oPaymentSummary);
        }
        
        $oResponse = responseBuilder()->success(__('message.general.update',['mod'=>'Payment Summary']), $oPaymentSummary, false);
        
        $this->urlComponents(config("businesslogic")[36]['menu'][3], $oResponse, config("businesslogic")[36]['title']);
        
        return $oResponse;
    }
    private function doctorEmail($oPaymentSummary)
    {
        $doctor_id = $oPaymentSummary->doctor_id;
        $doctorName  = doctorName($doctor_id);
        $doctorEmail = doctorEmail($doctor_id);

        $data['doctor_name']     = $doctorName;
        $data['total']           = $oPaymentSummary->system_amount;
        $data['actual_amount']   = $oPaymentSummary->actual_amount;
        $data['net_amount']      = $oPaymentSummary->system_amount;
        $data['service_charges'] = $oPaymentSummary->total_commission;
        $data['date']            = $oPaymentSummary->transfer_datetime;

        $emailTitle  = "Invoice Paid";
        $email       = $doctorEmail;
        
        dispatch(new SendEmail(Mail::to($email)->send(new InvoiceAlert($emailTitle, $data))));

        return true;

    }
    public function paymentSummeryDetail($id)
    {
        $oAuth = Auth::user();
        
        $oQb = PaymentSummary::orderBy('updated_at','DESC');
        
        if($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;  
            $oQb = QB::where($oInput,"doctor_id",$oQb);
        }
        $oPaymentSummary = $oQb->findOrFail($id);
    
        $oDoctorReceipts =   DoctorReceipt::where('summary_id',$id)->paginate(10);

        foreach ($oDoctorReceipts as $oDoctorReceipt) {
            $oAppointment = Appointment::with(['slotId','centerId','treatmentId'])->withTrashed()->where('id',$oDoctorReceipt->appointment_id)->first();
            if(isset($oAppointment)){
                $oAppointment = AppointmentDetail($oAppointment);
            }
            $oDoctorReceipt['appointment_id'] = $oAppointment;
        }
        $oResponse = responseBuilder()->success(__('message.general.detail',['mod'=>'Payment Summary']), $oDoctorReceipts, false);
        
        $this->urlComponents(config("businesslogic")[36]['menu'][4], $oResponse, config("businesslogic")[36]['title']);
        
        return $oResponse;
    }
    public function render(Request $request, $url)
    {
    	$sFileUrl = decrypt($url);

    	$oPaymentSummary = PaymentSummary::where('evidence_url', $sFileUrl)->first();
        
        if(!isset($oPaymentSummary))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
        
        $file = Storage::disk('s3')->get($sFileUrl);
        $data['image'] = base64_encode($file);
        $data['file_type'] = $oPaymentSummary->file_type;
        $data['mime_type'] = $oPaymentSummary->mime_type;
        $data['file_name'] = $oPaymentSummary->file_name;
        $oResponse = responseBuilder()->success(__('evidence image'), $data, false);
        return $oResponse;
    }
}
