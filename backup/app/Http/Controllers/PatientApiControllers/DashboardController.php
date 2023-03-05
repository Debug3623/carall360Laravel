<?php

namespace App\Http\Controllers\PatientApiControllers;

use App\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Helpers\QB;
use App\MedicalRecord;
use App\Prescription;
use App\Review;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
    public function reviews(Request $request)
    { 
        $oAuth = Auth::user();
        
        if (!$oAuth->isPatient()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $patient_id = $oAuth->patient_id;
        // $patient_id = 215;

        $oReview = Review::where('status','approved')->where("patient_id",$patient_id)->orderByDesc('updated_at')->take(10)->get();
        
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Review"]), $oReview, false);  
        return $oResponse;
    }
    public function appointments(Request $request)
    {
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        if (!$oAuth->isPatient()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $patient_id = $oAuth->patient_id;
        $oQb = Appointment::orderByDesc('updated_at')->where("patient_id",$patient_id)->with(['slotId','centerId','treatmentId','createdBy','updatedBy']);

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
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Appointments"]), $oAppointments, false);
    
        return $oResponse;

    }
    public function payments(Request $request)
    {
        $oInput =$request->all();
        $oAuth = Auth::user();
        if (!$oAuth->isPatient()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $patient_id = $oAuth->patient_id;
        
        $allAppointments = Appointment::whereNotIn('status',Appointment::$CANCELSTATUS)->where('patient_id',$patient_id)->get();
        
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date',
                'date_to'         => 'date|after_or_equal:date_from',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $total  = $allAppointments->whereBetween("appointment_date",[$oInput['date_from'],$oInput['date_to']])->sum('appointment_fee');
            $paid   =  $allAppointments->whereBetween("appointment_date",[$oInput['date_from'],$oInput['date_to']])->where('paid_status','paid')->sum('appointment_fee');
            $unPaid =  $allAppointments->whereBetween("appointment_date",[$oInput['date_from'],$oInput['date_to']])->where('paid_status','unpaid')->sum('appointment_fee');
        }else{
            $total  = $allAppointments->sum('appointment_fee');
            $paid   =  $allAppointments->where('paid_status','paid')->sum('appointment_fee');
            $unPaid =  $allAppointments->where('paid_status','unpaid')->sum('appointment_fee');
        }
        
        $data['total_payment'] = $total;
        $data['paid_payment'] = $paid;
        $data['unpaid_payment'] = $unPaid;
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Appointments"]), $data, false);
        return $oResponse;

    }
    public function prescription(Request $request){
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        if (!$oAuth->isPatient()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
     
        $patient_id = $oAuth->patient_id;
        // $patient_id = 213;

        $oQb = Prescription::orderByDesc('updated_at')->groupBy('appointment_id')->where('patient_id',$patient_id)->with(['appointmentId','medicineId']);
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date',
                'date_to'         => 'date|after_or_equal:date_from',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb  = $oQb->whereBetween("updated_at",[$oInput['date_from'],$oInput['date_to']]);
        }
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"dose_quantity",$oQb);
        $oQb = QB::whereLike($oInput,"dose_remarks",$oQb);
        $oQb = QB::where($oInput,"is_morning",$oQb);
        $oQb = QB::where($oInput,"is_afternoon",$oQb);
        $oQb = QB::where($oInput,"is_evening",$oQb);
        $oQb = QB::where($oInput,"is_week",$oQb);
        $oQb = QB::where($oInput,"is_once",$oQb);
        $oQb = QB::where($oInput,"days_for",$oQb);
        $oQb = QB::whereLike($oInput,"file_type",$oQb);
        $oQb = QB::whereLike($oInput,"mime_type",$oQb);
        $oQb = QB::whereLike($oInput,"file_name",$oQb);
        $oQb = QB::whereLike($oInput,"url",$oQb);   
        $oPrescriptions = $oQb->take(10)->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Prescriptions"]), $oPrescriptions, false);
        return $oResponse;
    }
    public function emr(Request $request){
        
        $oInput = $request->all();    
        $oAuth = Auth::user();
     
        if (!$oAuth->isPatient()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
     
        $patient_id = $oAuth->patient_id;
        // $patient_id = 206;

        $oQb = MedicalRecord::where('patient_id', $patient_id)->orderByDesc('updated_at');

        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date',
                'date_to'         => 'date|after_or_equal:date_from',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb  = $oQb->whereBetween("updated_at",[$oInput['date_from'],$oInput['date_to']]);
        }
        
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"file_type",$oQb);
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::whereLike($oInput,"file_name",$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"url",$oQb);

        $oFiles = $oQb->take(10)->get();
        
		$oResponse = responseBuilder()->success(__('message.EMR.list'), $oFiles, false);

		return $oResponse;
    }
}