<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class RolePolicy
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
        
        return isAllowed($user, 'role-index');
        
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function show(User $user, Role $role)
    {
        return isAllowed($user, 'role-show');
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return isAllowed($user, 'role-create');
    }
    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function store(User $user)
    {
        return isAllowed($user, 'role-store');
    }
    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        
        
        if($user->id == $role->update_by)
            return true;

        return isAllowed($user, 'role-update');

       
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function destroy(User $user, Role $role)
    {
        return isAllowed($user, 'role-destroy');

    }

    /**
     * Determine whether the user can restore the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function restore(User $user, Role $role)
    {
        return isAllowed($user, 'role-restore');

    }


    /**
     * Determine whether the user can restore the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function deleted(User $user)
    {

        return isAllowed($user, 'role-restore');
    }

    /**
     * TODO: Delete Restore functionality to be decided against each controller CRUD
     * 
     * Determine whether the user can permanently delete the role.
     * 
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {

        return isAllowed($user, 'role-restore');

    }


    /**
     * Check if user can assign the role with permissions mapping
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function createRolePermissions(User $user)
    {
        return isAllowed($user, 'role-create-permission');

    }
}
