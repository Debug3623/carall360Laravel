<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $guarded = ['id'];
    
    public function city(){
        return $this->hasMany('App\City','country_code','code');
    }
}
