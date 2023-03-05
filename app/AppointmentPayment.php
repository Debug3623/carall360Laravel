<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppointmentPayment extends Model
{
    protected $guarded = ['id'];
    public function getEvidenceUrlAttribute($value){
    	return encrypt($value);
    }
    public function appointmentId(){
        return $this->hasOne('App\Appointment', 'id', 'appointment_id')->withTrashed();
    }
    public function bankId(){
        return $this->hasOne('App\BankAccount', 'id', 'bank_id')->withTrashed();
    }
    public function verifiedBy(){
        return $this->belongsTo('App\User','verified_by','id')->select('id','name','phone');
    }
}
