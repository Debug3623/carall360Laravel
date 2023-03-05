<?php

namespace App\Policies;

use App\User;
use App\DoctorSpecialty;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorSpecialtyPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {        
        return isAllowed($user, 'doctor-specialty-index');
    }

    public function show(User $user, DoctorSpecialty $DoctorSpecialty)
    {
        return isAllowed($user, 'doctor-specialty-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'doctor-specialty-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'doctor-specialty-store');
    }

    public function update(User $user, DoctorSpecialty $DoctorSpecialty)
    {        
        if($user->id == $DoctorSpecialty->update_by)
            return true;
        
        return isAllowed($user, 'doctor-specialty-update');   
    }

    public function destroy(User $user, DoctorSpecialty $DoctorSpecialty)
    {
        return isAllowed($user, 'doctor-specialty-destroy');    
    }

    public function restore(User $user, DoctorSpecialty $DoctorSpecialty)
    {
        return isAllowed($user, 'doctor-specialty-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-specialty-deleted');
    }

    public function delete(User $user, DoctorSpecialty $DoctorSpecialty)
    {
        return isAllowed($user, 'doctor-specialty-delete');
    }
    
}
