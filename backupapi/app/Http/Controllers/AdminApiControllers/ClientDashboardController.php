<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\Patient;
use App\Doctor;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends Controller
{
    public function searchIndex(Request $request)
    {
        $oAuth = Auth::user();
        if (!$oAuth->isClient() && !$oAuth->isStaff()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $oInput = $request->all();
        $oInput['full_name'] = isset($oInput['name'])?$oInput['name']:null;
        
        $oQb  = Doctor::orderByDesc('updated_at')->with(['user']);
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;    
            $oQb  = QB::where($oInput,"organization_id",$oQb);
        }
        $oQb  = QB::whereLike($oInput,"full_name",$oQb);
        $oDoctors = $oQb->get();
        $oDoctors  = userImage($oDoctors);

        $oPQb = Patient::orderByDesc('updated_at')->with(['user']);
        if($oAuth->isClient()){

            $oInput['organization_id'] = $oAuth->organization_id;    
            $oPQb = $oPQb->whereHas('doctor', function ($p) use($oInput) {
                $p->where('organization_id', $oInput['organization_id']);
            });
            
        }
        $oPQb = QB::whereLike($oInput,"name",$oPQb);
        $oPatients = $oPQb->get();
        $oPatients = userImage($oPatients);
        $data['doctor'] = $oDoctors;
        $data['patient'] = $oPatients;
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Data"]), $data, false);  
        return $oResponse;
    }
    public function index()
    {
        $oAuth = Auth::user();
        if (!$oAuth->isClient() && !$oAuth->isStaff()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $organization_id = isset($oAuth->organization_id)? $oAuth->organization_id: 1;   
        // $organization_id = 105;       
        
        $oDQb = Doctor::orderByDesc('updated_at');
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;    
            $oDQb  = QB::where($oInput,"organization_id",$oDQb);
        }
        $data['doctors'] = $oDQb->count();
        
        $oPQb = Patient::orderByDesc('updated_at');
        if($oAuth->isClient()){

            $oInput['organization_id'] = $oAuth->organization_id;    
            $oPQb = $oPQb->whereHas('doctor', function ($p) use($oInput) {
                $p->where('organization_id', $oInput['organization_id']);
            });
        }
        $data['patients'] = $oPQb->count();

        $oAQb = Appointment::orderByDesc('updated_at');
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;    
            $oAQb = $oAQb->whereHas('doctorId', function ($p) use($oInput) {
                $p->where('organization_id', $oInput['organization_id']);
            });
        }
        $data['appointments'] = $oAQb->count();

        $oAFQb = Appointment::orderByDesc('updated_at')->where('paid_status','paid');
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;    
            $oAFQb = $oAFQb->whereHas('doctorId', function ($p) use($oInput) {
                $p->where('organization_id', $oInput['organization_id']);
            });
        }
        $data['revenue'] = $oAFQb->sum('appointment_fee');
        
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Data"]), $data, false);  
        return $oResponse;
    }
    public function onboardDoctor(Request $request)
    { 
        $oInput = $request->all();
        $oAuth = Auth::user();        
        if (!$oAuth->isClient() && !$oAuth->isStaff()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $organization_id = isset($oAuth->organization_id)? $oAuth->organization_id: 1;   
        // $organization_id = 105;       
        $doctors = Doctor::where('organization_id',$organization_id);
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date|before:tomorrow',
                'date_to'         => 'date|after_or_equal:date_from|before:tomorrow',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $doctors = $doctors->whereBetween("updated_at",[$oInput['date_from'],$oInput['date_to']]);
        }
        $doctors = $doctors->paginate(6);
        foreach ($doctors as $doctor) {
            $oUser = User::where('doctor_id',$doctor->pk)->where('organization_id',$organization_id)->whereNull('patient_id')->with('profilePic')->first();
            if($oUser){
                $doctor['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
            }
            $doctor['revenue']  = Appointment::where('doctor_id',$doctor->pk)->where('paid_status','paid')->sum('appointment_fee');
            $doctor['patient']  = Patient::where('doctor_id',$doctor->pk)->count();
        }
        
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Onboard Doctor"]), $doctors, false);  
        return $oResponse;
    }
    public function revenue()
    {
        $oAuth = Auth::user();
        
        if (!$oAuth->isClient() && !$oAuth->isStaff()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $organization_id = isset($oAuth->organization_id)? $oAuth->organization_id: 1;   
        // $organization_id = 105;
        $aData = [];
        $aMonthData = [];
        for ($i=1; $i <= 12; $i++) {
            
            $oAQb = Appointment::where('paid_status','paid')->whereMonth('created_at', $i);
            if($oAuth->isClient()){
                $oInput['organization_id'] = $oAuth->organization_id;    
                $oAQb = $oAQb->whereHas('doctorId', function ($p) use($oInput) {
                    $p->where('organization_id', $oInput['organization_id']);
                });
            }
            $aAppointments = $oAQb->get();
            
            $oDQb = Appointment::where('paid_status','paid')->whereMonth('created_at', $i);
            if($oAuth->isClient()){
                $oInput['organization_id'] = $oAuth->organization_id;    
                $oDQb = $oDQb->whereHas('doctorId', function ($p) use($oInput) {
                    $p->where('organization_id', $oInput['organization_id']);
                });
            }
            $aData[] = $oDQb->sum('appointment_fee');
            if(count($aAppointments) > 0){
                $from = Carbon::parse($aAppointments[0]->created_at);
                $aMonthData[] = $from->format('M-y');
            }else{
                $fromYear = 2021;
                $from = Carbon::parse($fromYear.'-'.$i);
                $aMonthData[] = $from->format('M-y');
            }
            
        }
        $amount['name']='Amount';
        $amount['data']= $aData;
        $amount['month']= $aMonthData;
        $series[] = $amount;
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Revenue"]), $series, false);
    
        return $oResponse;

    }
    public function appointmentStatus(Request $request)
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
        
        if (!$oAuth->isClient() && !$oAuth->isStaff()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $organization_id = isset($oAuth->organization_id)? $oAuth->organization_id: 1;   
        // $organization_id = 105;

        $oQb = Appointment::orderByDesc('updated_at')->with(['doctorId']);
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;    
            $oQb = $oQb->whereHas('doctorId', function ($p) use($oInput) {
                $p->where('organization_id', $oInput['organization_id']);
            });
        }
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oQb = $oQb->whereBetween("appointment_date",[$oInput['date_from'],$oInput['date_to']]);
        }
        $oQb = $oQb->get();
        $total = $oQb;
        $data['total'] = $total->count();
       
        $approve = $oQb;
        $data['approved'] = $approve->where("status",'approved')->count();
       
        $pending = $oQb;
        $data['pending'] = $pending->where("status",'pending')->count();

        $done = $oQb;
        $data['done'] = $done->where("status",'done')->count();

        $ongoing = $oQb;
        $data['ongoing'] = $ongoing->where("status",'ongoing')->count();

        $cancel_by_doctor = $oQb;
        $data['cancel_by_doctor'] = $cancel_by_doctor->where("status",'cancel_by_doctor')->count();

        $cancel_by_patient = $oQb;
        $data['cancel_by_patient'] = $cancel_by_patient->where("status",'cancel_by_patient')->count();

        $no_show = $oQb;
        $data['no_show'] = $no_show->where("status",'no_show')->count();

        $follow_up = $oQb;
        $data['follow_up'] = $follow_up->where("status",'follow_up')->count();

        $reschedule = $oQb;
        $data['reschedule'] = $reschedule->where("status",'reschedule')->count();

        $refund = $oQb;
        $data['refund'] = $refund->where("status",'refund')->count();

        $auto_cancel = $oQb;
        $data['auto_cancel'] = $auto_cancel->where("status",'auto_cancel')->count();

        $cancel_by_staff = $oQb;
        $data['cancel_by_staff'] = $cancel_by_staff->where("status",'cancel_by_staff')->count();

        $awaiting_confirmation = $oQb;
        $data['awaiting_confirmation'] = $awaiting_confirmation->where("status",'awaiting_confirmation')->count();
        
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Appointment Status"]), $data, false);
        return $oResponse;
    }
    public function medicineAnalytics(Request $request){
        $oInput = $request->all();
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date|before:tomorrow',
                'date_to'         => 'date|after_or_equal:date_from|before:tomorrow',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
        }
        $oAuth = Auth::user();
        
        if (!$oAuth->isClient() && !$oAuth->isStaff()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $organization_id = isset($oAuth->organization_id)? $oAuth->organization_id: 1;   
        // $organization_id = 102;
        $oQb = DB::table('prescriptions as p')
                    ->join('doctors as d','d.id','p.doctor_id')
                    ->join('doctor_medicines as dm','dm.id','p.medicine_id')
                    ->where('d.organization_id',$organization_id)
                    ->whereNull('p.deleted_at')
                    ->whereNull('d.deleted_at')
                    ->whereNotNull('p.medicine_id')
                    ->groupBy('p.medicine_id')
                    ->selectRaw('p.medicine_id,dm.medicine_name, count(p.medicine_id) AS occurrences,p.updated_at')
                    ->orderBy('occurrences','DESC')
                    ->get();
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oQb = $oQb->whereBetween("updated_at",[$oInput['date_from'],$oInput['date_to']]);
        }
        $data = $oQb->take(5);
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Medicines Analytics"]), $data, false);
        return $oResponse;
    }
    public function labTest(Request $request){
        $oInput = $request->all();
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date|before:tomorrow',
                'date_to'         => 'date|after_or_equal:date_from|before:tomorrow',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
        }
        $oAuth = Auth::user();
        if (!$oAuth->isClient() && !$oAuth->isStaff()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $organization_id = isset($oAuth->organization_id)? $oAuth->organization_id: 1;   
        // $organization_id = 102;
        $oQb = DB::table('prescription_diagnostics as p')
            ->join('doctors as d','d.id','p.doctor_id')
            ->join('diagnostics as di','di.id','p.diagnostic_id')
            ->where('d.organization_id',$organization_id)
            ->whereNull('d.deleted_at')
            ->whereNotNull('p.diagnostic_id')
            ->groupBy('p.diagnostic_id')
            ->selectRaw('p.diagnostic_id,di.name, count(p.diagnostic_id) AS occurrences,p.updated_at')
            ->orderBy('occurrences','DESC')
            ->get();
        
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oQb = $oQb->whereBetween("updated_at",[$oInput['date_from'],$oInput['date_to']]);
        }
        $data = $oQb->take(5);
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Lab Test"]), $data, false);
        return $oResponse;
    }
    public function treatmentAnalytics(Request $request){
        $oInput = $request->all();
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date|before:tomorrow',
                'date_to'         => 'date|after_or_equal:date_from|before:tomorrow',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
        }
        $oAuth = Auth::user();
        if (!$oAuth->isClient() && !$oAuth->isStaff()) {
            return responseBuilder()->error(__('You are not Allowed to accress'), 403, false);
        }
        $organization_id = isset($oAuth->organization_id)? $oAuth->organization_id: 1;   
        // $organization_id = 105;
        $oQb = DB::table('appointments as a')
            ->join('doctors as d','d.id','a.doctor_id')
            ->join('doctor_treatments as dt','dt.id','a.treatment_id')
            ->where('d.organization_id',$organization_id)
            ->whereNull('d.deleted_at')
            ->whereNull('a.deleted_at')
            ->groupBy('a.treatment_id')
            ->selectRaw('a.treatment_id,dt.treatment_name, count(a.treatment_id) AS occurrences,a.updated_at')
            ->orderBy('occurrences','DESC')
            ->get();
        
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oQb = $oQb->whereBetween("updated_at",[$oInput['date_from'],$oInput['date_to']]);
        }
        $data = $oQb->take(5);
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Treatment Analytics"]), $data, false);
        return $oResponse;
    }
}