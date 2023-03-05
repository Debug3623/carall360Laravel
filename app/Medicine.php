<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Medicine extends Model
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
}
