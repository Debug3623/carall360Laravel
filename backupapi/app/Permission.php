<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Permission extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function roles(){
        return $this->belongsToMany('App\Role', 'role_user', 'role_id', 'role_id');
    }
    public function organizations(){
        return $this->belongsToMany('App\Organization');
    }
    public function childPermission(){
        return $this->hasMany('App\Permission', 'parent_id')->select('id','title','alies','menu_order','selected_menu','is_menu','permission_code','description','url','icon','css_class','div_id','type','is_active','parent_id');
    }
    public function childMenu(){
        return $this->hasMany('App\Permission', 'parent_id')->where('is_active', 1)->where('is_menu', 1)->select('id','title','alies','menu_order','selected_menu','is_menu','permission_code','description','url','icon','css_class','div_id','type','is_active','parent_id');
    }
    public function parentPermission(){
        return $this->belongsTo('App\Permission','parent_id','id')->select('id','title','alies','menu_order','selected_menu','is_menu','permission_code','description','url','icon','css_class','div_id','type','is_active','parent_id');
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
}
