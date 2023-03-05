<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrescriptionDiagnostic extends Model
{
    protected $guarded = ['id'];
    
    public function patientId(){
    	return $this->hasOne('App\Patient', 'pk', 'patient_id')->withTrashed();
    }
    public function doctorId(){
        return $this->hasOne('App\Doctor', 'pk', 'doctor_id')->withTrashed();
    }
    public function diagnosticId(){
        return $this->hasOne('App\Diagnostic', 'id', 'diagnostic_id')->select('id','name','preinstruction','deleted_at')->withTrashed();
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
}
