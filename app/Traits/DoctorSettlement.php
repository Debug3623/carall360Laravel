<?php

namespace App\Traits;

use App\Appointment;
use App\Doctor;
use App\DoctorReceipt;
use App\Partnership;
use App\PaymentSummary;
use App\PlanCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceAlert;
use App\Jobs\SendEmail;

trait DoctorSettlement {

    public function doctorPaymentSettlement($durastion){
        
        if($durastion == "month"){
            $start_date = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $end_date   = Carbon::now()->endOfMonth()->subMonth()->toDateString();
        }elseif($durastion == "biweek"){
            $end_date   = Carbon::now()->toDateString();
            $start_date = date("Y-m-d", strtotime("$end_date - 16 days"));
        }else{
            $start_date = Carbon::now()->startOfWeek()->subWeek()->toDateString();
            $end_date   = Carbon::now()->endOfWeek()->subWeek()->toDateString();
        }

        $partnerships = Partnership::where('doctor_settlement_frequency',$durastion)->where('is_active',1)->latest()->get()->unique('doctor_id');
        
        foreach ($partnerships as $partnership) {
            $this->doctorPaymentSettlementCalculation($partnership, $start_date, $end_date);
        }
        return $partnerships;
    }
    public function patnerShipExpirReminder($start_date){

        $partnerships = Partnership::where('is_active',1)->whereDate('date_to',$start_date)->get();
        
        foreach ($partnerships as $partnership) {
            
            $fdate         = $partnership->date_to;
            $doctor        = Doctor::where('id',$partnership->doctor_id)->first();
            $doctorMessage = "Dear $doctor->title. $doctor->full_name, your partnership of plan $partnership->doctor_settlement_frequency is going to be expire on $fdate please renew this. For assistance, please contact: 0322-2555601; 0322-2555363";
            if (isset($doctor->phone)) {
                smsGateway($doctor->phone, $doctorMessage);
            }
            if (isset($doctor->email)) {
                $emailTitle = "Membership Reminder";
                emailGateway($doctor->email, $doctorMessage, $emailTitle);
            }
        }
        return $partnerships;
    }
    public function patnerShipExpired($start_date){
        
        $partnerships = Partnership::where('is_active',1)->where('date_to','<',$start_date)->get();
        
        foreach ($partnerships as $partnership) {
            
            $this->doctorPaymentSettlementCalculation($partnership);
            
            $fdate  = $partnership->date_to;
            $doctor = Doctor::where('id',$partnership->doctor_id)->first();
            $doctorMessage = "Dear $doctor->title. $doctor->full_name, your membership with Clinicall is expired on $fdate. Please renew your membership. For assistance, please contact: 0322-2555601; 0322-2555363";
            if (isset($doctor->phone)) {
                smsGateway($doctor->phone, $doctorMessage);
            }
            if (isset($doctor->email)) {
                $emailTitle = "Membership Expired";
                emailGateway($doctor->email, $doctorMessage, $emailTitle);
            }
            $partnership->update(['is_active' => 0]);
        }
        return $partnerships;
    }
    private function doctorPaymentSettlementCalculation($partnership, $start_date = null, $end_date = null){

            $plan = PlanCategory::where('id',$partnership->plan_id)->first();
            $oQB = Appointment::where('doctor_id',$partnership->doctor_id);
            if( isset($start_date) && isset($end_date) ){
                $oQB = $oQB->whereBetween('appointment_date',[$start_date,$end_date]);
            }
            $oAppointments = $oQB->where('paid_status','paid')->where('appointment_fee',"!=","0")->whereIn('status',['done','ongoing','follow_up','reschedual'])->where('is_settled',0)->get();
            
            if(count($oAppointments) == 0){
                return $partnership;
            }
        
            // $oAppointments = Appointment::whereBetween('appointment_date',[$start_date,$end_date])->where('paid_status','paid')->whereIn('status',['done','ongoing','follow_up','reschedual'])->where('is_settled',0)->get();
           
            $total_commission = 0;
            $actual_amount      = collect($oAppointments)->sum('appointment_fee');
            $total_appointments = count($oAppointments);
            
            if($plan->fix_percentage_fee == 'P'){
                $total_commission = ($plan->fee/100)*$actual_amount;
            }else{
                $total_commission = $plan->fee*$total_appointments;
            }

            $system_amount = $actual_amount - $total_commission;
            
            $perviousPaymentSummary = PaymentSummary::where('doctor_id',$partnership->doctor_id)->where('paid','in_progress')->latest()->first();
            
            $payment_summary = PaymentSummary::create([
                'system_amount'   => $system_amount,
                'doctor_id'       => $partnership->doctor_id,
                'actual_amount'   => $actual_amount,
                'total_commission'=> $total_commission,
                'outstanding'     => isset($perviousPaymentSummary)?$perviousPaymentSummary->system_amount:0,
                'created_at'      => Carbon::now()->toDateTimeString(),
            ]);

            foreach ($oAppointments as $oAppointment) {
                
                if($plan->fix_percentage_fee == 'P'){
                    $service_charges = ($plan->fee/100)*$oAppointment->appointment_fee;
                }else{
                    $service_charges = $plan->fee;
                }

                $doctor_receipt = DoctorReceipt::create([
                    'doctor_id'      => $oAppointment->doctor_id,
                    'appointment_id' => $oAppointment->id,
                    'amount'         => $oAppointment->appointment_fee,
                    'summary_id'     => $payment_summary->id,
                    'service_charges'=> $service_charges,
                ]);

                $oAppointment->update(['is_settled' => 1]);
 
            }
            // if(count($oAppointments)>0){
                $this->doctorPaymentSettlementEmail($partnership, $payment_summary);
            // }
        return $partnership;
    }
    private function doctorPaymentSettlementEmail($partnership, $payment_summary)
    {
        $doctor        =   Doctor::Where('id',$partnership->doctor_id)->first();
        $doctorEmail   =   $doctor->email;
        $doctorName    =   isset($doctor)? $doctor->title." ". $doctor->full_name :'';

        $data['doctor_name']     = $doctorName;
        $data['total']           = $payment_summary->system_amount;
        $data['actual_amount']   = $payment_summary->actual_amount;
        $data['net_amount']      = $payment_summary->system_amount;
        $data['service_charges'] = $payment_summary->total_commission;
        $data['date']            = $payment_summary->created_at->format('jS F Y');

        $emailTitle  = "Invoice Generated for $doctorName";
        $email       = "shafqat.ali@hospitall.tech";
        
        dispatch(new SendEmail(Mail::to($email)->send(new InvoiceAlert($emailTitle, $data))));

        $doctorEmailTitle  = "$doctorName, Your Invoice Generated";
        
        dispatch(new SendEmail(Mail::to($doctorEmail)->send(new InvoiceAlert($doctorEmailTitle, $data))));

        return true;

    }
}