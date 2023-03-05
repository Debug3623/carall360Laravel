<?php

use App\Appointment;
use App\Center;
use App\Doctor;
use App\Http\Libraries\ResponseBuilder;
use App\Patient;
use App\TimeSlot;
use Carbon\Carbon;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralAlert;
use App\PaymentRefund;
use App\Traits\VideoConsulatation;
use App\User;

function responseBuilder(){
        $responseBuilder = new ResponseBuilder();
        return $responseBuilder;
    }

    
    function getDataByCURL($url, $method='GET') {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return ['status' => false, 'code' => 422, 'message' => 'An Error occured while fetching data from request URL'];
        } 
        return json_decode($response);
    }
    
    function ageCalculator($dob) {
        if(!empty($dob)){
            $from = new DateTime($dob);
            $to   = new DateTime('today');
            return $from->diff($to)->y;
        }
        return 0;
    }


    function formatPhone($phone){
        $phone_str  =  trim($phone);
        if(strlen($phone) > 3){
            if($phone_str[0] == '3' || $phone_str[0] == '4' || $phone_str[0] == '5'){
                $phone      =   '0'.$phone;
                $phone = substr($phone, 0, 4) .'-'.substr($phone,4);
            } else if($phone_str[0] == 9 && $phone_str[1] == 2){
                $str2 = substr($phone_str, 2);
                $phone      =   '0'.$str2;
                $phone = substr($phone, 0, 4) .'-'.substr($phone,4);
            } else if($phone_str[0] == '0' && $phone_str[1] == '9' && $phone_str[2] == '2'){
                $str2 = substr($phone_str, 3);
                $phone      =   '0'.$str2;
                $phone = substr($phone, 0, 4) .'-'.substr($phone,4);
            } else if($phone_str[0] == '+' && $phone_str[1] == '9' && $phone_str[2] == '2'){
                $str2 = substr($phone_str, 3);
                $phone      =   '0'.$str2;
                $phone = substr($phone, 0, 4) .'-'.substr($phone,4);
            } else if($phone_str[4] == '-'){
                $phone = $phone;
            } else {
                $phone = substr($phone, 0, 4) .'-'.substr($phone,4);
            }
        }

        $iDefaultCountryCode = config('app.default_coutry_code');

        $phone = $iDefaultCountryCode.str_replace('-', '', $phone);
        


        return $phone;
    }
    function userImage($aData){
       
        foreach($aData as $data){
            $oUser = $data->user;
            if($oUser){
                $data['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
                $data['banner'] = (count($oUser->bannerPic)>0)? config("app")['url'].$oUser->bannerPic[0]->url:null;
            }
        }
        return $aData;
    }

    function chatUserImage($aData){
       
        foreach($aData as $data){
            $oUser = $data->patientUser;
            if($oUser){
                $oUser['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
            }
        }
        return $aData;
    }
    
    function DecryptId($oInput){

        foreach ($oInput['ids'] as $id) {
            $ids[] = decrypt($id);
        }
        $oInput['ids'] = $ids;
        return $oInput;
    }
    function AuthUserRoleChecker($doctor_id){
        
        $oAuth = Auth::user();
       
        if($oAuth->isClient()){
            $organization_id = $oAuth->organization_id;
            $oDoctor =  Doctor::where('organization_id',$organization_id)->findOrFail($doctor_id);
        }elseif ($oAuth->isDoctor()) {
            $doctor_id = $oAuth->doctor_id;
            $oDoctor =  Doctor::findOrFail($doctor_id);
        }elseif ($oAuth->isStaff()) {
            $oDoctor =  Doctor::findOrFail($doctor_id);
        }
        return $oDoctor;
    }
    /**
     * To send SMS notification and messages to users, following function takes one phone number
     * and one message per request to send message
     * $bUserBranding = true to include [application name] at start of SMS
     */
    
    function smsGateway($phone_number, $message, $bUseBranding = false){

        // if(app()->isLocal())
        //     return array('SUC' => true);

        //cleaning the phonenumber
        $iPhoneNumber = preg_replace("/[^0-9]/", "", $phone_number);

        $sMessage    = $message;

        $iPhoneNumber = ltrim($iPhoneNumber, '0');
        
        $iCountryCode = config('app.default_coutry_code');

        if(substr($iPhoneNumber, 0, 2) != $iCountryCode)
            $phone = $iCountryCode.$iPhoneNumber;

        // $sVendorEndpoint = 'http://smsctp3.eocean.us:24555/api';
        $sVendorEndpoint = 'https://www.support.hospitallcare.com/api/receive_sms_request';


        // //add branding to start of message
        // if($bUseBranding)
        //     $sMessage = "[".config('app.name')."] ".$sMessage;

        $aPostData = array();
        $aPostData['action']        = 'sendmessage';
        $aPostData['username']      = 'Nestol';
        $aPostData['password']      = '32JNoi90';
        // $aPostData['recipient']     = '03222555601';
        $aPostData['recipient']     = $iPhoneNumber;
        $aPostData['originator']    = '99095';
        $aPostData['messagedata']   = $sMessage;


        $oCurlHandle = curl_init();
        curl_setopt($oCurlHandle,CURLOPT_URL,$sVendorEndpoint);
        curl_setopt($oCurlHandle,CURLOPT_CONNECTTIMEOUT,2);
        curl_setopt($oCurlHandle,CURLOPT_RETURNTRANSFER,1);

        curl_setopt($oCurlHandle, CURLOPT_POST, 1);
        curl_setopt($oCurlHandle,CURLOPT_POSTFIELDS, $aPostData);
        

        $oBuffer = curl_exec($oCurlHandle);

        $sCurlError = curl_error($oCurlHandle);
        //dump($sCurlError);

        curl_close($oCurlHandle);
        
        if (empty($oBuffer))
            return array('SUC' => false, 'RES' => $sCurlError);
        
        return array('SUC' => true);
    }

    function emailGateway($email, $message, $title = "Appointment Alert"){
        
        dispatch(new SendEmail(Mail::to($email)->send(new GeneralAlert( $title, $message))));
        return "Email Sended";
    }
    function updateAppointmentStatus($oAppointment,$oInput,$oldStatus){
        $appointment  = $oAppointment->update([
            "status"            => isset($oInput['status'])?$oInput['status']:$oldStatus,
            'updated_by'        =>  Auth::user()->id,
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
            
        ]);
        return $appointment;
    }
    function followUpStatusUpdate($oAppointment,$oInput,$oldStatus){
        if (!Gate::allows('appointment-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            // dd($oAppointment,$oInput,$oldStatus);
            
        $oInput['doctor_id']        = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id']       = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        $oInput['paid_status']      = isset($oInput['paid_type'])?(($oInput['paid_type'] == 'paid')?'unpaid':'paid'):null;
        $oInput['fee']              = isset($oInput['paid_type'])?(($oInput['paid_type'] == 'paid')?(isset($oInput['fee']) ? $oInput['fee']:null):0):null;
        $oInput['discount_fee']     = isset($oInput['paid_type'])?(($oInput['paid_type'] == 'paid')?(isset($oInput['discount_fee']) ? $oInput['discount_fee']:null):0):null;
        $oInput['is_settled']       = isset($oAppointment['is_settled'])?$oAppointment['is_settled']:null;
        $oInput['parent_id']        = isset($oAppointment['id'])?$oAppointment['id']:null;
        $tempStatus['status']       = 'done'; //T0 change the status of previous Appointment
        $oInput['status']           = isset($oInput['status'])?$oInput['status']:'follow_up';
        $oValidator = Validator::make($oInput,[
            'lead_from'     => 'required|in:doctor,careall,website,patient',
            'paid_type'     => 'required|in:paid,free',
            'paid_status'   => 'required|in:paid,unpaid',
            'status'        => 'required|in:pending,approved,cancel_by_doctor,cancel_by_patient,cancel_by_adminStaff,no_show,done,ongoing,follow_up,reschedule,refund,auto_cancel',
            'type'          => 'required|in:online,physical',
            'fee'           => 'required|max:5|min:0',
            'discount_fee'  => 'required|max:5|min:0',
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
        // dd($oAppointment,$oInput,$oldStatus);
        $oPatient = Patient::where('id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oPatient))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);

        $selectedDateTime = Carbon::createFromTimestamp(strtotime($oInput['date'] . $oInput['slot']))->toDateTimeString();    
        $currentDateTime = Carbon::now()->toDateTimeString();    
        if($currentDateTime >= $selectedDateTime){
            return responseBuilder()->error(__('message.schedule.previous'), 404, false);
        }
        $oNewAppointment = bookAppointment($oInput);
        $oNewAppointment = Appointment::with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($oNewAppointment->id);
        // VideoConsulatation::onAppointmentApprove($oNewAppointment);
        // $changeOldAppointmentStatus     =   updateAppointmentStatus($oAppointment,$tempStatus,$oldStatus);
        $appointment    = $oAppointment->update([
            "status"            =>  $tempStatus['status'],
            'is_again'          =>  1,
            'updated_by'        =>  Auth::user()->id,
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
        ]);
        // $oResponse = responseBuilder()->success(__('message.general.reschedule',["mod"=>"Appointment"]), $oNewAppointment, false);
        // $this->urlComponents(config("businesslogic")[26]['menu'][1], $oResponse, config("businesslogic")[26]['title']);
        return $oNewAppointment;
    }
    function rescheduleStatusUpdate($oAppointment,$oInput,$oldStatus){

        if (!Gate::allows('appointment-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            // dd($oAppointment,$oInput,$oldStatus);
            
        $oInput['doctor_id']        = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id']       = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        $oInput['paid_status']      = isset($oAppointment['paid_status'])?$oAppointment['paid_status']:null;
        $oInput['fee']              = isset($oAppointment['original_fee'])?$oAppointment['original_fee']:null;
        $oInput['discount_fee']     = isset($oAppointment['appointment_fee'])?$oAppointment['appointment_fee']:null;
        $oInput['is_settled']       = isset($oAppointment['is_settled'])?$oAppointment['is_settled']:null;
        $oInput['parent_id']        = isset($oAppointment['id'])?$oAppointment['id']:null;
        $tempStatus['status']       = isset($oInput['status'])?$oInput['status']:'reschedule'; //TO change the status of previous Appointment
        $oInput['status']           = ($oAppointment['paid_status'] == 'paid')?'approved':'pending';
        $oValidator = Validator::make($oInput,[
            'lead_from'     => 'required|in:doctor,careall,website,patient',
            'paid_status'   => 'required|in:paid,unpaid',
            'status'        => 'required|in:pending,approved,cancel_by_doctor,cancel_by_patient,cancel_by_adminStaff,no_show,done,ongoing,follow_up,reschedule,refund,auto_cancel',
            'type'          => 'required|in:online,physical',
            'fee'           => 'required|max:5|min:0',
            'discount_fee'  => 'required|max:5|min:0',
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
        // dd($oAppointment,$oInput,$oldStatus);
        $oPatient = Patient::where('id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oPatient))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);

        $selectedDateTime = Carbon::createFromTimestamp(strtotime($oInput['date'] . $oInput['slot']))->toDateTimeString();    
        $currentDateTime = Carbon::now()->toDateTimeString();    
        if($currentDateTime >= $selectedDateTime){
            return responseBuilder()->error(__('message.schedule.previous'), 404, false);
        }
        $oNewAppointment = bookAppointment($oInput);
        $oNewAppointment = Appointment::with(['slotId','centerId','treatmentId','patientId','doctorId','MedicalRecord','createdBy','updatedBy','deletedBy','restoredBy'])->findOrFail($oNewAppointment->id);
        // VideoConsulatation::onAppointmentApprove($oNewAppointment);
        // $changeOldAppointmentStatus     =   updateAppointmentStatus($oAppointment,$tempStatus,$oldStatus);
        $appointment    = $oAppointment->update([
            "status"            =>  $tempStatus['status'],
            "original_fee"      =>  0,
            "appointment_fee"   =>  0,
            "tele_url"          =>  null,
            "tele_password"     =>  null,
            "patient_token"     =>  null,
            "doctor_token"      =>  null,
            "is_again"          =>  1,
            'updated_by'        =>  Auth::user()->id,
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
        ]);
        // $oResponse = responseBuilder()->success(__('message.general.reschedule',["mod"=>"Appointment"]), $oNewAppointment, false);
        // $this->urlComponents(config("businesslogic")[26]['menu'][1], $oResponse, config("businesslogic")[26]['title']);
        return $oNewAppointment;
    }
    
    function refundStatusUpdate($oAppointment,$oInput,$oldStatus){
        $oValidator = Validator::make($oInput,[
            'reason'                => 'present|max:500',
            ]);
            
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $updatePreviousAppointmentStatus    = $oAppointment->update([
                "status"            =>  'refund',
                "is_again"          =>  1,
                "tele_url"          =>  null,
                "tele_password"     =>  null,
                "patient_token"     =>  null,
                "doctor_token"      =>  null,
                'updated_by'        =>  Auth::user()->id,
                'updated_at'        =>  Carbon::now()->toDateTimeString(),
            ]);
        if ($updatePreviousAppointmentStatus) {
            $insertRefund   =    PaymentRefund::insert([
                'appointment_id'                => $oAppointment->id,
                'amount'                        => $oAppointment->appointment_fee,
                // 'refund_charges'                => $oInput->refund_charges,
                'status'                        => 'initiated',
                'old_status'                    => $oldStatus,
                // 'patient_account_number'        => $oInput->patient_account_number,
                // 'paid_datetime'                 => Carbon::now()->toDateTimeString(),
                // 'paid_by'                       => Auth::user()->id,
                'reason'                        => $oInput['reason'],
                'created_at'                    =>  Carbon::now()->toDateTimeString(),
                'created_by'                    =>  Auth::user()->id,
            ]);
            if ($insertRefund) {
                $bSource = 'patient';
                patientRefundNotification($oAppointment,$bSource);
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    function appointmentStatusChange($appointment,$oldAppointment){
        $newStatus = $appointment->status;
        $oldStatus = $oldAppointment->status;
        
        $patientEmail =  $appointment->patientId->email;
        $patientPhone =  $appointment->patientId->phone;
        $patientName  =  $appointment->patientId->name;

        $clinic       =  isset($appointment->center_id) ? centerName($appointment->center_id):'';
        $reference    =  $appointment->reference_no;

        $doctorEmail  =  $appointment->doctorId->email;
        $doctorPhone  =  $appointment->doctorId->phone;
        $doctorName   =  $appointment->doctorId->title.' '.$appointment->doctorId->full_name;
        $timeSlot     = TimeSlot::where('id',$appointment->slot_id)->first();
        $date         = Carbon::parse($appointment->appointment_date);                                                
        $fdate        = $date->format('jS F Y');
        $time         = date('h:i A', strtotime($timeSlot->slot));
        
        //Payment Time Limit
        $payment_date = Carbon::parse($appointment->payment_timelimit);                                                
        $pdate        = $payment_date->format('jS F Y');
        $ptime        = date('h:i A', strtotime($appointment->payment_timelimit));
        
        // old Appointment Details
        $oldTimeSlot  = TimeSlot::where('id',$oldAppointment->slot_id)->first();
        $olddate      = Carbon::parse($oldAppointment->appointment_date);                                               
        $oldfdate     = $olddate->format('jS F Y');
        $oldtime      = date('h:i A', strtotime($oldTimeSlot->slot));

        $n            = '\n';
            $doctorMessage = null;
            $patientMessage = null;
            $emailTitle = '';
            if($newStatus == "pending"){
                $emailTitle = 'Appointment is Pending';                   
                $patientMessage = "$patientName, appointment is booked. Your appointment tracking number: $reference. Please proceed with the payment before $ptime. on $pdate to avoid cancellation. For help, call 0322-2555601, 0322-2555363";
            }elseif($newStatus == 'approved'){
                $emailTitle = 'Appointment Approved';
                if($appointment->appointment_type == 'physical'){
                    if($oldStatus == 'reschedule'){
                        $emailTitle = 'Appointment reschedule';
                        $patientMessage = "Your appointment with $doctorName on $oldfdate at $oldtime is rescheduled to $fdate at $time. Appointment Tracking number: $reference".$n."Clinic: $clinic".$n." For assistance, please contact: 0322-2555601 - 0322-2555363.";
                        $doctorMessage = "Your appointment with $patientName on $oldfdate at $oldtime is rescheduled to $fdate at $time. For assistance, please contact: 0322-2555601 - 0322-2555363.";
                    } else {
                        $patientMessage = "Appointment confirmed! Details to your booked appointment are:".$n.$n."Doctor: $doctorName.".$n."Date: $fdate ".$n."Time: $time".$n."Clinic: $clinic".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
                        $doctorMessage  = "You have a confirmed appointment as per the details:".$n.$n."Patient: $patientName.".$n."Date: $fdate ".$n."Time: $time".$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
                    }
                }else{
                    if($oldStatus == 'reschedule'){
                        $emailTitle = 'Appointment reschedule';
                        $patientMessage = "Your appointment with $doctorName on $oldfdate at $oldtime is rescheduled to $fdate at $time. Appointment Tracking number: $reference".$n." For assistance, please contact: 0322-2555601 - 0322-2555363.";
                        $doctorMessage = "Your appointment with $patientName on $oldfdate at $oldtime is rescheduled to $fdate at $time. For assistance, please contact: 0322-2555601 - 0322-2555363.";
                    } else {
                        $patientMessage = "Appointment confirmed! Details to your booked appointment are:".$n.$n."Doctor Name: $doctorName.".$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
                        $doctorMessage  = "You have a confirmed appointment as per the details:".$n.$n."Patient Name: $patientName.".$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
                    }
                }
                // $patientMessage = "Appointment confirmed! Details to your booked appointment are:".$n.$n."Doctor: $doctorName".$n."Date: $fdate ".$n."Time: $time".$n.$n."For Queries: 0322-2555601,".$n."0322-2555363";
                // $doctorMessage  = "You have an appointment with $patientName.".$n.$n."Date: $fdate ".$n."Time: $time ".$n.$n."For Queries: 0322-2555601,".$n."0322-2555363";
            }elseif($newStatus == 'cancel_by_patient'){
                $emailTitle = 'Appointment Cancelled by Patient';
                $patientMessage = "Dear $patientName,".$n.$n."Your appointment has been Cancelled with $doctorName.".$n.$n."Appointment Tracking number: $reference".$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
                $doctorMessage  = "Dear $doctorName,".$n.$n."Your appointment has been Cancelled with $patientName.".$n.$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";                
            }elseif($newStatus == 'cancel_by_doctor'){
                $emailTitle = 'Appointment Cancelled by Doctor';
                $patientMessage = "Your appointment has been Cancelled with $doctorName.".$n.$n."Appointment Tracking number: $reference".$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";                
                $doctorMessage  = "Your appointment has been Cancelled with $patientName.".$n.$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
            }elseif($newStatus == 'no_show'){
                $emailTitle = 'No Show Appointment';
                $patientMessage = "Due to unavailability, your appointment has been cancelled with:".$n.$n."Doctor: $doctorName ".$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
                $doctorMessage = "Due to unavailability, your appointment has been cancelled with:".$n.$n."Doctor: $patientName ".$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
            }elseif($newStatus == 'done'){
                $emailTitle = 'Appointment Done';
                $patientMessage = "Thank you for availing consultation service with $doctorName through CliniCALL. We wish you a speedy recovery. For further queries, please contact 0322-2555601 - 0322-2555363";
                $doctorMessage  = "Your appointment has been done with $patientName.".$n.$n."Date: $fdate ".$n."Time: $time For further queries, please contact 0322-2555601 - 0322-2555363";
            }elseif($newStatus == 'ongoing'){
                $emailTitle = 'OnGoing Appointment';
                $patientMessage = "Your appointment is on-going with $doctorName on $fdate at $time. For assistance, please contact: 0322-2555601 - 0322-2555363";
                $doctorMessage = "Your appointment is on-going with $patientName on $fdate at $time. For assistance, please contact: 0322-2555601 - 0322-2555363";
            }elseif($newStatus == 'follow_up'){
                $emailTitle = 'Follow Up Appointment';
                if ($appointment->paid_status == 'unpaid') {
                    $patientMessage = "$patientName, your Follow Up appointment is booked. Your appointment tracking number is: $reference. Please proceed with the payment before $ptime. on $pdate to avoid cancellation. For help, call 0322-2555601, 0322-2555363";
                } else {
                    $patientMessage = "Your follow up appointment is booked with $doctorName on $fdate at $time Appointment Tracking number: $reference".$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
                    $doctorMessage = "Your follow up appointment is booked with $patientName on $fdate at $time. For assistance, please contact: 0322-2555601 - 0322-2555363";
                }
            }
            elseif($newStatus == 'refund'){
                $emailTitle = 'Appointment Fee refund';
                $patientMessage = "Dear $patientName,".$n.$n."Your appointment has been Cancelled with $doctorName.".$n.$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
                $doctorMessage  = "Dear $doctorName,".$n.$n."Your appointment has been Cancelled with $patientName.".$n.$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
            }
            elseif($newStatus == 'auto_cancel'){
                $emailTitle = 'Appointment Auto Cancelled';
                $patientMessage = "Dear $patientName,".$n.$n."Your appointment has been Cancelled with $doctorName.".$n.$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
                $doctorMessage  = "Dear $doctorName,".$n.$n."Your appointment has been Cancelled with $patientName.".$n.$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
            }elseif($newStatus == 'pending'){
                $emailTitle = 'Appointment is Pending';                   
                $patientMessage = "$patientName, appointment is booked. Your appointment tracking number: $reference. Please proceed with the payment before $ptime. on $pdate to avoid cancellation. For help, call 0322-2555601, 0322-2555363";
            }
            if(isset($patientMessage) && isset($patientPhone)){
                smsGateway($patientPhone, $patientMessage);
            }
            if(isset($patientMessage) && isset($patientEmail)){
                $patientMessage = str_replace('\n', '<br>', $patientMessage);
                emailGateway($patientEmail, $patientMessage, $emailTitle);
            }
            if(isset($doctorMessage) && isset($doctorPhone)){
                smsGateway($doctorPhone, $doctorMessage);
            }
            if(isset($doctorMessage) && isset($doctorEmail)){
                $doctorMessage = str_replace('\n', '<br>', $doctorMessage);
                emailGateway($doctorEmail, $doctorMessage, $emailTitle);
            }
     return $newStatus; 
    } 
    function isAllowed($user, $permission_code, $type = 'allow'){        
        $sSql = "

            SELECT 
                A.id
            FROM 
                users A,
                role_user B,
                permissions C,
                roles D,
                permission_role E
            WHERE 
                A.id = B.user_id AND
                B.role_id = D.id AND
                C.permission_code = ? AND
                A.id = ? AND
                D.id = E.role_id AND
                C.id = E.permission_id AND
                C.is_active = ? AND 
                C.type = ? AND
                C.deleted_at is null
        ";
        $aBindings = array($permission_code, $user->id, 1, $type);

        $oResult = DB::select(DB::raw($sSql), $aBindings);
        //dd($oResult);

        if(isset($oResult[0]->id) && $oResult[0]->id == $user->id)
            return true;
        return false;

        $aPerCount = App\Permission::with(['roles.users' => function($oQuery) use ($user){
                            dump("ID  ".$user->id);
                            $oQuery->where('id', $user->id);
                        }])
                        ->where('permission_code', $permission_code)
                        ->where('is_active', 1)
                        ->where('type', $type)
                        ->first()->toArray();
                        

        dd($aPerCount);

        if(!isset($aPerCount['roles'][0]['users'][0]['id']))
            return false;

        if($aPerCount['roles'][0]['users'][0]['id'] == $user->id)
            return true;

        /*
        dump($aPerCount['roles'][0]['users'][0]['id']);
        dd($aPerCount);
        if($aPerCount > 0)
            return true;
        */

        return false;
    }
    
    function bookAppointment($oInput){
        $current_time = Carbon::now()->toDateTimeString();
        $appointment_time = Carbon::createFromTimestamp(strtotime($oInput['date'] . $oInput['slot']))->toDateTimeString();
        $timestamp = (strtotime($current_time)+strtotime($appointment_time))/2 ;
        $payment_timelimit = date('Y-m-d H:i:s',$timestamp);
       
        $appointment = Appointment::create([
            "lead_from"         => $oInput['lead_from'],
            "treatment_id"      => $oInput['treatment_id'],
            "center_id"         => $oInput['center_id'],
            "original_fee"      => isset($oInput['fee'])?$oInput['fee']:0,
            'reference_no'      => strtoupper(uniqid()),
            "paid_status"       => isset($oInput['paid_status'])?$oInput['paid_status']:'unpaid',
            "status"            => isset($oInput['status'])?$oInput['status']:'pending',
            "doctor_remarks"    => isset($oInput['doctor_remarks'])?$oInput['doctor_remarks']:null,
            "parent_id"         => isset($oInput['parent_id'])?$oInput['parent_id']:null,
            "slot_id"           => $oInput['slot_id'],
            "patient_id"        => $oInput['patient_id'],
            "doctor_id"         => $oInput['doctor_id'],
            "appointment_type"  => $oInput['type'],
            "appointment_fee"   => isset($oInput['discount_fee'])?$oInput['discount_fee']:0,
            "payment_timelimit" => $payment_timelimit,
            "appointment_date"  => $oInput['date'],
            'created_by'        =>  Auth::user()->id,
            'updated_by'        =>  Auth::user()->id,
            'created_at'        =>  Carbon::now()->toDateTimeString(),
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
            
        ]);

            $reference    =  $appointment->reference_no;
            $patient      = patient($appointment->patient_id);
            $patientName  = $patient->name;
            $patient_phone= $patient->phone;
            $patient_email= $patient->email;
            $date         = Carbon::parse($oInput['date']);                                                // Appointment date
            $fdate        = $date->format('jS F Y');
            $time         = date('h:i A', strtotime($oInput['slot']));
            $payment_date = Carbon::parse($appointment->payment_timelimit);                                                // Appointment date
            $pdate        = $payment_date->format('jS F Y');
            $ptime        = date('h:i A', strtotime($appointment->payment_timelimit));
            $n            = '\n';
            if($appointment->status === "pending"){
                $patientMessage = "$patientName, appointment is booked. Your appointment tracking number: $reference. Please proceed with the payment before $ptime. on $pdate to avoid cancellation. For help, call 0322-2555601, 0322-2555363";
                $sms = smsGateway($patient_phone, $patientMessage, true);
                if($patient_email){
                    $patientMessage = str_replace('\n', '<br>', $patientMessage);
                    dispatch(new SendEmail(Mail::to($patient_email)->send(new GeneralAlert("Appointment Booked", $patientMessage))));
                }
            }

            return $appointment;
    }
    function centerName($id){
        $center         =   Center::Where('id',$id)->withTrashed()->first();
        $centerName     =   $center->name;
        return $centerName;
    }
    function doctorName($id){
        $doctor             =   Doctor::Where('id',$id)->withTrashed()->first();
        $doctor_name        =   isset($doctor)? $doctor->title." ". $doctor->full_name :'';
        return $doctor_name;
    }
    function doctorEmail($id){
        $doctor             =   Doctor::Where('id',$id)->withTrashed()->first();
        $doctor_email       =   $doctor->email;
        return $doctor_email;
    }
    function doctorPhone($id){
        $doctor             =   Doctor::Where('id',$id)->withTrashed()->first();
        $doctor_phone       =   $doctor->phone;
        return $doctor_phone;
    }
    function patientName($id){
        $patient             =   Patient::Where('id',$id)->withTrashed()->first();
        $patient_name        =   $patient->name;
        return $patient_name;
    }
    
    function patientPhone($id){
        $patient             =   Patient::Where('id',$id)->withTrashed()->first();
        $patient_phone       =   $patient->phone;
        return $patient_phone;
    }
    function patient($id){
        $patient             =   Patient::Where('id',$id)->withTrashed()->first();
        return $patient;
    }
    function patientEmail($id){
        $patient             =   Patient::Where('id',$id)->withTrashed()->first();
        $patient_email       =   $patient->email;
        return $patient_email;
    }
    function centerLocation($id){
        $center         =   Center::Where('id',$id)->withTrashed()->first();
        $centerAddress  =   $center->address;
        return $centerAddress;
    }
    function centerMap($id){
        $center         =   Center::Where('id',$id)->withTrashed()->first();
        $url  = 'http://maps.google.com/?q='.$center->lat.','.$center->lng;
        return $url;
    }
    
    function AppointmentDetail($oAppointment){

        $patient     = Patient::where('id',$oAppointment->patient_id)->withTrashed()->first();
        $doctor      = Doctor::where('id',$oAppointment->doctor_id)->withTrashed()->first();
        $oPatientUser= User::where('patient_id',$oAppointment->patient_id)->withTrashed()->first();
        $oDoctorUser = User::where('doctor_id',$oAppointment->doctor_id)->withTrashed()->whereNull('patient_id')->where('organization_id',$doctor->organization_id)->first();
        
        if($oPatientUser){
            $patient['image'] = (count($oPatientUser->profilePic)>0)? config("app")['url'].$oPatientUser->profilePic[0]->url:null;
        }
        if($oDoctorUser){
            $doctor['image'] = (count($oDoctorUser->profilePic)>0)? config("app")['url'].$oDoctorUser->profilePic[0]->url:null;
        }
        $oAppointment['patient_id'] = $patient;
        $oAppointment['doctor_id'] = $doctor;
        
        return $oAppointment;
    }

    function patientRefundNotification($oAppointment,$bSource)
    {
        if ($bSource === 'admin') {
            $oRefund        =   $oAppointment;
            $oAppointment   =   $oAppointment->appointment_id;
        }
        $patientEmail =  $oAppointment->patientId->email;
        $patientPhone =  $oAppointment->patientId->phone;
        $patientName  =  $oAppointment->patientId->name;
        $doctorName   =  $oAppointment->doctorId->title.' '.$oAppointment->doctorId->full_name;
        $timeSlot     =  TimeSlot::where('id',$oAppointment->slot_id)->first();
        $date         =  Carbon::parse($oAppointment->appointment_date);                                                
        $fdate        =  $date->format('jS F Y');
        $time         =  date('h:i A', strtotime($timeSlot->slot));
        $reference    =  $oAppointment->reference_no;
        $n            = '\n';

        if ($bSource === 'patient') {
            $emailTitle = 'Appointment Fee Refund';
            $patientMessage = "Dear $patientName,".$n.$n."Your Request for refund of appointment fee has been submitted for following details. Team will respond to your Request soon!".$n.$n."Doctor:". $doctorName .$n."Appointment Tracking number: $reference".$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";

        } else if ($bSource === 'admin') {
            $sRefundStatus  =   $oRefund->status;
            if ($sRefundStatus === 'in_progress') {
                $sMessage   =   "is In Progress";
            } else if ($sRefundStatus === 'completed') {
                $sMessage   =   "is Completed, Approved and Refunded to given account number";
            }else if ($sRefundStatus === 'rejected') {
                $sMessage   =   "is Rejected by the Admin";
            } else {
                $sMessage   =   "has been initiated";
            }
            $emailTitle = 'Appointment Fee Refund Update';
            $patientMessage = "Dear $patientName,".$n.$n."Your Request for refund of appointment fee $sMessage for following details.".$n.$n."Doctor:". $doctorName .$n."Appointment Tracking number: $reference".$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601 - 0322-2555363";
        }
        if(isset($patientMessage) && isset($patientPhone)){
            smsGateway($patientPhone, $patientMessage);
        }
        if(isset($patientMessage) && isset($patientEmail)){
            $patientMessage = str_replace('\n', '<br>', $patientMessage);
            emailGateway($patientEmail, $patientMessage, $emailTitle);
        }
        return true;
    }