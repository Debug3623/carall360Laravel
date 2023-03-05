<?php

namespace App\Traits;

use App\Appointment;
use App\Center;
use App\Doctor;
use App\Patient;
use App\TimeSlot;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralAlert;

trait AppointmentReminderTrait {

    public function AppointmentReminder($start_time,$end_time){
        
        $oAppointments   = Appointment::whereBetween('appointment_date', [$start_time,$end_time])->whereIn('status',Appointment::$ONGOINGSTATUS)->get();
        
        if(count($oAppointments)>0){
            foreach ($oAppointments as $appointment) {
                $patient     = Patient::where('id',$appointment->patient_id)->first();
                $doctor      = Doctor::where('id',$appointment->doctor_id)->first();
                $timeSlot     = TimeSlot::where('id',$appointment->slot_id)->first();
                $date         = Carbon::parse($appointment->appointment_date);                                                // Appointment date
                $fdate        = $date->format('jS F Y');
                $time         = date('h:i A', strtotime($timeSlot->slot));
                $n            = '\n';
                if($appointment->appointment_type == 'online'){
                    $password =   $appointment->tele_password;
                    $doctorBaseUrl=  $doctor->url;
                    $patientUrl   = config("app")['FRONTEND_URL'].'online_consultancy/'.$doctorBaseUrl.'/'.$appointment->tele_url;
                    $url          = config("app")['FRONTEND_URL'].'online_consultancy/'.$appointment->tele_url;
                    $patientMessage = "Appointment Reminder.".$n.$n."Your appointment has been booked with $doctor->title. $doctor->full_name.".$n.$n."Date: $fdate".$n."Time: $time".$n."Consultation Link: $patientUrl".$n.$n."For Queries:0322-2555601, 0322-2555363";
                    $doctorMessage  = "Appointment Reminder.".$n.$n."Your appointment has been booked with $patient->name.".$n.$n."Date: $fdate".$n."Time: $time".$n."Consultation Link: $url";
                }
                if($appointment->appointment_type == 'physical'){
                    $center         =   Center::where('id', $appointment->center_id)->first();    
                    $at             =   $center->name;
                    $location       =   $center->address;
                    $map            =   'http://maps.google.com/?q='.$center->lat.','.$center->lng;
                    
                    $patientMessage = "Appointment Reminder.".$n.$n."Your appointment has been booked with $doctor->title. $doctor->full_name at $at.".$n.$n."Date: $fdate".$n."Time: $time".$n."Location: $location".$n."Direction: $map".$n.$n."For Queries: 0322-2555601, 0322-2555363";
                    $doctorMessage  = "Appointment Reminder.".$n.$n."Your appointment has been booked with $patient->name at $at.".$n.$n."Date: $fdate".$n."Time: $time";
                }
                if (isset($patient->phone)) {
                    smsGateway($patient->phone, $patientMessage);
                }
                if (isset($doctor->phone)) {
                    smsGateway($doctor->phone, $doctorMessage);
                }
                if (isset($doctor->email)) {
                    $doctorMessage = str_replace('\n', '<br>', $doctorMessage);
                    $doctorMessage = str_replace('+', ' ', $doctorMessage);
                    $emailTitle = "Appointment Reminder";
                    emailGateway($doctor->email, $doctorMessage, $emailTitle);
                }
                if (isset($patient->email)) {
                    $patientMessage = str_replace('\n', '<br>', $patientMessage);
                    $patientMessage = str_replace('+', ' ', $patientMessage);
                    $emailTitle = "Appointment Reminder";
                    emailGateway($patient->email, $doctorMessage, $emailTitle);
                }
            }
        }

        return $oAppointments;
    }
    public function autoCancelAppointmentStatus($start_time,$end_time){
        
        $unpaidAppointments   = Appointment::whereBetween('payment_timelimit', [$start_time,$end_time])->where('status','pending')->where('paid_status','unpaid')->get();
        
        if(count($unpaidAppointments)>0){
            foreach ($unpaidAppointments as $appointment) {
                
                $patient     = Patient::where('id',$appointment->patient_id)->first();
                $doctor      = Doctor::where('id',$appointment->doctor_id)->first();
                $timeSlot    = TimeSlot::where('id',$appointment->slot_id)->first();
                $date        = Carbon::parse($appointment->appointment_date);                                                // Appointment date
                $fdate       = $date->format('jS F Y');
                $time         = date('h:i A', strtotime($timeSlot->slot));
                $n            = '\n';
                if($appointment->appointment_type == 'physical'){
                    $center         =   Center::where('id', $appointment->center_id)->first();    
                    $at             =   $center->name;                 
                    $patientMessage = "Appointment Cancelled.".$n.$n."Your appointment with $doctor->title. $doctor->full_name at $at on $fdate at $time has been cancelled due to non-payment.".$n.$n."For Queries: 0322-2555601, 0322-2555363";
                }else{
                    $patientMessage = "Appointment Cancelled.".$n.$n."Your appointment with $doctor->title. $doctor->full_name on $fdate at $time has been cancelled due to non-payment.".$n.$n."For Queries: 0322-2555601, 0322-2555363";
                }
                if (isset($patient->phone)) {
                    smsGateway($patient->phone, $patientMessage);
                }
                if (isset($patient->email)) {
                    $patientMessage = str_replace('\n', '<br>', $patientMessage);
                    $patientMessage = str_replace('+', ' ', $patientMessage);
                    $emailTitle = "Appointment Cancelled";
                    emailGateway($patient->email, $patientMessage, $emailTitle);
                }
                $appointment->update([
                    'status' => 'auto_cancel',
                    'is_again' => 1,
                ]);
            }
        }
        return $unpaidAppointments;
    }
    public function noShowAppointmentStatus($start_time,$end_time){

        $noShowAppointments = Appointment::where('status','approved')->where('paid_status','paid')->get();
        if(count($noShowAppointments)>0){
            foreach ($noShowAppointments as $appointment) {
                $timeSlot    = TimeSlot::where('id',$appointment->slot_id)->first();
                $appointment_time = Carbon::createFromTimestamp(strtotime($appointment->appointment_date . $timeSlot->slot))->toDateTimeString();
                if((strtotime($start_time) <= strtotime($appointment_time)) && (strtotime($end_time) >= strtotime($appointment_time))){
                    
                    $patient     = Patient::where('id',$appointment->patient_id)->first();
                    $doctor      = Doctor::where('id',$appointment->doctor_id)->first();
                    $date        = Carbon::parse($appointment->appointment_date);                                                // Appointment date
                    $fdate       = $date->format('jS F Y');
                    $time         = date('h:i A', strtotime($timeSlot->slot));
                    $n            = '\n';
                    $patientMessage = "Due to unavailability, your appointment has been cancelled with:".$n.$n."Doctor: $doctor->title. $doctor->full_name; ".$n."Date: $fdate ".$n."Time: $time ".$n.$n."For assistance, please contact: 0322-2555601; 0322-2555363";
                    if (isset($patient->phone)) {
                        smsGateway($patient->phone, $patientMessage);
                    }
                    if (isset($patient->email)) {
                        $patientMessage = str_replace('\n', '<br>', $patientMessage);
                        $patientMessage = str_replace('+', ' ', $patientMessage);
                        $emailTitle = "No Show Appointment";
                        emailGateway($patient->email, $patientMessage, $emailTitle);
                    }
                    $appointment->update(['status' => 'no_show']);
                }
            }
        }
        return $noShowAppointments;
    }
}