<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class UserPolicy
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
        return isAllowed($user, 'user-index');
    }

    public function show(User $user, User $oUser)
    {
        return isAllowed($user, 'user-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'user-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'user-store');
    }

    public function update(User $user, User $oUser)
    {        
        if($user->id == $oUser->update_by)
            return true;
        
        return isAllowed($user, 'user-update');   
    }

    public function destroy(User $user, User $oUser)
    {
        return isAllowed($user, 'user-destroy');    
    }

    public function restore(User $user, User $oUser)
    {
        return isAllowed($user, 'user-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'user-deleted');
    }

    public function delete(User $user, User $oUser)
    {
        return isAllowed($user, 'user-delete');
    }

    public function createUserRole(User $user)
    {
        return isAllowed($user, 'user-assign-role');
    }
    
}
