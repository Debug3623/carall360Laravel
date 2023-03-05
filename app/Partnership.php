<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partnership extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    
    public function doctorId(){
        return $this->hasOne('App\Doctor', 'pk', 'doctor_id')->with(['organization'])->withTrashed();
    }
    public function planId(){
        return $this->hasOne('App\PlanCategory', 'id', 'plan_id')->withTrashed();
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
