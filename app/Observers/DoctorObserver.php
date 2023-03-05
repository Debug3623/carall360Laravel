<?php

namespace App\Observers;

use App\Doctor;

class DoctorObserver
{
    /**
     * Handle the doctor "created" event.
     *
     * @param  \App\Doctor  $doctor
     * @return void
     */
    public function created(Doctor $doctor)
    {
        $iId = decrypt($doctor->id);
        $bIsUpdated = Doctor::where('id', $iId)->update(['pk' => $iId]);
        return true;
    }

   
}
