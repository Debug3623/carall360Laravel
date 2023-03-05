<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Organization extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    
    public function countryCode(){
        return $this->belongsTo('App\Country','country_code','code')->select('name','code','phonecode');
    }
    
    public function cityId(){
        return $this->belongsTo('App\City','city_id','id')->select('id','name','country_code');
    }

    public function createdBy(){
        return $this->belongsTo('App\User','created_by','id')->select('id','name','phone');
    }

    public function restoredBy(){
        return $this->belongsTo('App\User','restored_by','id')->select('id','name','phone');
    }
    
    public function updatedBy(){
        return $this->belongsTo('App\User','updated_by','id')->select('id','name','phone');
    }

    public function deletedBy(){
        return $this->belongsTo('App\User','deleted_by','id')->select('id','name','phone');
    }
    public function permissions(){
        return $this->belongsToMany('App\Permission')->with('childPermission');
    }
}
