<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\DoctorQualification;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorQualificationPolicy
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
        return isAllowed($user, 'doctor-qualification-index');
    }

    public function show(User $user, DoctorQualification $DoctorQualification)
    {
        return isAllowed($user, 'doctor-qualification-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'doctor-qualification-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'doctor-qualification-store');
    }

    public function update(User $user, DoctorQualification $DoctorQualification)
    {        
        if($user->id == $DoctorQualification->update_by)
            return true;
        
        return isAllowed($user, 'doctor-qualification-update');   
    }

    public function destroy(User $user, DoctorQualification $DoctorQualification)
    {
        return isAllowed($user, 'doctor-qualification-destroy');    
    }

    public function restore(User $user, DoctorQualification $DoctorQualification)
    {
        return isAllowed($user, 'doctor-qualification-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-qualification-deleted');
    }

    public function delete(User $user, DoctorQualification $DoctorQualification)
    {
        return isAllowed($user, 'doctor-qualification-delete');
    }
    
}
