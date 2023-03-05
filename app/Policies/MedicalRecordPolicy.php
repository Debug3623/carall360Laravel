<?php

namespace App\Policies;

use App\MedicalRecord;
use App\User;
use App\Permission;

use Illuminate\Auth\Access\HandlesAuthorization;

class MedicalRecordPolicy
{
    use HandlesAuthorization;


    // public function before(User $user)
    // {
    //     if(!$user->isPatient() && !$user->isDoctor())
    //         return false;
    // }


    /**
     * Determine whether the user can view the medical record.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalRecord  $medicalRecord
     * @return mixed
     */
    public function view(User $user, MedicalRecord $medicalRecord)
    {

        if($user->isDoctor() && $user->doctor_id != $medicalRecord->doctor_id)
            return false;

        if($user->isPatient() && $user->patient_id != $medicalRecord->patient_id)
            return false;

        return isAllowed($user, 'emr-view');
    }


    /**
     * List all records.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalRecord  $medicalRecord
     * @return mixed
     */
    public function list(User $user)
    {
        return isAllowed($user, 'emr-list');
    }

    /**
     * Determine whether the user can create medical records.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return isAllowed($user, 'emr-add');
    }

    /**
     * Determine whether the user can update the medical record.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalRecord  $medicalRecord
     * @return mixed
     */
    public function update(User $user, MedicalRecord $medicalRecord)
    {

        if($user->isDoctor() && $user->doctor_id != $medicalRecord->doctor_id)
            return false;

        if($user->isPatient() && $user->patient_id != $medicalRecord->patient_id)
            return false;

        return isAllowed($user, 'emr-update');
        
    }

    /**
     * Determine whether the user can show the medical record.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalRecord  $medicalRecord
     * @return mixed
     */
    public function show(User $user, MedicalRecord $medicalRecord)
    {

        if($user->isDoctor() && $user->doctor_id != $medicalRecord->doctor_id)
            return false;

        if($user->isPatient() && $user->patient_id != $medicalRecord->patient_id)
            return false;
            
        return isAllowed($user, 'emr-show');
        
    }

    public function destroy(User $user, MedicalRecord $MedicalRecord)
    {
        return isAllowed($user, 'emr-destroy');    
    }

    public function restore(User $user, MedicalRecord $MedicalRecord)
    {
        return isAllowed($user, 'emr-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'emr-deleted');
    }
    /**
     * Determine whether the user can delete the medical record.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalRecord  $medicalRecord
     * @return mixed
     */
    public function delete(User $user, MedicalRecord $medicalRecord)
    {

        if($user->isDoctor() && $user->doctor_id != $medicalRecord->doctor_id)
            return false;

        if($user->isPatient() && $user->patient_id != $medicalRecord->patient_id)
            return false;

        return isAllowed($user, 'emr-delete');
    }

    
}
