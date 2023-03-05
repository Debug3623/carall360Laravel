<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\DoctorAssistant;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorAssistantPolicy
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
        return isAllowed($user, 'doctor-assistant-index');
    }

    public function show(User $user, DoctorAssistant $DoctorAssistant)
    {
        return isAllowed($user, 'doctor-assistant-assistantstant-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'doctor-assistant-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'doctor-assistant-store');
    }

    public function update(User $user, DoctorAssistant $DoctorAssistant)
    {        
        if($user->id == $DoctorAssistant->update_by)
            return true;
        
        return isAllowed($user, 'doctor-assistant-update');   
    }

    public function destroy(User $user, DoctorAssistant $DoctorAssistant)
    {
        return isAllowed($user, 'doctor-assistant-destroy');    
    }

    public function restore(User $user, DoctorAssistant $DoctorAssistant)
    {
        return isAllowed($user, 'doctor-assistant-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-assistant-deleted');
    }

    public function delete(User $user, DoctorAssistant $DoctorAssistant)
    {
        return isAllowed($user, 'doctor-assistant-delete');
    }    
}
