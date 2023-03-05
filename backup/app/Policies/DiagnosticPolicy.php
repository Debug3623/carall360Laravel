<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Diagnostic;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DiagnosticPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {        
        return isAllowed($user, 'diagnostic-index');
    }

    public function show(User $user, Diagnostic $Diagnostic)
    {
        return isAllowed($user, 'diagnostic-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'diagnostic-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'diagnostic-store');
    }

    public function update(User $user, Diagnostic $Diagnostic)
    {        
        if($user->id == $Diagnostic->update_by)
            return true;
        
        return isAllowed($user, 'diagnostic-update');   
    }

    public function destroy(User $user, Diagnostic $Diagnostic)
    {
        return isAllowed($user, 'diagnostic-destroy');    
    }

    public function restore(User $user, Diagnostic $Diagnostic)
    {
        return isAllowed($user, 'diagnostic-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'diagnostic-deleted');
    }

    public function delete(User $user, Diagnostic $Diagnostic)
    {
        return isAllowed($user, 'diagnostic-delete');
    }
    
}
