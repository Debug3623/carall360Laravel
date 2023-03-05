<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WebSetting extends Model
{
    use SoftDeletes;
    public static $SOCIALLINK = [
        'Facebook' => 'fab fa-facebook-f',
        'Instagram'=> 'fab fa-instagram',
        'Linkedin' => 'fab fa-linkedin-in',
        'Twitter' => 'fab fa-twitter',
        'Youtube' => 'fab fa-youtube',
    ];
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
        return $this->hasOne('App\Doctor', 'id', 'doctor_id');
    }
}
