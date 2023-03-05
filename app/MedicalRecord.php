<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    public function getUrlAttribute($value){
    	return encrypt($value);
    }

    public function getIdAttribute($value){
    	return encrypt($value);
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
}
