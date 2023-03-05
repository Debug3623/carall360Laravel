<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\DoctorSchedule;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorSchedulePolicy
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
        return isAllowed($user, 'doctor-schedule-index');
    }

    public function show(User $user, DoctorSchedule $DoctorSchedule)
    {
        return isAllowed($user, 'doctor-schedule-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'doctor-schedule-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'doctor-schedule-store');
    }

    public function update(User $user, DoctorSchedule $DoctorSchedule)
    {        
        if($user->id == $DoctorSchedule->update_by)
            return true;
        
        return isAllowed($user, 'doctor-schedule-update');   
    }

    public function destroy(User $user, DoctorSchedule $DoctorSchedule)
    {
        return isAllowed($user, 'doctor-schedule-destroy');    
    }

    public function restore(User $user, DoctorSchedule $DoctorSchedule)
    {
        return isAllowed($user, 'doctor-schedule-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-schedule-deleted');
    }

    public function delete(User $user, DoctorSchedule $DoctorSchedule)
    {
        return isAllowed($user, 'doctor-schedule-delete');
    }



    public function updateVocationMood(User $user, DoctorSchedule $DoctorSchedule)
    {
        
        
        if($user->id == $DoctorSchedule->update_by)
            return true;
        
        return isAllowed($user, 'doctor-vocation-update');
    
    }
}
