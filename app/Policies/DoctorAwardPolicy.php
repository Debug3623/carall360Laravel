<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\DoctorAward;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorAwardPolicy
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
        return isAllowed($user, 'doctor-award-index');
    }

    public function show(User $user, DoctorAward $DoctorAward)
    {
        return isAllowed($user, 'doctor-award-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'doctor-award-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'doctor-award-store');
    }

    public function update(User $user, DoctorAward $DoctorAward)
    {        
        if($user->id == $DoctorAward->update_by)
            return true;
        
        return isAllowed($user, 'doctor-award-update');   
    }

    public function destroy(User $user, DoctorAward $DoctorAward)
    {
        return isAllowed($user, 'doctor-award-destroy');    
    }

    public function restore(User $user, DoctorAward $DoctorAward)
    {
        return isAllowed($user, 'doctor-award-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-award-deleted');
    }

    public function delete(User $user, DoctorAward $DoctorAward)
    {
        return isAllowed($user, 'doctor-award-delete');
    }
    
}
