<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Doctor;
class Patient extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'height' => 'float',
        'weight' => 'float',
    ];

    public function getIdAttribute($value){
    	return encrypt($value);
    }

    
    public function user(){
    	return $this->hasOne('App\User', 'patient_id', 'pk');
    }
    public function doctor(){
        return $this->hasOne('App\Doctor', 'pk', 'doctor_id')->withTrashed();
    }

    

    public function countryCode(){
        return $this->belongsTo('App\Country','country_code','code')->select('name','code','phonecode');
    }
    public function cityId(){
        return $this->belongsTo('App\City','city_id','id')->select('id','name','country_code');
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
        return $this->hasOne('App\Organization', 'id', 'organization_id');
    }
}
