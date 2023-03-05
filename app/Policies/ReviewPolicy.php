<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Review;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {        
        return isAllowed($user, 'review-index');
    }

    public function show(User $user, Review $Review)
    {
        return isAllowed($user, 'review-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'review-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'review-store');
    }

    public function update(User $user, Review $Review)
    {        
        if($user->id == $Review->update_by)
            return true;
        
        return isAllowed($user, 'review-update');   
    }

    public function destroy(User $user, Review $Review)
    {
        return isAllowed($user, 'review-destroy');    
    }

    public function statusUpdate(User $user, Review $Review)
    {        
        if($user->id == $Review->update_by)
            return true;
        
        return isAllowed($user, 'review-status-update');   
    }
    public function indexPatient(User $user)
    {        
        return isAllowed($user, 'patient-review-index');
    }

    public function showPatient(User $user, Review $Review)
    {
        return isAllowed($user, 'patient-review-show');   
    }

    public function createPatient(User $user)
    {
        return isAllowed($user, 'patient-review-create');
    }

    public function storePatient(User $user)
    {
        return isAllowed($user, 'patient-review-store');
    }

    public function updatePatient(User $user, Review $Review)
    {        
        if($user->id == $Review->update_by)
            return true;
        
        return isAllowed($user, 'patient-review-update');
    }
}
