<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Http\Controllers\Controller;

use App\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\QB;
use App\Patient;
use Intervention\Image\Facades\Image;
use App\User;
use App\MedicalRecord as MR;
use Auth;
use Carbon\Carbon;
use Gate;

class MedicalRecordController extends Controller
{
    use \App\Traits\WebServicesDoc;
    
    public function index(Request $request)
    {
    	if (!Gate::allows('emr-list'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
            
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        $oInput['id'] = isset($oInput['id'])?decrypt($oInput['id']):null;
        $oInput['url'] = isset($oInput['url'])?decrypt($oInput['url']):null;
       
        $oValidator = Validator::make($oInput,[
            'doctor_id'   => 'required|exists:doctors,id',
            'patient_id'  => 'nullable|exists:patients,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oQb = MR::orderByDesc('updated_at')->where('doctor_id', $oInput['doctor_id'])->with(['doctorId','patientId','createdBy','updatedBy','restoredBy']);
        
        if(auth()->user()->isDoctor()){
            $oFiles = $oQb->where('is_active', 1);
        }else{
            $oQb = QB::where($oInput,"is_active",$oQb);
        }

        if($oInput['patient_id']){
            $oQb = QB::where($oInput,"patient_id",$oQb);
        }
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"file_type",$oQb);
        $oQb = QB::whereLike($oInput,"mime_type",$oQb);
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::whereLike($oInput,"file_name",$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"url",$oQb);

        $oFiles = $oQb->paginate(10);
        
		$oResponse = responseBuilder()->success(__('message.EMR.list'), $oFiles, false);

        $this->urlRec(29, 0, $oResponse);

		return $oResponse;
    }
	/**
	 * This function will responsible to upload the patient medical record to the cloud
	 * and confirm. In case $doctor_id is empty then check if logged in user is doctor
	 * then get auth() user_id as doctor_id
 	 */
    public function create(Request $request)
    {
        $oInput = $request->all() ;  
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        if(auth()->user()->isDoctor()){
            $oInput['is_active'] = 1;
        }
        
        $oValidator = Validator::make($oInput,[
            'record' 			=> 'required',
    		'description'		=> 'nullable|max:250',
            'appointment_id'	=> 'nullable|exists:appointments,id',
            // 'appointment_id'	=> 'nullable',
    		'type'				=> 'required|in:lab,prescription,radiology,other',
    		'is_active'			=> 'required|in:1,0',
            'doctor_id'         => 'required|exists:doctors,id',
            'patient_id'        => 'required|exists:patients,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oFiles = $request->file('record');
        
        if($request->hasFile('record')){
            foreach ($oFiles as $image) {
                $imageExtension = $image->extension();
                $extension = ['doc', 'docx', 'pdf', 'jpeg', 'jpg', 'png'];
                if(!in_array($imageExtension,$extension)){
                    abort(400,"The record must be a file of type: doc, docx, pdf, jpeg, jpg and png");
                }
            }
        }
        if (!Gate::allows('emr-add'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
    	
        $oPatient = Patient::where('id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        
        if(!isset($oPatient))
    		return responseBuilder()->error(__('message.EMR.noaccess'), 401, false);

        if(isset($oInput['appointment_id'])){
            $oAppointment = Appointment::where('id',$oInput['appointment_id'])->where('patient_id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
            if(!isset($oAppointment))
                return responseBuilder()->error(__('message.general.notFind'), 404, false);
        }

        if($request->hasFile('record')){
            $oMedicalrecords = [];
            $i = 0;
            foreach ($oFiles as $oFile) {
                $imageName = $oFile->getClientOriginalName();
                $imageNameDate = date('U').$i.'_'.$imageName;
                $i++;
                $extension = $oFile->extension();
                if($extension == "png" || $extension == "jpg" || $extension == "jpeg"){
                    
                    $img = Image::make($oFile);
                    $imageSize = filesize($oFile)/1000;
                    if($imageSize >= "400"){
                        $width = 1000; // your max width
                        $height = 1000; // your max height
                    
                        $img->height() > $img->width() ? $width=null : $height=null;
                        
                        $img->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    
                    $mPutFile = 'medical_record/'.md5($oInput['patient_id']).'/'.md5($imageNameDate).'.'.$extension;
                    $image = Storage::disk('s3')->put($mPutFile,$img->encode());

                }else{
                    $mPutFile = Storage::disk('s3')->putFile('medical_record/'.md5($oInput['patient_id']), $oFile);
                }
                
                $oMedicalrecord = MR::create([
                    "patient_id"     => $oInput['patient_id'],
                    "doctor_id"      => $oInput['doctor_id'],
                    "appointment_id" => isset($oInput['appointment_id'])? $oInput['appointment_id'] : null,
                    "description"    => $oInput['description'],
                    "type"           => $oInput['type'],
                    // "is_active"      => $oInput['is_active'],
                    "is_active"      => isset($oInput['is_active'])?$oInput['is_active']:1,
                    "mime_type"      => $oFile->getClientMimeType(),
                    "file_type"      => $oFile->extension(),
                    "url"            => $mPutFile,
                    "file_name"      => substr($oFile->getClientOriginalName(), 0, 250),
                    'created_by'    => Auth::user()->id,
                    'updated_by'    => Auth::user()->id,
                    'created_at'    => Carbon::now()->toDateTimeString(),
                    'updated_at'    => Carbon::now()->toDateTimeString(),
                ]);
                $oMedicalrecords[] = MR::where('id', decrypt($oMedicalrecord->id))->with(['doctorId','patientId','createdBy','updatedBy','restoredBy'])->first();
            }
            $oResponse = responseBuilder()->success(__('message.EMR.create'), $oMedicalrecords, false);
            $this->urlRec(29, 1, $oResponse);    
            
            return $oResponse;   
        }else{
            $oResponse = responseBuilder()->error(__('There is an issue with file'), 403, false);
            return $oResponse;
        }
        $oResponse = responseBuilder()->error(__('There is an issue with file'), 403, false);
        return $oResponse;
    }
    /**
     * If doctor then patient id should be in param
     * in case of patient then param will be optional
     * If doctor viewing patient record then only active recordss will be displayed
     * bu for patient all records will be rendered
     */
/**
     * Show one record full without actual file rendering
     */
    public function show($id)
    {
    	$id = decrypt($id);

    	$oMedRec = MR::with(['doctorId','patientId','createdBy','updatedBy','restoredBy'])->findOrFail($id);

    	if (!Gate::allows('emr-show', $oMedRec))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

		$oResponse = responseBuilder()->success(__('message.EMR.view'), $oMedRec, false);
        
        $this->urlRec(29, 2, $oResponse);

		return $oResponse;

    }

    /**
     * update the medical record associated with doctor's patient or patient self service
     * Appointment record can be set to null
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();  
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        $id = decrypt($id);
        
        $oValidator = Validator::make($oInput,[
            'description'		=> 'nullable|string|max:250',
    		// 'appointment_id'	=> 'nullable',
    		'appointment_id'	=> 'nullable|exists:appointments,id',
    		'type'				=> 'required|in:lab,prescription,radiology,other',
    		'is_active'			=> 'required|in:1,0',
            'doctor_id'         => 'required|exists:doctors,id',
            'patient_id'        => 'required|exists:patients,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oMedicalRecord = MR::findOrFail($id);

        if (!Gate::allows('emr-update', $oMedicalRecord))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oPatient = Patient::where('id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oPatient))
    		return responseBuilder()->error(__('message.EMR.noaccess'), 401, false);
        
        if(isset($oInput['appointment_id'])){
            $oAppointment = Appointment::where('id',$oInput['appointment_id'])->where('patient_id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
            if(!isset($oAppointment))
                return responseBuilder()->error(__('message.general.notFind'), 404, false);
        }
		$oUpdatedRec = $oMedicalRecord->update([
            "patient_id"     => $oInput['patient_id'],
            "doctor_id"      => $oInput['doctor_id'],
            "appointment_id" => isset($oInput['appointment_id'])? $oInput['appointment_id'] : null,
            "description"    => $oInput['description'],
            "type"           => $oInput['type'],
            "is_active"      => $oInput['is_active'],
            'updated_by'    => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);

        $oMedicalrecord = MR::where('id', $id)->with(['doctorId','patientId','createdBy','updatedBy','restoredBy'])->first();

		$oResponse = responseBuilder()->success(__('message.EMR.update'), $oMedicalrecord, false);
        $this->urlRec(29, 3, $oResponse);
        
        return $oResponse;
    }

    // Soft Delete Patients 
    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oInput = DecryptId($oInput);
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:medical_records,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $oInput['ids'];
       
        $allMedicalRecord = MedicalRecord::findOrFail($aIds);
        
        foreach($allMedicalRecord as $oRow)
            if (!Gate::allows('emr-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
    
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oMedicalRecord = MedicalRecord::find($id);
                if($oMedicalRecord){
                    $oMedicalRecord->update(['deleted_by' => Auth::user()->id]);
                    $oMedicalRecord->delete();
                }
            }
        }else{
            $oMedicalRecord = MedicalRecord::findOrFail($aIds);
        
            $oMedicalRecord->update(['deleted_by' => Auth::user()->id]);
            $oMedicalRecord->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Medical Record"]));
        $this->urlRec(29, 4, $oResponse);
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request)
    {
        if (!Gate::allows('emr-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
            
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        $oInput['id'] = isset($oInput['id'])?decrypt($oInput['id']):null;
        $oInput['url'] = isset($oInput['url'])?decrypt($oInput['url']):null;
       
        $oValidator = Validator::make($oInput,[
            'doctor_id'   => 'required|exists:doctors,id',
            'patient_id'  => 'required|exists:patients,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oQb = MR::onlyTrashed()->where('patient_id', $oInput['patient_id'])->where('doctor_id', $oInput['doctor_id'])->with(['doctorId','patientId','createdBy','updatedBy','restoredBy']);
        
        if(auth()->user()->isDoctor()){
            $oFiles = $oQb->where('is_active', 1);
        }else{
            $oQb = QB::where($oInput,"is_active",$oQb);
        }
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"file_type",$oQb);
        $oQb = QB::whereLike($oInput,"mime_type",$oQb);
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::whereLike($oInput,"file_name",$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"url",$oQb);

        $oFiles = $oQb->get()->toArray();

        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Medical record"]), $oFiles, false);
        
        $this->urlRec(29, 5, $oResponse);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {
        $oInput = $request->all();
        $oInput = DecryptId($oInput);
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:medical_records,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $oInput['ids'];
        
        $allMedicalRecord = MedicalRecord::onlyTrashed()->findOrFail($aIds);
        
        foreach($allMedicalRecord as $oRow)
            if (!Gate::allows('emr-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oMedicalRecord = MedicalRecord::onlyTrashed()->find($id);
                if($oMedicalRecord){
                    $oMedicalRecord->update([
                        'restored_by' => Auth::user()->id,
                        'restored_at' => Carbon::now()->toDateTimeString(),                  
                        ]);
                    $oMedicalRecord->restore();
                }
            }
        }else{
            $oMedicalRecord = MedicalRecord::onlyTrashed()->findOrFail($aIds);
            $oMedicalRecord->update([
                'restored_by' => Auth::user()->id,
                'restored_at' => Carbon::now()->toDateTimeString(),                  
                ]);
            $oMedicalRecord->restore();
        }
        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Medical Record"]));
        
        $this->urlRec(29, 6, $oResponse);
        return $oResponse;
    }
    
    /**
     * Delete the EMR permanently from the database with first removing the file from s3 bucket
     */
    public function delete($id)
    {
    	$id = decrypt($id);

    	$oMedRec = MR::findOrFail($id);

    	if (!Gate::allows('emr-delete', $oMedRec))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

    	$bFileRemove = Storage::disk('s3')->delete(decrypt($oMedRec->url)); 

    	if($bFileRemove){

    		MR::where('id', $id)->forceDelete();

    		$oResponse = responseBuilder()->success(__('message.EMR.delete'), '', false);
			$this->urlRec(29, 7, $oResponse);

			return $oResponse;

    	}
    	return responseBuilder()->error(__('message.EMR.noaccess'), 401, false);
    }
        /**
     * Render the file to be disapled by the API on the screen
     * This API needs to be called within frame/iframe to be shown as integrated part of application
     */
    public function render(Request $request, $url)
    {
    	
    	$sFileUrl = decrypt($url);

    	$oMedRec = MR::where('url', $sFileUrl)->first();
        
        if(!isset($oMedRec))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
        
        // if (!Gate::allows('emr-file-render', $oMedRec))
        //     return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        // if($oMedRec->is_active != 1)
        // 	return responseBuilder()->error(__('message.EMR.inactive'), 401, false); 

    	$iPatientId = $oMedRec->patient_id;
    	$iDoctorId = $oMedRec->doctor_id;
        
        if(auth()->user()->isPatient() && $iPatientId != auth()->user()->patient_id)
    		return responseBuilder()->error(__('message.EMR.noaccess'), 401, false); 


    	if(auth()->user()->isDoctor() && $iDoctorId != auth()->user()->doctor_id)
    		return responseBuilder()->error(__('message.EMR.noaccess'), 401, false); 

        // $oResponse = responseBuilder()->success(__('message.EMR.list'), $oMedRec, false);    	
        // $this->urlRec(29, 8, $oResponse);
        // return response(Storage::disk('s3')->get($sFileUrl))->header('Content-Type', $oMedRec->mime_type);
        
        $file = Storage::disk('s3')->get($sFileUrl);
        $data['image'] = base64_encode($file);
        $data['file_type'] = $oMedRec->file_type;
        $data['mime_type'] = $oMedRec->mime_type;
        $data['file_name'] = $oMedRec->file_name;

        $oResponse = responseBuilder()->success(__('message.EMR.list'), $data, false);

        return $oResponse;
    } 
}
