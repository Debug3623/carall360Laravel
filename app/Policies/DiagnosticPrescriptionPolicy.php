<?php

namespace App\Policies;

use App\User;
use App\PrescriptionDiagnostic;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiagnosticPrescriptionPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {        
        return isAllowed($user, 'diagnostic-prescription-index');
    }

    public function show(User $user, PrescriptionDiagnostic $PrescriptionDiagnostic)
    {
        return isAllowed($user, 'diagnostic-prescription-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'diagnostic-prescription-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'diagnostic-prescription-store');
    }

    public function update(User $user, PrescriptionDiagnostic $PrescriptionDiagnostic)
    {        
        if($user->id == $PrescriptionDiagnostic->update_by)
            return true;
        
        return isAllowed($user, 'diagnostic-prescription-update');   
    }

    public function destroy(User $user, PrescriptionDiagnostic $PrescriptionDiagnostic)
    {
        return isAllowed($user, 'diagnostic-prescription-destroy');    
    }
}
