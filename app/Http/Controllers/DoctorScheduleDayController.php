<?php

namespace App\Http\Controllers;

use App\DoctorSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\DoctorScheduleDay;
use App\TimeSlot;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DoctorScheduleDayController extends Controller
{
    use \App\Traits\WebServicesDoc;
    public function index(Request $request, $schedule_id)
    {
        $oInput = $request->all();
        
        if (!Gate::allows('schedule-slot-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorSchedule = DoctorSchedule::findOrFail($schedule_id);
        
        $oQb = $oDoctorSchedule->doctorScheduleDay()->with(['schedule','doctor']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"day_no",$oQb);
        $oQb = QB::whereLike($oInput,"day_name",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        
        $oSchedules = $oQb->get();

        foreach($oSchedules as $days){
            $slot_array =  explode(',', $days->slot_id);
            $timeSlot = TimeSlot::whereIn('id',$slot_array)->select('id','slot')->get();
            $days['slot_id'] = $timeSlot;
        }
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Schedule Day Slot"]), $oSchedules, false);
        $this->urlComponents(config("businesslogic")[21]['menu'][0], $oResponse, config("businesslogic")[21]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('schedule-slot-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'day_no'          => 'required|digits_between:0,6',
            'day_name'        => 'required|string|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'is_active'       => 'required|in:0,1',
            'slot_id'         => 'present|nullable',
            'doctor_id'       => 'required|exists:doctors,id',
            'schedule_id'     => 'required|exists:doctor_schedules,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $slot_array =  isset($oInput['slot_id'])?explode(',', $oInput['slot_id']):[];
        
        $aTimeSlot = TimeSlot::findOrFail($slot_array);

        $oDoctor = Doctor::findOrFail($oInput['doctor_id']);

        $oDoctorSchedule = DoctorSchedule::findOrFail($oInput['schedule_id']);

        $oDoctorScheduleDay = DoctorScheduleDay::create([
            'doctor_id'     => $oInput['doctor_id'],
            'schedule_id'   => $oInput['schedule_id'],
            'day_no'        => $oInput['day_no'],
            'day_name'      => $oInput['day_name'],
            'is_active'     => $oInput['is_active'],
            'slot_id'       => $oInput['slot_id'],
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorScheduleDay= DoctorScheduleDay::with(['schedule','doctor'])->findOrFail($oDoctorScheduleDay->id);
        
        $oDoctorScheduleDay['slot_id'] = $aTimeSlot;
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Schedule Slot"]), $oDoctorScheduleDay, false);
        
        $this->urlComponents(config("businesslogic")[21]['menu'][1], $oResponse, config("businesslogic")[21]['title']);
        
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
        $oSchedule = DoctorScheduleDay::with(['schedule','doctor'])->findOrFail($schedule_id);
        
        if (!Gate::allows('schedule-slot-show',$oSchedule))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        
        $slot_array =  explode(',', $oSchedule->slot_id);
        $aTimeSlot = TimeSlot::findOrFail($slot_array);
       
        $oSchedule['slot_id'] = $aTimeSlot;
        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Schedule Day"]), $oSchedule, false);
        
        $this->urlComponents(config("businesslogic")[21]['menu'][2], $oResponse, config("businesslogic")[21]['title']);
        
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
            'day_no'          => 'required|digits_between:0,6',
            'day_name'        => 'required|string|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'is_active'       => 'required|in:0,1',
            'slot_id'         => 'present|nullable',
            'doctor_id'       => 'required|exists:doctors,id',
            'schedule_id'     => 'required|exists:doctor_schedules,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $slot_array =  isset($oInput['slot_id'])?explode(',', $oInput['slot_id']):[];
        $aTimeSlot = TimeSlot::findOrFail($slot_array);

        $oDoctor = Doctor::findOrFail($oInput['doctor_id']);

        $oDoctorSchedule = DoctorSchedule::findOrFail($oInput['schedule_id']);

        $oDoctorScheduleDay = DoctorScheduleDay::findOrFail($id);
        
        if (!Gate::allows('schedule-slot-update',$oDoctorScheduleDay))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorScheduleDay = $oDoctorScheduleDay->update([
            'doctor_id'     => $oInput['doctor_id'],
            'schedule_id'   => $oInput['schedule_id'],
            'day_no'        => $oInput['day_no'],
            'day_name'      => $oInput['day_name'],
            'is_active'     => $oInput['is_active'],
            'slot_id'       => $oInput['slot_id'],
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorScheduleDay= DoctorScheduleDay::with(['schedule','doctor'])->findOrFail($id);
        
        $oDoctorScheduleDay['slot_id'] = $aTimeSlot;
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Schedule Slot"]), $oDoctorScheduleDay, false);
        
        $this->urlComponents(config("businesslogic")[21]['menu'][3], $oResponse, config("businesslogic")[21]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_schedule_day,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allDoctorScheduleDay = DoctorScheduleDay::findOrFail($aIds);
        
        foreach($allDoctorScheduleDay as $oRow)
            if (!Gate::allows('schedule-slot-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctorSchedule = DoctorScheduleDay::find($id);
                if($oDoctorSchedule){
                    $oDoctorSchedule->delete();
                }
            }
        }else{
            $oDoctorSchedule = DoctorScheduleDay::findOrFail($aIds);
            $oDoctorSchedule->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Schedule Slot"]));
        $this->urlComponents(config("businesslogic")[21]['menu'][4], $oResponse, config("businesslogic")[21]['title']);
        
        return $oResponse;
    }
}
