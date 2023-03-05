<?php

namespace App\Http\Controllers\DoctorApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Doctor;
use App\Patient;
use App\MedicalRecord as MR;

use Gate;

/**
 * All doctor - partient related functionality coded in following controller
 * This includes
 * - Doctor - patient record update
 * - Patient self record update
 * - Encrypte Decrypt - medical record
 * - upload to s3 bucket
 */

class PatientManagementController extends Controller
{
    use \App\Traits\WebServicesDoc;

	/**
	 * This function will responsible to upload the patient medical record to the cloud
	 * and confirm. In case $doctor_id is empty then check if logged in user is doctor
	 * then get auth() user_id as doctor_id
 	 */
    public function create(Request $request, $patient_id = '')
    {

    	$aData = $request->all();


    	$aValidationRules = array(
    		'record' 			=> 'required|file|mimes:jpeg,jpg,png,mp3,mp4,doc,docx,pdf',
    		'description'		=> 'required|string|max:250',
    		'appointment_id'	=> 'nullable|numeric',
    		'type'				=> 'required|in:lab,prescription,radiology,other',
    		'is_active'			=> 'required|in:1,0'
    	);

    	$oValidator = Validator::make($aData, $aValidationRules);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }


        if (!Gate::allows('emr-add'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

    	
    	$iPatientId = $patient_id;

    	
    	if(auth()->user()->isPatient()){
    		$oPatient = Patient::where('id', auth()->user()->patient_id)->first();
    		$iDoctorId = $oPatient->doctor_id;
    		$iPatientId = auth()->user()->patient_id;
    	}

    	if(auth()->user()->isDoctor() && empty($iPatientId))
    		return responseBuilder()->error(__('message.EMR.noaccess'), 401, false); 

    	if(auth()->user()->isDoctor()){
    		$oPatient = Patient::where('id', $iPatientId)->first();
    		$iDoctorId = auth()->user()->doctor_id;
    		if($oPatient->doctor_id != $iDoctorId)
    			return responseBuilder()->error(__('message.EMR.noaccess'), 401, false); 
    	}


    	$oFile = $request->file('record');
    	$mPutFile = Storage::disk('s3')->putFile('files/'.md5($iPatientId), $oFile);
    	//dump($mPutFile);

    	$aMedRecord = array();
    	$aMedRecord['patient_id'] 			= $iPatientId;
    	$aMedRecord['doctor_id'] 			= $iDoctorId;

    	$aMedRecord['appointment_id'] 		= !empty($aData['appointment_id'])?$aData['appointment_id']:null;
    	$aMedRecord['description'] 			= $aData['description'];
    	$aMedRecord['type'] 				= $aData['type'];
    	$aMedRecord['is_active'] 			= $aData['is_active'];

    	$aMedRecord['mime_type'] 			= $oFile->getClientMimeType();
    	$aMedRecord['file_type'] 			= $oFile->extension();
    	$aMedRecord['url'] 					= $mPutFile;
    	$aMedRecord['file_name'] 			= substr($oFile->getClientOriginalName(), 0, 250);
    	
		$aMedRecord['created_by'] 			= auth()->user()->id;
    	$aMedRecord['updated_by'] 			= auth()->user()->id;
    	
    	//dump($aMedRecord);

    	$bNewMediclaRecords = MR::create($aMedRecord);

    	if($bNewMediclaRecords){
    		$oResponse = responseBuilder()->success(__('message.EMR.create'), $bNewMediclaRecords, false);
        	$this->urlRec(106, 3, $oResponse);
        
        	return $oResponse;
    	}

    }



    /**
     * Render the file to be disapled by the API on the screen
     * This API needs to be called within frame/iframe to be shown as integrated part of application
     */
    public function render(Request $request, $file)
    {
    	
    	$sFileUrl = decrypt($file);

    	$sActor = auth()->user()->getActor();

		$oMedRec = MR::where('url', $sFileUrl)->first();

		if (!Gate::allows('emr-file-render', $oMedRec))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        if($oMedRec->is_active != 1)
        	return responseBuilder()->error(__('message.EMR.inactive'), 401, false); 

    	$iPatientId = $oMedRec->patient_id;
    	$iDoctorId = $oMedRec->doctor_id;

    	$sPlainUrl = decrypt($oMedRec->url);
    	//dd($sPlainUrl);
    	if(auth()->user()->isPatient() && $iPatientId != auth()->user()->patient_id)
    		return responseBuilder()->error(__('message.EMR.noaccess'), 401, false); 


    	if(auth()->user()->isDoctor() && $iDoctorId != auth()->user()->doctor_id)
    		return responseBuilder()->error(__('message.EMR.noaccess'), 401, false); 

    	
    	$this->urlRec(106, 1, $oMedRec);
    	return response(Storage::disk('s3')->get($sPlainUrl))->header('Content-Type', $oMedRec->mime_type);

    }

    /**
     * If doctor then patient id should be in param
     * in case of patient then param will be optional
     * If doctor viewing patient record then only active recordss will be displayed
     * bu for patient all records will be rendered
     */
    public function index(Request $request, $patient_id = '')
    {

    	if (!Gate::allows('emr-list'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


    	if(auth()->user()->isPatient()){
    		$iPatientId = auth()->user()->patient_id;
    		$oPatient = Patient::where('id', $iPatientId)->first();
    		$iDoctorId = $oPatient->doctor_id;
    	}

		if(auth()->user()->isDoctor()){

			$iPatientId = $patient_id;
    		$iDoctorId = auth()->user()->doctor_id;
    		$oPatient = Patient::where('id', $iPatientId)->first();
    		if($oPatient->doctor_id != $iDoctorId)
				return responseBuilder()->error(__('message.EMR.noaccess'), 401, false);     			
		}

		//all validations passed for verification, now fetch the list of files uploaded

		$oFiles = MR::where('patient_id', $iPatientId)
						->where('doctor_id', $iDoctorId);

		if(auth()->user()->isDoctor()) //doctor should only be able to see active records by patient
			$oFiles = $oFiles->where('is_active', 1);


		$oFiles = $oFiles->get()->toArray();
		
		//dd($oFiles);


		$oResponse = responseBuilder()->success(__('message.EMR.list'), $oFiles, false);
		$this->urlRec(106, 2, $oResponse);

		return $oResponse;
	

    }


    /**
     * update the medical record associated with doctor's patient or patient self service
     * Appointment record can be set to null
     */
    public function update(Request $request, $id)
    {
    	$aData = $request->all();

    	$iMediaId = decrypt($id);


    	$aValidationRules = array(
    		'description'		=> 'required|string|max:250',
    		'appointment_id'	=> 'nullable|numeric',
    		'type'				=> 'required|in:lab,prescription,radiology,other',
    		'is_active'			=> 'required|in:1,0'
    	);

    	$oValidator = Validator::make($aData, $aValidationRules);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oCurrentMedRecord = MR::findOrFail($iMediaId);


        if (!Gate::allows('emr-update', $oCurrentMedRecord))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        
		$aUpdate = array(
			'description' 		=> $aData['description'],
			'appointment_id'	=> !empty($aData['appointment_id'])?$aData['appointment_id']:null,
			'type'				=> $aData['type'],
			'is_active'			=> $aData['is_active'],
			'updated_by'		=> auth()->user()->id
		);

		$oUpdatedRec = MR::where('id', $iMediaId)->update($aUpdate);

		$oResponse = responseBuilder()->success(__('message.EMR.update'), $oUpdatedRec, false);
        $this->urlRec(106, 3, $oResponse);
        
        return $oResponse;
    }

    /**
     * Show one record full without actual file rendering
     */
    public function show($id)
    {
    	$iMedicalRecord = decrypt($id);

    	$oMedRec = MR::findOrFail($iMedicalRecord);

    	if (!Gate::allows('emr-show', $oMedRec))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


		$oResponse = responseBuilder()->success(__('message.EMR.view'), $oMedRec, false);
		$this->urlRec(106, 4, $oResponse);

		return $oResponse;

    }

    /**
     * Delete the EMR permanently from the database with first removing the file from s3 bucket
     */
    public function delete($id)
    {
    	$iMedicalRecord = decrypt($id);

    	$oMedRec = MR::findOrFail($iMedicalRecord);

    	if (!Gate::allows('emr-delete', $oMedRec))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

    	$bFileRemove = Storage::disk('s3')->delete(decrypt($oMedRec->url)); 

    	if($bFileRemove){

    		MR::where('id', $iMedicalRecord)->delete($iMedicalRecord);

    		$oResponse = responseBuilder()->success(__('message.EMR.delete'), '', false);
			$this->urlRec(106, 5, $oResponse);

			return $oResponse;

    	}

    	return responseBuilder()->error(__('message.EMR.noaccess'), 401, false);

    }

}
