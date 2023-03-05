<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Partnership;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class PartnershipPolicy
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
        return isAllowed($user, 'partnership-index');
    }

    public function show(User $user, Partnership $Partnership)
    {
        return isAllowed($user, 'partnership-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'partnership-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'partnership-store');
    }

    public function update(User $user, Partnership $Partnership)
    {        
        if($user->id == $Partnership->update_by)
            return true;
        
        return isAllowed($user, 'partnership-update');   
    }

    public function destroy(User $user, Partnership $Partnership)
    {
        return isAllowed($user, 'partnership-destroy');    
    }

    public function restore(User $user, Partnership $Partnership)
    {
        return isAllowed($user, 'partnership-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'partnership-deleted');
    }

    public function delete(User $user, Partnership $Partnership)
    {
        return isAllowed($user, 'partnership-delete');
    }
}
