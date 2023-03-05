<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\DoctorExperience;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorExperiencePolicy
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
        return isAllowed($user, 'doctor-experience-index');
    }

    public function show(User $user, DoctorExperience $DoctorExperience)
    {
        return isAllowed($user, 'doctor-experience-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'doctor-experience-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'doctor-experience-store');
    }

    public function update(User $user, DoctorExperience $DoctorExperience)
    {        
        if($user->id == $DoctorExperience->update_by)
            return true;
        
        return isAllowed($user, 'doctor-experience-update');   
    }

    public function destroy(User $user, DoctorExperience $DoctorExperience)
    {
        return isAllowed($user, 'doctor-experience-destroy');    
    }

    public function restore(User $user, DoctorExperience $DoctorExperience)
    {
        return isAllowed($user, 'doctor-experience-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-experience-deleted');
    }

    public function delete(User $user, DoctorExperience $DoctorExperience)
    {
        return isAllowed($user, 'doctor-experience-delete');
    }
    
}
