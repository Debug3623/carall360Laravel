<?php

namespace App\Observers;

use App\Patient;

class PatientObserver
{
    /**
     * Handle the patient "created" event.
     *
     * @param  \App\Patient  $patient
     * @return void
     */
    public function created(Patient $patient)
    {
        $iId = decrypt($patient->id);
        $bIsUpdated = Patient::where('id', $iId)->update(['pk' => $iId]);
        return true;
    }

   
}
