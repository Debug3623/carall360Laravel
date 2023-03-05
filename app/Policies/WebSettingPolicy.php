<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\WebSetting;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class WebSettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {        
        return isAllowed($user, 'web-setting-index');
    }

    public function show(User $user, WebSetting $WebSetting)
    {
        return isAllowed($user, 'web-setting-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'web-setting-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'web-setting-store');
    }

    public function update(User $user, WebSetting $WebSetting)
    {        
        if($user->id == $WebSetting->update_by)
            return true;
        
        return isAllowed($user, 'web-setting-update');   
    }

    public function destroy(User $user, WebSetting $WebSetting)
    {
        return isAllowed($user, 'web-setting-destroy');    
    }

    public function restore(User $user, WebSetting $WebSetting)
    {
        return isAllowed($user, 'web-setting-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'web-setting-deleted');
    }

    public function delete(User $user, WebSetting $WebSetting)
    {
        return isAllowed($user, 'web-setting-delete');
    }    
}
