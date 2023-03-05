<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Chat extends Model
{
    
    use SoftDeletes;
    protected $guarded = ['id'];
    public function doctorUser(){
    	return $this->hasOne('App\User', 'id', 'doctor_user_id')->select('id','name','is_active');
    }

    public function patientUser(){
    	return $this->hasOne('App\User', 'id', 'patient_user_id')->select('id','name','is_active','patient_id')->with('patient');
    }

    public function latestMessage()
    {
        return $this->hasOne('App\Message','chat_id','id')->select('id','chat_id','user_id', 'body', 'created_by', 'created_at','is_seen')->latest();
    }    

    
}
