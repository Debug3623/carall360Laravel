<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Prescription extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    
    public function getUrlAttribute($value){
    	return encrypt($value);
    }
    public function patientId(){
    	return $this->hasOne('App\Patient', 'pk', 'patient_id')->withTrashed();
    }
    public function doctorId(){
        return $this->hasOne('App\Doctor', 'pk', 'doctor_id')->withTrashed();
    }
    public function medicineId(){
        return $this->hasOne('App\DoctorMedicine', 'id', 'medicine_id')->select('id','medicine_name','type','deleted_at')->withTrashed();
    }
    public function appointmentId(){
        return $this->hasOne('App\Appointment', 'id', 'appointment_id')->withTrashed();
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
}
