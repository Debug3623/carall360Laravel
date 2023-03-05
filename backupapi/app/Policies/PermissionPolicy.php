<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class PermissionPolicy
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
        return isAllowed($user, 'permission-index');
    }

    public function show(User $user, Permission $Permission)
    {
        return isAllowed($user, 'permission-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'permission-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'permission-store');
    }

    public function update(User $user, Permission $Permission)
    {        
        if($user->id == $Permission->update_by)
            return true;
        
        return isAllowed($user, 'permission-update');   
    }

    public function destroy(User $user, Permission $Permission)
    {
        return isAllowed($user, 'permission-destroy');    
    }

    public function restore(User $user, Permission $Permission)
    {
        return isAllowed($user, 'permission-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'permission-deleted');
    }

    public function delete(User $user, Permission $Permission)
    {
        return isAllowed($user, 'permission-delete');
    }
    
    
    public function parentPermission(User $user)
    {
        return isAllowed($user, 'parent-permission');
    }
}
