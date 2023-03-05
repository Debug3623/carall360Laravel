<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Http\Controllers\Controller;

use App\DoctorSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\DoctorScheduleDay;
use App\Partnership;
use App\TimeSlot;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DoctorScheduleController extends Controller
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
        if (!Gate::allows('doctor-schedule-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorSchedule::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"heading",$oQb);
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"fee",$oQb);
        $oQb = QB::whereLike($oInput,"discount_fee",$oQb);
        $oQb = QB::whereLike($oInput,"minimum_booking_hours",$oQb);
        $oQb = QB::whereLike($oInput,"duration",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::where($oInput,"is_primary",$oQb);
        $oQb = QB::where($oInput,"is_vocation",$oQb);
        $oQb = QB::whereBetween($oInput,"date_from",$oQb);
        $oQb = QB::whereBetween($oInput,"date_to",$oQb);
        $oQb = QB::whereBetween($oInput,"vocation_date_from",$oQb);
        $oQb = QB::whereBetween($oInput,"vocation_date_to",$oQb);
        
        $oSchedules = $oQb->get();

        foreach($oSchedules as $schedule){
            $scheduleDays = DoctorScheduleDay::where('schedule_id',$schedule->id)->get();
            if($scheduleDays){
                foreach($scheduleDays as $days){
                    $slot_array =  isset($days->slot_id)?explode(',', $days->slot_id):[];
                    $timeSlot = TimeSlot::whereIn('id',$slot_array)->select('id','slot')->get();
                    $days['slot_id'] = $timeSlot;
                }
            }
            $schedule['schedule_days'] = $scheduleDays;
        }
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Schedules"]), $oSchedules, false);
        $this->urlComponents(config("businesslogic")[20]['menu'][0], $oResponse, config("businesslogic")[20]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('doctor-schedule-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'heading'         => 'required|string|max:50|min:3',
            'type'            => 'required|in:online,physical',
            'fee'             => 'required|max:5|min:0',
            'discount_fee'    => 'required|max:5|min:0',
            'minimum_booking_hours'  => 'required',
            'duration'        => 'required',
            'is_active'       => 'required|in:0,1',
            'is_primary'      => 'required|in:0,1',
            'is_vocation'     => 'required|in:0,1',
            'date_from'       => 'required|date|after:yesterday',
            'date_to'         => 'present|nullable|date|after_or_equal:date_from',
            'vocation_date_from' => 'present|nullable|date|after:yesterday',
            'vocation_date_to'=> 'present|nullable|date|after_or_equal:vocation_date_from',
            'doctor_id'       => 'required|exists:doctors,id',
            'center_id'       => 'present|nullable|exists:centers,id',
            'color'           => 'present|nullable',
            'time_from'       => 'required',
            'time_to'         => 'required',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        if($oInput['discount_fee'] == 0){
            abort(400,'Fee should be greater then 0');
        }
        $alreadySchedules = [];
        $partnerShip = Partnership::where('doctor_id',$oInput['doctor_id'])->where('is_active',1)->first();
        if(!isset($partnerShip)){
            abort(400,'Need membership to create schedule');
        }
        if(!(strtotime($partnerShip->date_from) <= strtotime($oInput['date_from']) && strtotime($partnerShip->date_to) >= strtotime($oInput['date_to']))){
            abort(400,'Schedule should be according to membership plan');
        }
        $oDoctorScheduleCheck = DoctorSchedule::where([  
                                                    ['doctor_id','=',$oInput['doctor_id']],
                                                    // ['heading','=',$oInput['heading']],
                                                    ['type','=',$oInput['type']],
                                                    // ['center_id','=',$oInput['center_id']],
                                                    ['is_active','=',1],
                                                    ['is_vocation','=',$oInput['is_vocation']],
                                                ])->get();
        
        if(count($oDoctorScheduleCheck)>0){
            foreach ($oDoctorScheduleCheck as $key => $value) {
                $alreadySchedule = $this->dateChecker($value,$oInput);
                if(isset($alreadySchedule)){
                    $alreadySchedules[] = $alreadySchedule;
                }
            }
        }
        if(count($alreadySchedules)>0){
            return responseBuilder()->error(__('message.schedule.already'), 400, false);  
        }
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);

        $oDoctorSchedule = DoctorSchedule::create([
            'doctor_id'     => $oInput['doctor_id'],
            'center_id'     => $oInput['center_id'],
            'heading'       => $oInput['heading'],
            'type'          => $oInput['type'],
            'fee'           => $oInput['fee'],
            'discount_fee'  => $oInput['discount_fee'],
            'minimum_booking_hours'=> $oInput['minimum_booking_hours'],
            'duration'      => $oInput['duration'],
            'is_active'     => $oInput['is_active'],
            'is_primary'    => $oInput['is_primary'],
            'is_vocation'   => $oInput['is_vocation'],
            'date_from'     => $oInput['date_from'],
            'date_to'       => $oInput['date_to'],
            'time_from'     => $oInput['time_from'],
            'time_to'       => $oInput['time_to'],
            'color'         => $oInput['color'],
            'vocation_date_from'=> $oInput['vocation_date_from'],
            'vocation_date_to'  => $oInput['vocation_date_to'],
            'created_by'    => Auth::user()->id,
            'updated_by'    => Auth::user()->id,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);

        $date_from = Carbon::parse($oInput['date_from']);
        $date_to = Carbon::parse($oInput['date_to']);

        $date_diff = $date_from->diffInDays($date_to);
        $day_diff = ($date_diff < 6)? $date_diff : 6;
        for ($j=0; $j <= $day_diff; $j++) {
            $nextDateDay = null;
            $nextDateDay = $date_from->addDay($j);
            $date_from   = Carbon::parse($oInput['date_from']);
            $timestamp   = strtotime($nextDateDay);
            $day_name = date('D', $timestamp);
            $day_number = date('N', $timestamp);
            $tSlots = [];
            $oSlot_ids = [];
            for($i = strtotime($oInput['time_from']); $i<= strtotime($oInput['time_to']); $i = $i + $oInput['duration'] * 60) {
                if(strtotime($oInput['time_from']) <= $i && strtotime($oInput['time_to']) >= $i){        
                    $tSlots[] = date("H:i", $i);
                }
            }
            
            $oSlot = TimeSlot::select('id')->whereIn('slot',$tSlots)->get();
            $ARRAY=json_decode($oSlot,true);
            $ARRAY=array_column($ARRAY,'id');
            $slots=implode(',',$ARRAY); 
            $oDoctorScheduleDay = DoctorScheduleDay::create([
                'doctor_id'     => $oInput['doctor_id'],
                'schedule_id'   => $oDoctorSchedule->id,
                'day_no'        => $day_number,
                'day_name'      => $day_name,
                'is_active'     => 1,
                'slot_id'       => $slots,
                'created_at'    => Carbon::now()->toDateTimeString(),
                'updated_at'    => Carbon::now()->toDateTimeString(),
            ]);           

        }
        $oDoctorSchedule= DoctorSchedule::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oDoctorSchedule->id);
        $scheduleDays = DoctorScheduleDay::where('schedule_id',$oDoctorSchedule->id)->get();
            if($scheduleDays){
                foreach($scheduleDays as $days){
                    $slot_array =  isset($days->slot_id)?explode(',', $days->slot_id):[];
                    $timeSlot = TimeSlot::whereIn('id',$slot_array)->select('id','slot')->get();
                    $days['slot_id'] = $timeSlot;
                }
            }
        $oDoctorSchedule['schedule_days'] = $scheduleDays;
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Schedule"]), $oDoctorSchedule, false);
        
        $this->urlComponents(config("businesslogic")[20]['menu'][1], $oResponse, config("businesslogic")[20]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DoctorSchedule  $DoctorSchedule
     * @return \Illuminate\Http\Response
     */
    public function show($schedule_id)
    {
        $oSchedule = DoctorSchedule::with(['createdBy','updatedBy','deletedBy'])->findOrFail($schedule_id);
        
        if (!Gate::allows('doctor-schedule-show',$oSchedule))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"schedule"]), $oSchedule, false);
        
        $this->urlComponents(config("businesslogic")[20]['menu'][2], $oResponse, config("businesslogic")[20]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DoctorSchedule  $DoctorSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorSchedule $DoctorSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DoctorSchedule  $DoctorSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'heading'         => 'required|string|max:50|min:3',
            'type'            => 'required|in:online,physical',
            'fee'             => 'required|max:5|min:0',
            'discount_fee'    => 'required|max:5|min:0',
            'minimum_booking_hours'  => 'required',
            'duration'        => 'required',
            'is_active'       => 'required|in:0,1',
            'is_primary'      => 'required|in:0,1',
            'is_vocation'     => 'required|in:0,1',
            'date_from'       => 'required|date|after:yesterday',
            'date_to'         => 'present|nullable|date|after_or_equal:date_from',
            'vocation_date_from' => 'present|nullable|date|after:yesterday',
            'vocation_date_to'=> 'present|nullable|date|after_or_equal:vocation_date_from',
            'doctor_id'       => 'required|exists:doctors,id',
            'center_id'       => 'present|nullable|exists:centers,id',
            'color'           => 'present|nullable',
            'time_from'       => 'required',
            'time_to'         => 'required|after:time_from',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        if($oInput['discount_fee'] == 0){
            abort(400,'Fee should be greater then 0');
        }
        $partnerShip = Partnership::where('doctor_id',$oInput['doctor_id'])->where('is_active',1)->first();
        if(!isset($partnerShip)){
            abort(400,'Need membership to create schedule');
        }
        if(!(strtotime($partnerShip->date_from) <= strtotime($oInput['date_from']) && strtotime($partnerShip->date_to) >= strtotime($oInput['date_to']))){
            abort(400,'Schedule should be according to membership plan');
        }
        $alreadySchedules = [];
        $oDoctorScheduleCheck = DoctorSchedule::where([  
                                                    ['id','!=',$id],
                                                    ['doctor_id','=',$oInput['doctor_id']],
                                                    // ['heading','=',$oInput['heading']],
                                                    ['type','=',$oInput['type']],
                                                    // ['center_id','=',$oInput['center_id']],
                                                    ['is_active','=',1],
                                                    ['is_vocation','=',$oInput['is_vocation']],
                                                ])->get();
        if(count($oDoctorScheduleCheck)>0){
            foreach ($oDoctorScheduleCheck as $key => $value) {

                $alreadySchedule = $this->dateChecker($value,$oInput);
                if(isset($alreadySchedule)){
                    $alreadySchedules[] = $alreadySchedule;
                }
            }
        }
        if(count($alreadySchedules)>0){
            return responseBuilder()->error(__('message.schedule.already'), 400, false);  
        }
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);

        $oDoctorSchedule = DoctorSchedule::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('doctor-schedule-update',$oDoctorSchedule))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        if($oDoctorSchedule->discount_fee != $oInput['discount_fee'] || $oDoctorSchedule->fee != $oInput['fee'] || $oDoctorSchedule->duration != $oInput['duration'] || $oDoctorSchedule->date_from != $oInput['date_from'] || $oDoctorSchedule->date_to != $oInput['date_to'] || $oDoctorSchedule->time_to != $oInput['time_to'] || $oDoctorSchedule->time_from != $oInput['time_from']){
            // Check Appointments between new dates and time
            $oAppointments = Appointment::where('doctor_id',$oInput['doctor_id'])->whereBetween('appointment_date',[$oDoctorSchedule->date_from,$oDoctorSchedule->date_to])->where('appointment_type',$oDoctorSchedule->type)->whereIn('status',['pending','approved','ongoing','follow_up','reschedule','awaiting_confirmation'])->get();
            $appointments = [];
            $outsideDate = [];
            $new_time_from = Carbon::createFromTimestamp(strtotime($oInput['date_from'] . $oInput['time_from']))->toDateTimeString();
            $new_time_to   = Carbon::createFromTimestamp(strtotime($oInput['date_to'] . $oInput['time_to']))->toDateTimeString();
            
            if(count($oAppointments)>0){
                foreach ($oAppointments as $oAppointment) {
                    $timeSlot         = TimeSlot::where('id',$oAppointment->slot_id)->first();
                    $appointment_time = Carbon::createFromTimestamp(strtotime($oAppointment->appointment_date . $timeSlot->slot))->toDateTimeString();
                    if((strtotime($new_time_from) <= strtotime($appointment_time)) && (strtotime($new_time_to) >= strtotime($appointment_time))){
                        $appointments[] = $oAppointment;
                    }else{
                        $outsideDate[] = $oAppointment;
                    }
                }
            }
            if(count($outsideDate)>0){
                return responseBuilder()->error(__('message.schedule.appointment'), 400, false);  
            }
        }
        if($oDoctorSchedule->duration != $oInput['duration'] || $oDoctorSchedule->date_from != $oInput['date_from'] || $oDoctorSchedule->date_to != $oInput['date_to'] || $oDoctorSchedule->time_to != $oInput['time_to'] || $oDoctorSchedule->time_from != $oInput['time_from']){
            
            DoctorScheduleDay::where('schedule_id',$id)->delete();
            $date_from = Carbon::parse($oInput['date_from']);
            $date_to = Carbon::parse($oInput['date_to']);
            
            $date_diff = $date_from->diffInDays($date_to);
            $day_diff = ($date_diff < 6)?$date_diff : 6;
            for ($j=0; $j <= $day_diff; $j++) {
                $nextDateDay = null;
                $nextDateDay = $date_from->addDay($j);
                $date_from   = Carbon::parse($oInput['date_from']);
                $timestamp   = strtotime($nextDateDay);
                $day_name  = date('D', $timestamp);
                $day_number = date('N', $timestamp);
                
                $tSlots = [];
                $oSlot_ids = [];
                for($i = strtotime($oInput['time_from']); $i<= strtotime($oInput['time_to']); $i = $i + $oInput['duration'] * 60) {
                    if(strtotime($oInput['time_from']) <= $i && strtotime($oInput['time_to']) >= $i){        
                        $tSlots[] = date("H:i", $i);
                    }
                }
                $oSlot = TimeSlot::select('id')->whereIn('slot',$tSlots)->get();
                
                $ARRAY=json_decode($oSlot,true);
                $ARRAY=array_column($ARRAY,'id');
                $slots=implode(',',$ARRAY); 
                
                $oDoctorScheduleDay = DoctorScheduleDay::create([
                    'doctor_id'     => $oInput['doctor_id'],
                    'schedule_id'   => $oDoctorSchedule->id,
                    'day_no'        => $day_number,
                    'day_name'      => $day_name,
                    'is_active'     => 1,
                    'slot_id'       => $slots,
                    'created_at'    => Carbon::now()->toDateTimeString(),
                    'updated_at'    => Carbon::now()->toDateTimeString(),
                ]);           
    
            }
        }

        $oDoctorSchedule = $oDoctorSchedule->update([
            'doctor_id'     => $oInput['doctor_id'],
            'center_id'     => $oInput['center_id'],
            'heading'       => $oInput['heading'],
            'type'          => $oInput['type'],
            'fee'           => $oInput['fee'],
            'discount_fee'  => $oInput['discount_fee'],
            'minimum_booking_hours'=> $oInput['minimum_booking_hours'],
            'duration'      => $oInput['duration'],
            'is_active'     => $oInput['is_active'],
            'is_primary'    => $oInput['is_primary'],
            'is_vocation'   => $oInput['is_vocation'],
            'date_from'     => $oInput['date_from'],
            'date_to'       => $oInput['date_to'],
            'time_from'     => $oInput['time_from'],
            'time_to'       => $oInput['time_to'],
            'color'         => $oInput['color'],
            'vocation_date_from'=> $oInput['vocation_date_from'],
            'vocation_date_to'  => $oInput['vocation_date_to'],
            'updated_by'    => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);

        $oDoctorSchedule = DoctorSchedule::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        $scheduleDays = DoctorScheduleDay::where('schedule_id',$oDoctorSchedule->id)->get();
            if($scheduleDays){
                foreach($scheduleDays as $days){
                    $slot_array =  isset($days->slot_id)?explode(',', $days->slot_id):[];
                    $timeSlot = TimeSlot::whereIn('id',$slot_array)->select('id','slot')->get();
                    $days['slot_id'] = $timeSlot;
                }
            }
        $oDoctorSchedule['schedule_days'] = $scheduleDays;
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"schedule"]), $oDoctorSchedule, false);
        
        $this->urlComponents(config("businesslogic")[20]['menu'][3], $oResponse, config("businesslogic")[20]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_schedules,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allDoctorSchedule = DoctorSchedule::findOrFail($aIds);
        
        foreach($allDoctorSchedule as $oRow)
            if (!Gate::allows('doctor-schedule-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctorSchedule = DoctorSchedule::find($id);
                if($oDoctorSchedule){
                    $oDoctorSchedule->update(['deleted_by' => Auth::user()->id]);
                    $oDoctorSchedule->delete();
                }
            }
        }else{
            $oDoctorSchedule = DoctorSchedule::findOrFail($aIds);
        
            $oDoctorSchedule->update(['deleted_by' => Auth::user()->id]);
            $oDoctorSchedule->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"schedule"]));
        $this->urlComponents(config("businesslogic")[20]['menu'][4], $oResponse, config("businesslogic")[20]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$doctor_id)
    {
        $doctor_id = decrypt($doctor_id);
        if (!Gate::allows('doctor-schedule-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        $oInput = $request->all();
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorSchedule::where('doctor_id',$doctor_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"heading",$oQb);
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"fee",$oQb);
        $oQb = QB::whereLike($oInput,"discount_fee",$oQb);
        $oQb = QB::whereLike($oInput,"minimum_booking_hours",$oQb);
        $oQb = QB::whereLike($oInput,"duration",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::where($oInput,"is_primary",$oQb);
        $oQb = QB::where($oInput,"is_vocation",$oQb);
        $oQb = QB::whereBetween($oInput,"date_from",$oQb);
        $oQb = QB::whereBetween($oInput,"date_to",$oQb);
        $oQb = QB::whereBetween($oInput,"vocation_date_from",$oQb);
        $oQb = QB::whereBetween($oInput,"vocation_date_to",$oQb);

        $oSchedules = $oQb->get();

        foreach($oSchedules as $schedule){
            $scheduleDays = DoctorScheduleDay::where('schedule_id',$schedule->id)->get();
            if($scheduleDays){
                foreach($scheduleDays as $days){
                    $slot_array =  isset($days->slot_id)?explode(',', $days->slot_id):[];
                    $timeSlot = TimeSlot::whereIn('id',$slot_array)->select('id','slot')->get();
                    $days['slot_id'] = $timeSlot;
                }
            }
            $schedule['schedule_days'] = $scheduleDays;
        }
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"schedule"]), $oSchedules, false);
        
        $this->urlComponents(config("businesslogic")[20]['menu'][5], $oResponse, config("businesslogic")[20]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_schedules,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allDoctorSchedule= DoctorSchedule::onlyTrashed()->findOrFail($aIds);
        
        foreach($allDoctorSchedule as $oRow)
            if (!Gate::allows('doctor-schedule-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oDoctorSchedule = DoctorSchedule::onlyTrashed()->find($id);
                if($oDoctorSchedule){
                    $oDoctorSchedule->restore();
                }
            }
        }else{
            $oDoctorSchedule = DoctorSchedule::onlyTrashed()->findOrFail($aIds);
            $oDoctorSchedule->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"schedule"]));
        
        $this->urlComponents(config("businesslogic")[20]['menu'][6], $oResponse, config("businesslogic")[20]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oDoctorSchedule = DoctorSchedule::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('doctor-schedule-delete',$oDoctorSchedule))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorSchedule->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"schedule"]));
        
        $this->urlComponents(config("businesslogic")[20]['menu'][7], $oResponse, config("businesslogic")[20]['title']);
        
        return $oResponse;
    }
    public function timeSlot(Request $request)
    {
        $oInput = $request->all();
        if(isset($oInput['schedule_id'])){
            $oDoctorSchedule = DoctorSchedule::where('id',$oInput['schedule_id'])->select('duration','time_from','time_to','date_from','date_to')->first();
            if(!isset($oDoctorSchedule)){
                abort(400,"Enter Valid Schedule ID"); 
            }
            $tSlots = [];
            for($i = strtotime($oDoctorSchedule->time_from); $i<= strtotime($oDoctorSchedule->time_to); $i = $i + $oDoctorSchedule->duration * 60) {
                if(strtotime($oDoctorSchedule->time_from) <= $i && strtotime($oDoctorSchedule->time_to) >= $i){        
                    $tSlots[] = date("H:i", $i);
                }
            }
            $oSlot = TimeSlot::select('id','slot')->whereIn('slot',$tSlots)->get();
            $aSlot = $oSlot->pluck('id')->toArray();
            $schedule = [];
                      
            // $oDoctorScheduleDays = DoctorScheduleDay::where('schedule_id',$oInput['schedule_id'])->orderBy('day_no','ASC')->select('id','day_no','day_name','schedule_id','slot_id')->get();
            $oDoctorScheduleDays = DoctorScheduleDay::where('schedule_id',$oInput['schedule_id'])->select('id','day_no','day_name','schedule_id','slot_id')->get();
            if(count($oDoctorScheduleDays)>0){
              foreach ($oDoctorScheduleDays as $oDoctorScheduleDay) {
                $slot = [];
                    $doctor_slot_array =  isset($oDoctorScheduleDay->slot_id)?explode(',', $oDoctorScheduleDay->slot_id):[];
                    
                    foreach ($aSlot as $all) {
                        if(in_array($all, $doctor_slot_array)){
                            $oActiveSlot = TimeSlot::select('id','slot')->where('id',$all)->first();  
                            $oActiveSlot['is_active'] = 1;
                            $slot[] = $oActiveSlot;
                        }else{
                            $oActiveSlot = TimeSlot::select('id','slot')->where('id',$all)->first();  
                            $oActiveSlot['is_active'] = 0;
                            $slot[] = $oActiveSlot;
                        }
                    }
                    $oDoctorScheduleDay['slot'] = $slot;
                    
                }
            }
            
            $data['schedule'] = $oDoctorScheduleDays; 
            $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Slot List"]), $data, false);
            $this->urlComponents(config("businesslogic")[20]['menu'][8], $oResponse, config("businesslogic")[20]['title']);
        
            return $oResponse;
        }
        $oQb = TimeSlot::select('id','slot');

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"slot",$oQb);
        
        $oSlot = $oQb->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Slot List"]), $oSlot, false);
        $this->urlComponents(config("businesslogic")[20]['menu'][8], $oResponse, config("businesslogic")[20]['title']);
        
        return $oResponse;
    }
    public function updateVocationMood(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'is_vocation'     => 'required|in:0,1',
            'vocation_date_from' => 'required|date|after:yesterday',
            'vocation_date_to'=> 'present|nullable|date|after_or_equal:vocation_date_from',
            'doctor_id'       => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oDoctor = AuthUserRoleChecker($oInput['doctor_id']);

        $oDoctorSchedule = DoctorSchedule::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('doctor-vocation-update',$oDoctorSchedule))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorSchedule = $oDoctorSchedule->update([
            'doctor_id'     => $oInput['doctor_id'],
            'is_vocation'   => $oInput['is_vocation'],
            'vocation_date_from'=> $oInput['vocation_date_from'],
            'vocation_date_to'  => $oInput['vocation_date_to'],
            'updated_by'    => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorSchedule = DoctorSchedule::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Vocation Mood"]), $oDoctorSchedule, false);
        
        $this->urlComponents(config("businesslogic")[20]['menu'][9], $oResponse, config("businesslogic")[20]['title']);
        
        return $oResponse;
    }
    public function doctorTimeSlots(Request $request){
       
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'date'      => 'required|date|after:yesterday',
            'type'      => 'required|in:online,physical',
            'doctor_id' => 'required|exists:doctors,id',
            'center_id' => 'present|nullable|exists:centers,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $day_name    = Carbon::parse($oInput['date'])->format('D');
        $currentDate = Carbon::now()->format('Y-m-d');
        $slots = [];
        $avalible_slots = [];
        $oDoctorSchedule = DoctorSchedule::where([  
                                                    ['doctor_id','=',$oInput['doctor_id']],
                                                    ['type','=',$oInput['type']],
                                                    ['center_id','=',$oInput['center_id']],
                                                    ['is_active','=',1],
                                                    ['is_vocation','=',0],
                                                    ['date_from','<=',$oInput['date']],
                                                    ['date_to','>=',$oInput['date']],
                                                ])->get();
        if(count($oDoctorSchedule)>0){
            foreach ($oDoctorSchedule as $schedule) {
                $schedule_id = $schedule->id;
                $minimum_booking_hours = $schedule->minimum_booking_hours;
                $timestamp = strtotime($oInput['date']) + $minimum_booking_hours*60*60;
                $day_name = date('D', $timestamp);
                $min_slot = date('H:i', $timestamp);
                if($currentDate == $oInput['date']){
                    $currentTimeStamp = strtotime(Carbon::now()) + $minimum_booking_hours*60*60;
                    $min_slot = date('H:i', $currentTimeStamp);
                }
                $last_date = date('Y-m-d',$timestamp);
                if($schedule->date_fom <= $last_date && $schedule->date_to >= $last_date){
                    $oDoctorScheduleDay = DoctorScheduleDay::where('is_active',1)->where('schedule_id',$schedule_id)->where('day_name',$day_name)->get();
            
                    if(isset($oDoctorScheduleDay)){
                        foreach($oDoctorScheduleDay as $days){
                           
                            $slot_array =  isset($days->slot_id)? explode(',', $days->slot_id):[];
                            $timeSlot = TimeSlot::whereIn('id',$slot_array)->where('slot','>=',$min_slot)->select('id','slot')->get();
                            foreach ($timeSlot as $key => $time ) {
                                $time['fee'] = $schedule->fee;
                                $time['discount_fee'] = $schedule->discount_fee;
                                $slots[$key] = $time;

                            }
                        }
                    }   
                }
            }
            
            if(count($slots)>0){
                foreach ($slots as $key => $slot) {
                    $oAppointment = Appointment::where('slot_id',$slot->id)->where('doctor_id',$oInput['doctor_id'])->where('appointment_date',$oInput['date'])->whereNotIn('status',Appointment::$CANCELSTATUS)->first();
                    if(!isset($oAppointment)){
                        $avalible_slots[] = $slot; 
                    }
                }
                
                $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"TimeSlots"]), $avalible_slots, false);
                
                $this->urlComponents(config("businesslogic")[20]['menu'][10], $oResponse, config("businesslogic")[20]['title']);
                
                return $oResponse;
            }else{
                $slots = [];
                $oResponse = responseBuilder()->success(__('There No Slots Available. Select Another Date'),$slots, false);
                return $oResponse;
            }
        }
        $slots = [];
        $oResponse = responseBuilder()->success(__('There No Slots Available. Select Another Date'),$slots, false);   
        return $oResponse; 
    }
    private function dateChecker($value, $oInput){
        $alreadyDate = null;
        if(strtotime($value->date_from) >= strtotime($oInput['date_from']) && strtotime($value->date_to) <= strtotime($oInput['date_to'])){
            $alreadyDate = $this->timeChecker($value, $oInput);
        }elseif((strtotime($value->date_from) < strtotime($oInput['date_from']) && strtotime($value->date_to) < strtotime($oInput['date_to'])) && (strtotime($value->date_to) > strtotime($oInput['date_from'])) ){
            $alreadyDate = $this->timeChecker($value, $oInput);
        }elseif((strtotime($value->date_from) > strtotime($oInput['date_from']) && strtotime($value->date_to) > strtotime($oInput['date_to'])) && (strtotime($value->date_from) < strtotime($oInput['date_to']))){
            $alreadyDate = $this->timeChecker($value, $oInput);
        }elseif(strtotime($value->date_from) <= strtotime($oInput['date_from']) && strtotime($value->date_to) >= strtotime($oInput['date_to'])){
            $alreadyDate = $this->timeChecker($value, $oInput);
        }

        return $alreadyDate;
    }
    private function timeChecker($value, $oInput){
        $alreadyTime = null;
        if(strtotime($value->time_from) >= strtotime($oInput['time_from']) && strtotime($value->time_to) <= strtotime($oInput['time_to'])){
            // dd('okay1');
            $alreadyTime = $value;
        }elseif((strtotime($value->time_from) < strtotime($oInput['time_from']) && strtotime($value->time_to) < strtotime($oInput['time_to'])) && (strtotime($value->time_to) > strtotime($oInput['time_from'])) ){
            // dd('okay2');
            $alreadyTime = $value;
        }elseif((strtotime($value->time_from) > strtotime($oInput['time_from']) && strtotime($value->time_to) > strtotime($oInput['time_to'])) && (strtotime($value->time_from) < strtotime($oInput['time_to']))){
            // dd('okay3');
            $alreadyTime = $value;
        }elseif(strtotime($value->time_from) <= strtotime($oInput['time_from']) && strtotime($value->time_to) >= strtotime($oInput['time_to'])){
            // dd('okay4');
            $alreadyTime = $value;  
        }
        return $alreadyTime;
    }
}
