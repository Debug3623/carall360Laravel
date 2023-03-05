<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Doctor;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorPolicy
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
        return isAllowed($user, 'doctor-index');
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function show(User $user, Doctor $Doctor)
    {
        return isAllowed($user, 'doctor-show');
    }
    public function DoctorProfileView(User $user, Doctor $Doctor)
    {
        return isAllowed($user, 'doctor-profile-view');
    }
    public function DoctorProfileUpdate(User $user, Doctor $Doctor)
    {
        
        
        if($user->id == $Doctor->update_by)
            return true;
        
        return isAllowed($user, 'doctor-profile-update');
    }
    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return isAllowed($user, 'doctor-create');
    }
    public function store(User $user)
    {
        return isAllowed($user, 'doctor-store');
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function update(User $user, Doctor $Doctor)
    {
        
        
        if($user->id == $Doctor->update_by)
            return true;

        return isAllowed($user, 'doctor-update');
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function destroy(User $user, Doctor $Doctor)
    {
        return isAllowed($user, 'doctor-destroy');
    }

    /**
     * Determine whether the user can restore the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function restore(User $user, Doctor $Doctor)
    {
        return isAllowed($user, 'doctor-restore');
    }


    /**
     * Determine whether the user can restore the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-deleted');
    }


    /**
     * TODO: Delete Restore functionality to be decided against each controller CRUD
     * 
     * Determine whether the user can permanently delete the role.
     * 
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function delete(User $user, Doctor $Doctor)
    {
        return isAllowed($user, 'doctor-delete');
    }
    
}
