<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class MenuPolicy
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
        return isAllowed($user, 'menu-index');
    }

    public function show(User $user, Permission $Permission)
    {
        return isAllowed($user, 'menu-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'menu-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'menu-store');
    }

    public function update(User $user, Permission $Permission)
    {        
        if($user->id == $Permission->update_by)
            return true;
        
        return isAllowed($user, 'menu-update');   
    }

    public function destroy(User $user, Permission $Permission)
    {
        return isAllowed($user, 'menu-destroy');    
    }

    public function restore(User $user, Permission $Permission)
    {
        return isAllowed($user, 'menu-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'menu-deleted');
    }

    public function delete(User $user, Permission $Permission)
    {
        return isAllowed($user, 'menu-delete');
    }
    
    
    public function parentPermission(User $user)
    {
        return isAllowed($user, 'menu-permission');
    }
}
