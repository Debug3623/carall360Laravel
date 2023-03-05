<?php

namespace App\Policies;

use App\User;
use App\Prescription;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrescriptionPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {        
        return isAllowed($user, 'prescription-index');
    }

    public function show(User $user, Prescription $Prescription)
    {
        return isAllowed($user, 'prescription-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'prescription-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'prescription-store');
    }

    public function update(User $user, Prescription $Prescription)
    {        
        if($user->id == $Prescription->update_by)
            return true;
        
        return isAllowed($user, 'prescription-update');   
    }

    public function destroy(User $user, Prescription $Prescription)
    {
        return isAllowed($user, 'prescription-destroy');    
    }

    public function restore(User $user, Prescription $Prescription)
    {
        return isAllowed($user, 'prescription-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'prescription-deleted');
    }

    public function delete(User $user, Prescription $Prescription)
    {
        return isAllowed($user, 'prescription-delete');
    }
    
}
