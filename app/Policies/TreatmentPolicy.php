<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Treatment;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class TreatmentPolicy
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
        return isAllowed($user, 'treatment-index');
    }

    public function show(User $user, Treatment $Treatment)
    {
        return isAllowed($user, 'treatment-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'treatment-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'treatment-store');
    }

    public function update(User $user, Treatment $Treatment)
    {        
        if($user->id == $Treatment->update_by)
            return true;
        
        return isAllowed($user, 'treatment-update');   
    }

    public function destroy(User $user, Treatment $Treatment)
    {
        return isAllowed($user, 'treatment-destroy');    
    }

    public function restore(User $user, Treatment $Treatment)
    {
        return isAllowed($user, 'treatment-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'treatment-deleted');
    }

    public function delete(User $user, Treatment $Treatment)
    {
        return isAllowed($user, 'treatment-delete');
    }
    public function parentTreatments(User $user)
    {
        return isAllowed($user, 'parent-treatment-index');
    }    
}
