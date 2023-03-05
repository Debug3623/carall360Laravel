<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $guarded = ['id'];


    public function phone(){

    	return $this->belongsTo('App\User', 'user_id', 'id')->latest();
    }

	public function email(){

    	return $this->belongsTo('App\User', 'user_id', 'id')->latest();
    }


}
