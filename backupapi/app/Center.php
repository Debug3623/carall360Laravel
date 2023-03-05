<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Center extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function createdBy(){
        return $this->belongsTo('App\User','created_by','id')->select('id','name','phone');
    }
    public function updatedBy(){
        return $this->belongsTo('App\User','updated_by','id')->select('id','name','phone');
    }
    public function deletedBy(){
        return $this->belongsTo('App\User','deleted_by','id')->select('id','name','phone');
    }
    public function doctor(){
        return $this->hasOne('App\Doctor', 'id', 'doctor_id')->withTrashed();
    }
    public function countryCode(){
        return $this->belongsTo('App\Country','country_code','code')->select('name','code','phonecode');
    }
    public function cityId(){
        return $this->belongsTo('App\City','city_id','id')->select('id','name','country_code');
    }
}
