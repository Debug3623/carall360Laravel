<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\DoctorMedicine;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;



class DoctorMedicinePolicy
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
        return isAllowed($user, 'doctor-medicine-index');
    }

    public function show(User $user, DoctorMedicine $DoctorMedicine)
    {
        return isAllowed($user, 'doctor-medicine-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'doctor-medicine-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'doctor-medicine-store');
    }

    public function update(User $user, DoctorMedicine $DoctorMedicine)
    {        
        if($user->id == $DoctorMedicine->update_by)
            return true;
        
        return isAllowed($user, 'doctor-medicine-update');   
    }

    public function destroy(User $user, DoctorMedicine $DoctorMedicine)
    {
        return isAllowed($user, 'doctor-medicine-destroy');    
    }

    public function restore(User $user, DoctorMedicine $DoctorMedicine)
    {
        return isAllowed($user, 'doctor-medicine-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'doctor-medicine-deleted');
    }

    public function delete(User $user, DoctorMedicine $DoctorMedicine)
    {
        return isAllowed($user, 'doctor-medicine-delete');
    }
    
}
