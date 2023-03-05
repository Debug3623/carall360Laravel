<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\PlanCategory;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class PlanCategoryPolicy
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
        return isAllowed($user, 'plan-category-index');
    }

    public function show(User $user, PlanCategory $PlanCategory)
    {
        return isAllowed($user, 'plan-category-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'plan-category-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'plan-category-store');
    }

    public function update(User $user, PlanCategory $PlanCategory)
    {        
        if($user->id == $PlanCategory->update_by)
            return true;
        
        return isAllowed($user, 'plan-category-update');   
    }

    public function destroy(User $user, PlanCategory $PlanCategory)
    {
        return isAllowed($user, 'plan-category-destroy');    
    }

    public function restore(User $user, PlanCategory $PlanCategory)
    {
        return isAllowed($user, 'plan-category-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'plan-category-deleted');
    }

    public function delete(User $user, PlanCategory $PlanCategory)
    {
        return isAllowed($user, 'plan-category-delete');
    }
}
