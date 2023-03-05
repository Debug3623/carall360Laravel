<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\DoctorTreatment;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorTreatmentPolicy
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
        return isAllowed($user, 'doctor-treatment-index');
    }

    public function show(User $user, DoctorTreatment $DoctorTreatment)
    {
        return isAllowed($user, 'doctor-treatment-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'doctor-treatment-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'doctor-treatment-store');
    }

    public function update(User $user, DoctorTreatment $DoctorTreatment)
    {        
        if($user->id == $DoctorTreatment->update_by)
            return true;
        
        return isAllowed($user, 'doctor-treatment-update');   
    }

    public function destroy(User $user, DoctorTreatment $DoctorTreatment)
    {
        return isAllowed($user, 'doctor-treatment-destroy');    
    }

    public function restore(User $user, DoctorTreatment $DoctorTreatment)
    {
        return isAllowed($user, 'doctor-treatment-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-treatment-deleted');
    }

    public function delete(User $user, DoctorTreatment $DoctorTreatment)
    {
        return isAllowed($user, 'doctor-treatment-delete');
    }
    public function allTreatments(User $user)
    {
        return isAllowed($user, 'all-treatments');
    }
    
}
