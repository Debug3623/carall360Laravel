<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorScheduleDay extends Model
{
    protected $guarded = ['id'];
    protected $table = 'doctor_schedule_day';
    public function getDoctorIdAttribute($value){
    	return encrypt($value);
    }
    public function getPkAttribute(){
        return decrypt($this->doctor_id);
    }
    public function doctor(){
        return $this->hasOne('App\Doctor', 'id', 'pk');
    }
    public function schedule(){
        return $this->hasOne('App\DoctorSchedule', 'id', 'schedule_id');
    }
}
