<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PaymentSummary extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    public function getEvidenceUrlAttribute($value){
    	return encrypt($value);
    }
    public function doctorId(){
        return $this->hasOne('App\Doctor', 'pk', 'doctor_id')->withTrashed();
    }
    public function paidBy(){
        return $this->belongsTo('App\User','paid_by','id')->select('id','name','phone');
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
}
