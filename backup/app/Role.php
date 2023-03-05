<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Role extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function permissions(){
        return $this->belongsToMany('App\Permission')->where('is_active', 1)->with('childPermission');
    }
    public function menu(){
        return $this->belongsToMany('App\Permission')->where('is_active', 1)->orderBy('menu_order',"ASC")->where('is_menu', 1)->whereNull('parent_id')->with('childMenu');
    }
    public function users(){
        return $this->belongsToMany('App\User', 'role_user', 'role_id', 'user_id');
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

    public function permissions_active(){
        return $this->belongsToMany('App\Permission', 'permission_role', 'role_id', 'permission_id')->where('is_active', 1);
    }
}
