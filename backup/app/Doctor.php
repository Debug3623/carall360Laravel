<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Doctor extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'doctors';
    
    public function getIdAttribute($value){
    	return encrypt($value);
    }
    
    public function patients(){
        return $this->hasMany('App\Patient','doctor_id','pk')->withTrashed();
    }

    public function doctorAssistant(){

        return $this->hasMany('App\DoctorAssistant','doctor_id', 'pk');
    }

    public function assistant(){
        return $this->hasMany('App\DoctorAssistant','doctor_id', 'pk');
    }

    public function doctorAward(){
        return $this->hasMany('App\DoctorAward','doctor_id','pk');
    }
    public function doctorCertification(){
        return $this->hasMany('App\DoctorCertification','doctor_id','pk');
    }
    public function doctorExperience(){
        return $this->hasMany('App\DoctorExperience','doctor_id','pk');
    }
    public function doctorQualification(){
        return $this->hasMany('App\DoctorQualification','doctor_id','pk');
    }
    public function doctorTreatment(){
        return $this->hasMany('App\DoctorTreatment','doctor_id','pk');
    }
    public function doctorMedicine(){
        return $this->hasMany('App\DoctorMedicine','doctor_id','pk');
    }
    public function doctorCenter(){
        return $this->hasMany('App\Center','doctor_id','pk');
    }
    public function doctorWebSetting(){
        return $this->hasMany('App\WebSetting','doctor_id','pk');
    }
    public function doctorSchedule(){
        return $this->hasMany('App\DoctorSchedule','doctor_id','pk');
    }
    public function user(){
        return $this->hasOne('App\User', 'doctor_id', 'pk')->with('media');
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
    public function organization(){
        return $this->hasOne('App\Organization', 'id', 'organization_id')->with(['countryCode','cityId']);
    }
    
}
