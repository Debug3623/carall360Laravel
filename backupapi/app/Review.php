<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = ['id'];
    
    public function doctorId(){
        return $this->hasOne('App\Doctor', 'pk', 'doctor_id')->withTrashed();
    }
    public function patientId(){
        return $this->hasOne('App\Patient', 'pk', 'patient_id')->withTrashed();
    }
    public function appointmentId(){
        return $this->hasOne('App\Appointment', 'id', 'appointment_id')->withTrashed();
    }
    public function responseBy(){
        return $this->belongsTo('App\User','response_by','id')->select('id','name','phone');
    }
}
