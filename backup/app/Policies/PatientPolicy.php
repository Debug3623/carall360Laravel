<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Patient;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class PatientPolicy
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
        return isAllowed($user, 'patient-index');
    }

    public function show(User $user, Patient $Patient)
    {
        return isAllowed($user, 'patient-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'patient-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'patient-store');
    }

    public function update(User $user, Patient $Patient)
    {        
        if($user->id == $Patient->update_by)
            return true;
        
        return isAllowed($user, 'patient-update');   
    }

    public function destroy(User $user, Patient $Patient)
    {
        return isAllowed($user, 'patient-destroy');    
    }

    public function restore(User $user, Patient $Patient)
    {
        return isAllowed($user, 'patient-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'patient-deleted');
    }

    public function delete(User $user, Patient $Patient)
    {
        return isAllowed($user, 'patient-delete');
    }   
    public function PatientProfileView(User $user, Patient $Patient)
    {
        return isAllowed($user, 'patient-profile-view');
    }
    public function PatientProfileUpdate(User $user, Patient $Patient)
    {    
        if($user->id == $Patient->update_by)
            return true;
        
        return isAllowed($user, 'patient-profile-update');
    } 
}
