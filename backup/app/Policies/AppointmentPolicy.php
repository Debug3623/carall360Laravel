<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Appointment;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class AppointmentPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {        
        return isAllowed($user, 'appointment-index');
    }

    public function show(User $user, Appointment $Appointment)
    {
        return isAllowed($user, 'appointment-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'appointment-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'appointment-store');
    }

    public function update(User $user, Appointment $Appointment)
    {        
        if($user->id == $Appointment->update_by)
            return true;
        
        return isAllowed($user, 'appointment-update');   
    }

    public function destroy(User $user, Appointment $Appointment)
    {
        return isAllowed($user, 'appointment-destroy');    
    }

    public function restore(User $user, Appointment $Appointment)
    {
        return isAllowed($user, 'appointment-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'appointment-deleted');
    }

    public function delete(User $user, Appointment $Appointment)
    {
        return isAllowed($user, 'appointment-delete');
    }
    
}
