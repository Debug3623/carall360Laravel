<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentRefund extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function appointment()
    {
       return $this->belongsTo('App\Appointment','appointment_id','id')->withTrashed();
    }
}
