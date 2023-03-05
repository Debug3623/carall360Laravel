<?php

namespace App\Http\Controllers\DoctorApiControllers;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\Patient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Helpers\QB;
use App\PaymentSummary;
use App\Review;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function patients(Request $request)
    {
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        if (!$oAuth->isDoctor()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }       
        $oQb = Patient::orderByDesc('updated_at')->where('doctor_id',$oAuth->doctor_id)->with(['countryCode','cityId']);
        
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"ref_number",$oQb);
        $oQb = QB::whereLike($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"cnic",$oQb);
        $oQb = QB::whereLike($oInput,"address",$oQb);
        $oQb = QB::whereLike($oInput,"phone",$oQb);
        $oQb = QB::whereLike($oInput,"gender",$oQb);
        $oQb = QB::whereLike($oInput,"marital_status",$oQb);
        $oQb = QB::whereLike($oInput,"blood_group",$oQb);
        $oQb = QB::whereLike($oInput,"city_id",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        
        $oPatients = $oQb->get();
        $oPatients = userImage($oPatients);

        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Patients"]), $oPatients, false);  
        return $oResponse;
    }
    public function reviews(Request $request)
    { 
        $oAuth = Auth::user();
        
        if (!$oAuth->isDoctor()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        
        $oReview = Review::where('status','approved')->where("doctor_id",$oAuth->doctor_id)->orderByDesc('updated_at')->take(10)->get();
        
        foreach ($oReview as $review) {
            $patient = Patient::where('id',$review->patient_id)->first();
            $oUser   = User::where('patient_id',$review->patient_id)->first();
            if($oUser){
                $patient['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
            }
            $review['patient_id'] = $patient;
        }
        
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Review"]), $oReview, false);  
        return $oResponse;
    }
    public function appointments(Request $request)
    {
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        if (!$oAuth->isDoctor()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $oQb = Appointment::orderByDesc('updated_at')->where("doctor_id",$oAuth->doctor_id)->with(['slotId','centerId','treatmentId','createdBy','updatedBy']);

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
        $oQb = QB::whereLike($oInput,"appointment_type",$oQb);
        $oAppointments = $oQb->paginate(10);

        foreach ($oAppointments as $appointment) {
            $patient = Patient::where('id',$appointment->patient_id)->first();
            $oUser   = User::where('patient_id',$appointment->patient_id)->first();
            if($oUser){
                $patient['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
            }
            $appointment['patient_id'] = $patient;
        }
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Appointments"]), $oAppointments, false);
    
        return $oResponse;

    }
    public function appointmentAnalytics(Request $request)
    {
        $oInput = $request->all();
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date',
                'date_to'         => 'date|after_or_equal:date_from',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
        }
        $oAuth = Auth::user();
        
        if (!$oAuth->isDoctor()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        
        $oQb = Appointment::where("doctor_id",$oAuth->doctor_id)->get();
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oQb = $oQb->whereBetween("appointment_date",[$oInput['date_from'],$oInput['date_to']]);
        }
        $total = $oQb;
        $data['total'] = $total->count();
       
        $approved = $oQb;
        $data['approved'] = $approved->where("status",'approved')->count();

        $pending = $oQb;
        $data['pending'] = $pending->where("status",'pending')->count();

        $done = $oQb;
        $data['done'] = $done->where("status",'done')->count();

        $ongoing = $oQb;
        $data['ongoing'] = $ongoing->where("status",'ongoing')->count();
        
        $cancel_by_doctor = $oQb;
        $data['cancel_by_doctor'] = $cancel_by_doctor->whereIn("status",['cancel_by_doctor','cancel_by_patient','auto_cancel','cancel_by_staff'])->count();

        $other = $oQb;
        $data['other'] = $other->whereNotIn("status",['approved','pending','done','ongoing','cancel_by_doctor','cancel_by_patient','auto_cancel','cancel_by_staff'])->count();
        
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Appointments"]), $data, false);
        return $oResponse;
    }
    public function paymentAnalytics(Request $request)
    {
        $oAuth = Auth::user();
        
        if (!$oAuth->isDoctor()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        
        $payable = PaymentSummary::where('paid','in_progress')->where('status','payable')->where("doctor_id",$oAuth->doctor_id)->sum('system_amount');
        $collectable = PaymentSummary::where('paid','in_progress')->where('status','collectable')->where("doctor_id",$oAuth->doctor_id)->sum('system_amount');
        $data['payable'] = number_format($payable,2);
        $data['collectable'] = number_format($collectable,2);
        
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Payment Summery"]), $data, false);
        return $oResponse;
    }
    public function appointmentType()
    {
        $oAuth = Auth::user();
        if (!$oAuth->isDoctor()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $phyData = [];
        $onlineData = [];
        $aMonthData = [];
        for ($i=1; $i <= 12; $i++) {
            $phyData[] = Appointment::where("doctor_id",$oAuth->doctor_id)->where('appointment_type','physical')->whereMonth('created_at', $i)->count();
        }
        for ($i=1; $i <= 12; $i++) { 
            $onlineData[] = Appointment::where("doctor_id",$oAuth->doctor_id)->where('appointment_type','online')->whereMonth('created_at', $i)->count();
        }
        for ($i=1; $i <= 12; $i++) {
            $aAppointments  = Appointment::where("doctor_id",$oAuth->doctor_id)->whereMonth('created_at', $i)->get();
            if(count($aAppointments)>0){
                $from = Carbon::parse($aAppointments[0]->created_at);
                $aMonthData[] = $from->format('M-y');
            }else{
                $fromYear = 2021;
                $from = Carbon::parse($fromYear.'-'.$i);
                $aMonthData[] = $from->format('M-y');
            }
        }
        $physical['name']='Physical';
        $physical['data']= $phyData;
        $online['name']='Online';
        $online['data']= $onlineData;
        $series[] = $physical;
        $series[] = $online;
        $series[] = $aMonthData;

        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Appointments"]), $series, false);
        return $oResponse;

    }
}