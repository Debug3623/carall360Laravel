<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Medicine;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class MedicinePolicy
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
        return isAllowed($user, 'medicine-index');
    }

    public function show(User $user, Medicine $Medicine)
    {
        return isAllowed($user, 'medicine-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'medicine-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'medicine-store');
    }

    public function update(User $user, Medicine $Medicine)
    {        
        if($user->id == $Medicine->update_by)
            return true;
        
        return isAllowed($user, 'medicine-update');   
    }

    public function destroy(User $user, Medicine $Medicine)
    {
        return isAllowed($user, 'medicine-destroy');    
    }

    public function restore(User $user, Medicine $Medicine)
    {
        return isAllowed($user, 'medicine-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'medicine-deleted');
    }

    public function delete(User $user, Medicine $Medicine)
    {
        return isAllowed($user, 'medicine-delete');
    }    
}
