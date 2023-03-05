<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\PatientRiskfactor;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class RiskfactorPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {        
        return isAllowed($user, 'riskfactor-index');
    }

    public function show(User $user, PatientRiskfactor $PatientRiskfactor)
    {
        return isAllowed($user, 'riskfactor-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'riskfactor-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'riskfactor-store');
    }

    public function update(User $user, PatientRiskfactor $PatientRiskfactor)
    {        
        if($user->id == $PatientRiskfactor->update_by)
            return true;
        
        return isAllowed($user, 'riskfactor-update');   
    }

    public function destroy(User $user, PatientRiskfactor $PatientRiskfactor)
    {
        return isAllowed($user, 'riskfactor-destroy');    
    }

    public function restore(User $user, PatientRiskfactor $PatientRiskfactor)
    {
        return isAllowed($user, 'riskfactor-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'riskfactor-deleted');
    }

    public function delete(User $user, PatientRiskfactor $PatientRiskfactor)
    {
        return isAllowed($user, 'riskfactor-delete');
    }
    
}
