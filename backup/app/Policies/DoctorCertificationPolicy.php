<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\DoctorCertification;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorCertificationPolicy
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
        return isAllowed($user, 'doctor-certification-index');
    }

    public function show(User $user, DoctorCertification $DoctorCertification)
    {
        return isAllowed($user, 'doctor-certification-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'doctor-certification-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'doctor-certification-store');
    }

    public function update(User $user, DoctorCertification $DoctorCertification)
    {        
        if($user->id == $DoctorCertification->update_by)
            return true;
        
        return isAllowed($user, 'doctor-certification-update');   
    }

    public function destroy(User $user, DoctorCertification $DoctorCertification)
    {
        return isAllowed($user, 'doctor-certification-destroy');    
    }

    public function restore(User $user, DoctorCertification $DoctorCertification)
    {
        return isAllowed($user, 'doctor-certification-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-certification-deleted');
    }

    public function delete(User $user, DoctorCertification $DoctorCertification)
    {
        return isAllowed($user, 'doctor-certification-delete');
    }
    
}
