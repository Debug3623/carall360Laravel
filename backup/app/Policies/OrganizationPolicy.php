<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Organization;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class OrganizationPolicy
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
        return isAllowed($user, 'organization-index');
    }

    public function show(User $user, Organization $Organization)
    {
        return isAllowed($user, 'organization-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'organization-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'organization-store');
    }

    public function update(User $user, Organization $Organization)
    {        
        if($user->id == $Organization->update_by)
            return true;
        
        return isAllowed($user, 'organization-update');   
    }

    public function destroy(User $user, Organization $Organization)
    {
        return isAllowed($user, 'organization-destroy');    
    }

    public function restore(User $user, Organization $Organization)
    {
        return isAllowed($user, 'organization-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'organization-deleted');
    }

    public function delete(User $user, Organization $Organization)
    {
        return isAllowed($user, 'organization-delete');
    }
    public function createOrganizationPermissions(User $user)
    {
        return isAllowed($user, 'create-organization-permissions');
    }
}
