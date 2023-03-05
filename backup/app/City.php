<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = ['id'];
    
    public function countryCode(){
        return $this->belongsTo('App\Country','country_code','code')->select('name','code','phonecode');
    }
}
