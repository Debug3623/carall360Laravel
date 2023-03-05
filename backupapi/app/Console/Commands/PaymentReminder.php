<?php

namespace App\Console\Commands;

use App\Appointment;
use App\Center;
use App\Doctor;
use App\Patient;
use App\TimeSlot;
use Carbon\Carbon;
use App\Jobs\SendEmail;
use App\Mail\GeneralAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class PaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PaymentReminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send sms or email before 2 hours appointment time limit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today      = Carbon::now()->toDateTimeString();
        $start_time = date("Y-m-d H:i:s", strtotime("$today + 3 hours"));
        $end_time   = date("Y-m-d H:i:s", strtotime("$today + 4 hours"));
        $oAppointments   = Appointment::whereBetween('payment_timelimit', [$start_time,$end_time])->where('status','pending')->where('paid_status','unpaid')->get();
        if(count($oAppointments)>0){
            foreach ($oAppointments as $appointment) {
                $patient  = Patient::where('id',$appointment->patient_id)->first();
                $doctor   = Doctor::where('id',$appointment->doctor_id)->first();
                $timeSlot = TimeSlot::where('id',$appointment->slot_id)->first();
                $date     = Carbon::parse($appointment->appointment_date);                                                // Appointment date
                $fdate    = $date->format('jS F Y');
                $time     = date('h:i A', strtotime($timeSlot->slot));
                
                $pt_date  = Carbon::parse($appointment->payment_timelimit);                                                // Appointment date
                $pt_fdate = $pt_date->format('jS F Y');
                $pt_time  = date('h:i A', strtotime($pt_date));
           
                $n        = '\n';
                $url    = config("app")['FRONTEND_URL'].'patient/payment/'.$appointment->id;
                $patientMessage = "Payment Reminder.".$n.$n."Dear+$patient->name,".$n.$n."Your outstanding amount of $appointment->appointment_fee Rs. against your appointment booked with $doctor->title. $doctor->full_name on Date: $fdate Time: $time".$n.$n."Kindly pay before: Date: $pt_fdate Time: $pt_time through $url".$n.$n."Please ignore, if already paid.";
                if (isset($patient->phone)) {
                    $iPhoneNumber = preg_replace("/[^0-9]/", "", $patient->phone);
                    $sMessage    = $patientMessage;
                    $iPhoneNumber = ltrim($iPhoneNumber, '0');
                    $sVendorEndpoint = 'https://www.support.hospitallcare.com/api/receive_sms_request';
                    $aPostData = array();
                    $aPostData['action']        = 'sendmessage';
                    $aPostData['username']      = 'Nestol';
                    $aPostData['password']      = '32JNoi90';
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
                    curl_close($oCurlHandle);
                }
                if (isset($patient->email)) {
                    $patientMessage = str_replace('\n', '<br>', $patientMessage);
                    $patientMessage = str_replace('+', ' ', $patientMessage);
                    dispatch(new SendEmail(Mail::to($patient->email)->send(new GeneralAlert( 'Payment Reminder', $patientMessage))));
                }
            }
        }
        if(count($oAppointments)>0){
            $this->info('SMS Sent successfully');
        }else{
            $this->info("there is no data to send sms");
        }
    }
}
