<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\DoctorScheduleDay;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorScheduleDayPolicy
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
        return isAllowed($user, 'schedule-slot-index');
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function show(User $user, DoctorScheduleDay $DoctorScheduleDay)
    {
        return isAllowed($user, 'schedule-slot-show');
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return isAllowed($user, 'schedule-slot-create');
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function store(User $user)
    {
        return isAllowed($user, 'schedule-slot-store');
    }
    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function update(User $user, DoctorScheduleDay $DoctorScheduleDay)
    {
        
        
        if($user->id == $DoctorScheduleDay->update_by)
            return true;
        
        return isAllowed($user, 'schedule-slot-update');
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function destroy(User $user, DoctorScheduleDay $DoctorScheduleDay)
    {
        return isAllowed($user, 'schedule-slot-destroy');
    }
}
