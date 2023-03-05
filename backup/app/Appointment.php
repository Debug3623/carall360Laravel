<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Appointment extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    public static $CANCELSTATUS = [
        'cancel_by_doctor'  => 'cancel_by_doctor',
        'cancel_by_patient' => 'cancel_by_patient',
        'no_show'           => 'no_show',
        'auto_cancel'       => 'auto_cancel',
        'refund'            => 'refund',
        'cancel_by_adminStaff'=>'cancel_by_adminStaff',
    ];
    public static $UPDATEDEDSTATUS = [
        'cancel_by_doctor'  => 'cancel_by_doctor',
        'cancel_by_patient' => 'cancel_by_patient',
        'no_show'           => 'no_show',
        'auto_cancel'       => 'auto_cancel',
        'cancel_by_adminStaff'=>'cancel_by_adminStaff',
        'pending'           =>'pending',
        'awaiting_confirmation'=>'awaiting_confirmation',
    ];
    public static $APPROVEDSTATUS = [
        'approved'  => 'approved',
        'done'      => 'done',
        'ongoing'   => 'ongoing',
        'follow_up' => 'follow_up',
        'reschedule'=> 'reschedule',
    ];
    public static $ONGOINGSTATUS = [
        'approved'  => 'approved',
        'ongoing'   => 'ongoing',
        'follow_up' => 'follow_up',
        'reschedule'=> 'reschedule',
    ];
    public static $NEGPENDING = [
        'no_show'       => 'no_show',
        'done'          => 'done',
        'follow_up'     => 'follow_up',
        'refund'        => 'refund',
        'awaiting_confirmation' =>'awaiting_confirmation',
        'auto_cancel'   => 'auto_cancel',
        'approved'      => 'approved',
    ];
    public static $NEGAPPROVED = [
        'auto_cancel'   => 'auto_cancel',
        // 'follow_up'     => 'follow_up',
        'pending'       => 'pending',
    ];
    public static $NEGCANCELBYDOCTOR = [
        'no_show'               => 'no_show',
        'done'                  => 'done',
        'follow_up'             => 'follow_up',
        'approved'              => 'approved',
        'pending'               => 'pending',
        'cancel_by_patient'     => 'cancel_by_patient',
        'cancel_by_adminStaff'  => 'cancel_by_adminStaff',
        'auto_cancel'           => 'auto_cancel',
    ];

    public static $NEGCANCELBYPATIENT = [
        'no_show'               => 'no_show',
        'done'                  => 'done',
        'follow_up'             => 'follow_up',
        'approved'              => 'approved',
        'pending'               => 'pending',
        'cancel_by_doctor'      => 'cancel_by_doctor',
        'cancel_by_adminStaff'  => 'cancel_by_adminStaff',
        'auto_cancel'           => 'auto_cancel',
    ];
    public static $NEGCANCELBYADMINSTAFF = [
        'no_show'               => 'no_show',
        'done'                  => 'done',
        'follow_up'             => 'follow_up',
        'approved'              => 'approved',
        'pending'               => 'pending',
        'cancel_by_patient'     => 'cancel_by_patient',
        'cancel_by_doctor'      => 'cancel_by_doctor',
        'auto_cancel'           => 'auto_cancel',
    ];
    public static $NEGNOSHOW = [
        'done'                  => 'done',
        'follow_up'             => 'follow_up',
        'approved'              => 'approved',
        'pending'               => 'pending',
        'cancel_by_patient'     => 'cancel_by_patient',
        'cancel_by_doctor'      => 'cancel_by_doctor',
        'cancel_by_adminStaff'  => 'cancel_by_adminStaff',
        'auto_cancel'           => 'auto_cancel',
    ];
    public static $NEGDONE = [
        'approved'              => 'approved',
        'pending'               => 'pending',
        'cancel_by_patient'     => 'cancel_by_patient',
        'cancel_by_doctor'      => 'cancel_by_doctor',
        'cancel_by_adminStaff'  => 'cancel_by_adminStaff',
        'no_show'               => 'no_show',
        'reschedule'            => 'reschedule',
        'auto_cancel'           => 'auto_cancel',
        'refund'                => 'refund',
    ];
    public static $NEGFOLLOWUP = [
        'approved'              => 'approved',
        'pending'               => 'pending',
        'reschedule'            => 'reschedule',
        'auto_cancel'           => 'auto_cancel',
        'refund'                => 'refund',
    ];
    public static $NEGAUTOCANCEL = [
        'approved'              => 'approved',
        'pending'               => 'pending',
        'cancel_by_patient'     => 'cancel_by_patient',
        'cancel_by_doctor'      => 'cancel_by_doctor',
        'cancel_by_adminStaff'  => 'cancel_by_adminStaff',
        'no_show'               => 'no_show',
        'reschedule'            => 'reschedule',
        'refund'                => 'refund',
        'follow_up'             => 'follow_up',
        'done'                  => 'done',
    ];
    public static $NEGAWAITINGCONFIRMATION = [
        'auto_cancel'           => 'auto_cancel',
        'cancel_by_patient'     => 'cancel_by_patient',
        'cancel_by_doctor'      => 'cancel_by_doctor',
        'cancel_by_adminStaff'  => 'cancel_by_adminStaff',
        'no_show'               => 'no_show',
        'reschedule'            => 'reschedule',
        'refund'                => 'refund',
        'done'                  => 'done',
    ];
    public static $NEGRESCHEDULE = [
        'approved'              => 'approved',
        'pending'               => 'pending',
        'auto_cancel'           => 'auto_cancel',
        'cancel_by_patient'     => 'cancel_by_patient',
        'cancel_by_doctor'      => 'cancel_by_doctor',
        'cancel_by_adminStaff'  => 'cancel_by_adminStaff',
        'no_show'               => 'no_show',
        'awaiting_confirmation' =>'awaiting_confirmation',
        'refund'                => 'refund',
        'done'                  => 'done',
    ];
    public static $NEGREFUND = [
        'approved'              => 'approved',
        'pending'               => 'pending',
        'auto_cancel'           => 'auto_cancel',
        'cancel_by_patient'     => 'cancel_by_patient',
        'cancel_by_doctor'      => 'cancel_by_doctor',
        'cancel_by_adminStaff'  => 'cancel_by_adminStaff',
        'no_show'               => 'no_show',
        'awaiting_confirmation' =>'awaiting_confirmation',
        'reschedule'            => 'reschedule',
        'done'                  => 'done',
    ];
    public function patient(){
    	return $this->hasOne('App\Patient', 'pk', 'patient_id')->withTrashed();
    }
    public function doctor(){
        return $this->hasOne('App\Doctor', 'doctor_id', 'pk')->withTrashed();
    }
    public function createdBy(){
        return $this->belongsTo('App\User','created_by','id')->select('id','name','phone');
    }
    public function updatedBy(){
        return $this->belongsTo('App\User','updated_by','id')->select('id','name','phone');
    }
    public function deletedBy(){
        return $this->belongsTo('App\User','deleted_by','id')->select('id','name','phone');
    }
    public function restoredBy(){
        return $this->belongsTo('App\User','restored_by','id')->select('id','name','phone');
    }
    public function doctorId(){
        return $this->hasOne('App\Doctor', 'pk', 'doctor_id')->withTrashed();
    }
    public function patientId(){
        return $this->hasOne('App\Patient', 'pk', 'patient_id')->withTrashed();
    }
    public function treatmentId(){
        return $this->hasOne('App\DoctorTreatment', 'id', 'treatment_id')->select('id','treatment_name','deleted_at')->withTrashed();
    }
    public function centerId(){
        return $this->hasOne('App\Center', 'id', 'center_id')->select('id','name','lat','lng','address','deleted_at')->withTrashed();
    }
    public function slotId(){
        return $this->hasOne('App\TimeSlot', 'id', 'slot_id')->select('id','slot');
    }
    public function MedicalRecord(){
        return $this->hasMany('App\MedicalRecord','appointment_id','id')->withTrashed();
    }
    public function Reviews(){
        return $this->hasMany('App\Review','appointment_id','id');
    }
    public function AppointmentPayment(){
        return $this->hasMany('App\AppointmentPayment','appointment_id','id')->where('status','processed')->with(['bankId','verifiedBy']);
    }
    public function refund(){
        return $this->hasOne('App\PaymentRefund','appointment_id','id')->withTrashed();
    }
}
