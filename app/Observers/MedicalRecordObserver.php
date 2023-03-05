<?php

namespace App\Observers;

use App\MedicalRecord;

class MedicalRecordObserver
{
    public function created(MedicalRecord $medicalRecord)
    {
        $iId = decrypt($medicalRecord->id);
        $bIsUpdated = MedicalRecord::where('id', $iId)->update(['pk' => $iId]);
        return true;
    }  
}
