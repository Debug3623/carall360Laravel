<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Center;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorCenterPolicy
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
        return isAllowed($user, 'doctor-center-index');
    }

    public function show(User $user, Center $Center)
    {
        return isAllowed($user, 'doctor-center-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'doctor-center-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'doctor-center-store');
    }

    public function update(User $user, Center $Center)
    {        
        if($user->id == $Center->update_by)
            return true;
        
        return isAllowed($user, 'doctor-center-update');   
    }

    public function destroy(User $user, Center $Center)
    {
        return isAllowed($user, 'doctor-center-destroy');    
    }

    public function restore(User $user, Center $Center)
    {
        return isAllowed($user, 'doctor-center-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-center-deleted');
    }

    public function delete(User $user, Center $Center)
    {
        return isAllowed($user, 'doctor-center-delete');
    }
    
}
