<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Appointment;
use App\Doctor;
use App\DoctorMedicine;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Patient;
use Illuminate\Support\Facades\Storage;
use App\Prescription;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
class PrescriptionController extends Controller
{
    use \App\Traits\WebServicesDoc;

    public function index(Request $request)
    {
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        if (!Gate::allows('prescription-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        $oInput['url'] = isset($oInput['url'])?decrypt($oInput['url']):null;

        $oQb = Prescription::orderByDesc('updated_at')->whereNotNull('medicine_id')->whereNull('file_type')->with(['appointmentId','medicineId','patientId','doctorId','createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;
            
            $oQb = $oQb->whereHas('doctorId', function ($q) use($oInput) {
                $q->where('organization_id', $oInput['organization_id']);
            });
        }elseif ($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;    
        }elseif ($oAuth->isPatient()) {
            $oInput['patient_id'] = $oAuth->patient_id;    
        }
        $oQb = $oQb->whereHas('appointmentId', function ($q) use($oInput) {
            $q->whereIn('status', Appointment::$APPROVEDSTATUS);
        });
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"dose_quantity",$oQb);
        $oQb = QB::whereLike($oInput,"dose_remarks",$oQb);
        $oQb = QB::where($oInput,"is_morning",$oQb);
        $oQb = QB::where($oInput,"is_afternoon",$oQb);
        $oQb = QB::where($oInput,"is_evening",$oQb);
        $oQb = QB::where($oInput,"is_week",$oQb);
        $oQb = QB::where($oInput,"is_once",$oQb);
        $oQb = QB::where($oInput,"days_for",$oQb);
        $oQb = QB::whereLike($oInput,"file_type",$oQb);
        $oQb = QB::whereLike($oInput,"mime_type",$oQb);
        $oQb = QB::whereLike($oInput,"file_name",$oQb);
        $oQb = QB::whereLike($oInput,"url",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"patient_id",$oQb);
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::where($oInput,"medicine_id",$oQb);
        
        $oPrescriptions = $oQb->paginate(10);
        foreach ($oPrescriptions as $prescription) {
            $prescription['patientId']      = Patient::where('id',$prescription->patient_id)->withTrashed()->first();
            $prescription['doctorId']       = Doctor::where('id',$prescription->doctor_id)->withTrashed()->first();
            $prescription['appointmentId']  = Appointment::where('id',$prescription->appointment_id)->withTrashed()->first();
            $prescription['medicineId']     = DoctorMedicine::where('id',$prescription->medicine_id)->withTrashed()->first();
        }
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Prescriptions"]), $oPrescriptions, false);
        $this->urlComponents(config("businesslogic")[30]['menu'][0], $oResponse, config("businesslogic")[30]['title']);
        return $oResponse;

    }
    public function pictureIndex(Request $request)
    {
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        if (!Gate::allows('prescription-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        $oInput['url'] = isset($oInput['url'])?decrypt($oInput['url']):null;

        $oQb = Prescription::orderByDesc('updated_at')->whereNull('medicine_id')->whereNotNull('file_type')->with(['appointmentId','medicineId','patientId','doctorId','createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;
            
            $oQb = $oQb->whereHas('doctorId', function ($q) use($oInput) {
                $q->where('organization_id', $oInput['organization_id']);
            });
        }elseif ($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;    
        }elseif ($oAuth->isPatient()) {
            $oInput['patient_id'] = $oAuth->patient_id;    
        }
        
        $oQb = QB::whereLike($oInput,"file_type",$oQb);
        $oQb = QB::whereLike($oInput,"mime_type",$oQb);
        $oQb = QB::whereLike($oInput,"file_name",$oQb);
        $oQb = QB::whereLike($oInput,"url",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"patient_id",$oQb);
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::where($oInput,"medicine_id",$oQb);
        
        $oPrescriptions = $oQb->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Prescriptions"]), $oPrescriptions, false);
        $this->urlComponents(config("businesslogic")[30]['menu'][0], $oResponse, config("businesslogic")[30]['title']);
        return $oResponse;
    }
    public function store(Request $request){
        if(!(auth()->user()->isDoctor())){
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        }
        $oInput = $request->all();
        
        if (!Gate::allows('prescription-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oValidator = Validator::make($oInput,[
            'dose_quantity' => 'present|nullable|max:10',
            'type'          => 'required|in:tablet,capsule,syrup,drops,inhaler,injection,topical,patch,ointment,spray,sach,shampoo,vaccine,guaze,anticeptic,balm,vial,cream',
            'is_morning'    => 'required|in:0,1',
            'is_afternoon'  => 'required|in:0,1',
            'is_evening'    => 'required|in:0,1',
            'is_daily'      => 'required|in:0,1',
            'is_week'       => 'required|in:0,1',
            'is_once'       => 'required|in:0,1',
            'dose_remarks'  => 'present|nullable|max:100',
            'days_for'      => 'required|max:9',
            'medicine_id'   => 'present|nullable|exists:doctor_medicines,id',
            'medicine_name' => 'nullable',
            'appointment_id'=> 'required|exists:appointments,id',
            'doctor_id'     => 'required|exists:doctors,id',
            'patient_id'    => 'required|exists:patients,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oAppointment = Appointment::where('id',$oInput['appointment_id'])->where('patient_id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oAppointment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
        
        if(isset($oInput['medicine_name'])){
            $oDate = DoctorMedicine::where('medicine_name',$oInput['medicine_name'])->where('doctor_id',$oInput['doctor_id'])->first();
            if(!isset($oDate)){
                $oDoctorMedicine = DoctorMedicine::create([
                    'doctor_id'     => $oInput['doctor_id'],
                    'medicine_name' => $oInput['medicine_name'],
                    // 'description'   => $oInput['dose_remarks'],
                    'is_active'     => 1,
                    'type'          => $oInput['type'],
                    'created_by'    => Auth::user()->id,
                    'updated_by'    => Auth::user()->id,
                    'created_at'    => Carbon::now()->toDateTimeString(),
                    'updated_at'    => Carbon::now()->toDateTimeString(),
                ]);
                $oInput['medicine_id'] = $oDoctorMedicine->id;
            }else{
                $oInput['medicine_id'] = $oDate->id;
            }
        }
        $oPrescription = Prescription::create([
            'dose_quantity' => $oInput['dose_quantity'],
            'type'          => $oInput['type'], 
            'is_morning'    => $oInput['is_morning'], 
            'is_afternoon'  => $oInput['is_afternoon'], 
            'is_evening'    => $oInput['is_evening'], 
            'is_daily'      => $oInput['is_daily'],
            'is_week'       => $oInput['is_week'], 
            'is_once'       => $oInput['is_once'], 
            'dose_remarks'  => $oInput['dose_remarks'], 
            'days_for'      => $oInput['days_for'], 
            'medicine_id'   => $oInput['medicine_id'], 
            'appointment_id'=> $oInput['appointment_id'], 
            'doctor_id'     => $oInput['doctor_id'], 
            'patient_id'    => $oInput['patient_id'],
            'created_by'    => Auth::user()->id,
            'updated_by'    => Auth::user()->id,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        
        $oPrescription = Prescription::with(['appointmentId','medicineId','patientId','doctorId','createdBy','updatedBy','deletedBy'])->findOrFail($oPrescription->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Prescription"]), $oPrescription, false);
        $this->urlComponents(config("businesslogic")[30]['menu'][1], $oResponse, config("businesslogic")[30]['title']);
        return $oResponse;
    }
    public function show($id)
    {
        $oAuth = Auth::user();
        $oQb = Prescription::orderByDesc('updated_at')->with(['appointmentId','medicineId','patientId','doctorId','createdBy','updatedBy','deletedBy']);
        
        if($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;  
            $oQb = QB::where($oInput,"doctor_id",$oQb);  
        
        }elseif($oAuth->isPatient()) {
            $oInput['patient_id'] = $oAuth->patient_id;
            $oQb = QB::where($oInput,"patient_id",$oQb);
        }
        $oPrescription = $oQb->findOrFail($id);
        
        if (!Gate::allows('prescription-show',$oPrescription))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Prescription"]), $oPrescription, false);
        
        $this->urlComponents(config("businesslogic")[30]['menu'][2], $oResponse, config("businesslogic")[30]['title']);
        
        return $oResponse;
    }
    public function update(Request $request, $id)
    {
        if(!(auth()->user()->isDoctor())){
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        }
        $oInput = $request->all();

        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        $oValidator = Validator::make($oInput,[
            'dose_quantity' => 'present|nullable|max:10',
            'type'          => 'required|in:tablet,capsule,syrup,drops,inhaler,injection,topical,patch,ointment,spray,sach,shampoo,vaccine,guaze,anticeptic,balm,vial,cream',
            'is_morning'    => 'required|in:0,1',
            'is_afternoon'  => 'required|in:0,1',
            'is_evening'    => 'required|in:0,1',
            'is_daily'      => 'required|in:0,1',
            'is_week'       => 'required|in:0,1',
            'is_once'       => 'required|in:0,1',
            'dose_remarks'  => 'present|nullable|max:100',
            'days_for'      => 'required|max:9',
            'medicine_id'   => 'present|nullable|exists:doctor_medicines,id',
            'medicine_name' => 'nullable',
            'appointment_id'=> 'required|exists:appointments,id',
            'doctor_id'     => 'required|exists:doctors,id',
            'patient_id'    => 'required|exists:patients,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oAppointment = Appointment::where('id',$oInput['appointment_id'])->where('patient_id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oAppointment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
    
        $oPrescription = Prescription::findOrFail($id); 
        
        if (!Gate::allows('prescription-update',$oPrescription))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        if(isset($oInput['medicine_name'])){
            $oDate = DoctorMedicine::where('medicine_name',$oInput['medicine_name'])->where('doctor_id',$oInput['doctor_id'])->first();
            if(!isset($oDate)){
                $oDoctorMedicine = DoctorMedicine::create([
                    'doctor_id'     => $oInput['doctor_id'],
                    'medicine_name' => $oInput['medicine_name'],
                    // 'description'   => $oInput['dose_remarks'],
                    'is_active'     => 1,
                    'type'          => $oInput['type'],
                    'created_by'    => Auth::user()->id,
                    'updated_by'    => Auth::user()->id,
                    'created_at'    => Carbon::now()->toDateTimeString(),
                    'updated_at'    => Carbon::now()->toDateTimeString(),
                ]);
                $oInput['medicine_id'] = $oDoctorMedicine->id;
            }else{
                $oInput['medicine_id'] = $oDate->id;
            }
        }
        $oPrescriptionUpdate= $oPrescription->update([
            'dose_quantity' => $oInput['dose_quantity'],
            'type'          => $oInput['type'], 
            'is_morning'    => $oInput['is_morning'], 
            'is_afternoon'  => $oInput['is_afternoon'], 
            'is_evening'    => $oInput['is_evening'], 
            'is_daily'      => $oInput['is_daily'], 
            'is_week'       => $oInput['is_week'], 
            'is_once'       => $oInput['is_once'], 
            'dose_remarks'  => $oInput['dose_remarks'], 
            'days_for'      => $oInput['days_for'], 
            'medicine_id'   => $oInput['medicine_id'], 
            'appointment_id'=> $oInput['appointment_id'], 
            'doctor_id'     => $oInput['doctor_id'], 
            'patient_id'    => $oInput['patient_id'],
            'updated_by'    => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
            
        ]);
        $oPrescription = Prescription::with(['appointmentId','medicineId','patientId','doctorId','createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Prescription"]), $oPrescription, false);
        
        $this->urlComponents(config("businesslogic")[30]['menu'][3], $oResponse, config("businesslogic")[30]['title']);
        
        return $oResponse;
    }
    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:prescriptions,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allPrescription = Prescription::findOrFail($aIds);
        
        foreach($allPrescription as $oRow)
            if (!Gate::allows('prescription-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oPrescription = Prescription::find($id);
                if($oPrescription){
                    $oPrescription->update(['deleted_by' => Auth::user()->id]);
                    $oPrescription->delete();
                }
            }
        }else{
            $oPrescription = Prescription::findOrFail($aIds);
            $oPrescription->update(['deleted_by' => Auth::user()->id]);
            $oPrescription->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Prescription"]));
        $this->urlComponents(config("businesslogic")[30]['menu'][4], $oResponse, config("businesslogic")[30]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request)
    {
        $oInput = $request->all();
        
        if (!Gate::allows('prescription-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oAuth = Auth::user();
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oQb = Prescription::onlyTrashed()->orderByDesc('deleted_at')->with(['appointmentId','medicineId','patientId','doctorId','createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;            
            $oQb = $oQb->whereHas('doctorId', function ($q) use($oInput) {
                $q->where('organization_id', $oInput['organization_id']);
            });
        }elseif ($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;    
        }elseif ($oAuth->isPatient()) {
            $oInput['patient_id'] = $oAuth->patient_id;    
        }
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"dose_quantity",$oQb);
        $oQb = QB::whereLike($oInput,"dose_remarks",$oQb);
        $oQb = QB::where($oInput,"is_morning",$oQb);
        $oQb = QB::where($oInput,"is_afternoon",$oQb);
        $oQb = QB::where($oInput,"is_evening",$oQb);
        $oQb = QB::where($oInput,"is_week",$oQb);
        $oQb = QB::where($oInput,"is_once",$oQb);
        $oQb = QB::where($oInput,"days_for",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"patient_id",$oQb);
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::where($oInput,"medicine_id",$oQb);

        $oPrescription = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Prescriptions"]), $oPrescription, false);
        
        $this->urlComponents(config("businesslogic")[30]['menu'][5], $oResponse, config("businesslogic")[30]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:prescriptions,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allPrescriptions = Prescription::onlyTrashed()->findOrFail($aIds);
        
        foreach($allPrescriptions as $oRow)
            if (!Gate::allows('prescription-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oPrescription = Prescription::onlyTrashed()->find($id);
                if($oPrescription){
                    $oPrescription->restore();
                }
            }
        }else{
            $oPrescription = Prescription::onlyTrashed()->findOrFail($aIds);
            $oPrescription->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Prescription"]));
        
        $this->urlComponents(config("businesslogic")[30]['menu'][6], $oResponse, config("businesslogic")[30]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oPrescription = Prescription::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('prescription-delete',$oPrescription))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $bFileRemove = Storage::disk('s3')->delete(decrypt($oPrescription->url));
        
        $oPrescription->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Prescription"]));
        
        $this->urlComponents(config("businesslogic")[30]['menu'][7], $oResponse, config("businesslogic")[30]['title']);
        
        return $oResponse;
    }
    
    public function uploadPrescription(Request $request)
    {
        $oInput = $request->all();
        if(!(auth()->user()->isDoctor())){
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        }
        if (!Gate::allows('prescription-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;

        $oValidator = Validator::make($oInput,[
            'record' 		=> 'required',
            // 'record' 		=> 'required|file|mimes:jpeg,jpg,png,mp3,mp4,doc,docx,pdf',
    		'appointment_id'=> 'required|exists:appointments,id',
            'doctor_id'     => 'required|exists:doctors,id',
            'patient_id'    => 'required|exists:patients,id',
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
        $oAppointment = Appointment::where('id',$oInput['appointment_id'])->where('patient_id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oAppointment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);

        
        $oPrescriptions =[];
        if($request->hasFile('record')){
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
                    
                    $mPutFile = 'prescription/'.md5($oInput['patient_id']).'/'.md5($imageNameDate).'.'.$extension;
                    $image = Storage::disk('s3')->put($mPutFile,$img->encode());

                }else{
                    $mPutFile = Storage::disk('s3')->putFile('prescription/'.md5($oInput['patient_id']), $oFile);
                }

                // $mPutFile = Storage::disk('s3')->putFile('prescription/'.md5($oInput['patient_id']), $oFile);
                $oPrescription = Prescription::create([
                    'appointment_id'=> $oInput['appointment_id'], 
                    'doctor_id'     => $oInput['doctor_id'], 
                    'patient_id'    => $oInput['patient_id'],
                    "mime_type"     => $oFile->getClientMimeType(),
                    "file_type"     => $oFile->extension(),
                    "url"           => $mPutFile,
                    "file_name"     => substr($oFile->getClientOriginalName(), 0, 250),
                    'created_by'    => Auth::user()->id,
                    'updated_by'    => Auth::user()->id,
                    'created_at'    => Carbon::now()->toDateTimeString(),
                    'updated_at'    => Carbon::now()->toDateTimeString(),
                ]);
                $oPrescriptions[] = Prescription::with(['appointmentId','medicineId','patientId','doctorId','createdBy','updatedBy','deletedBy'])->findOrFail($oPrescription->id);
            }
            $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Prescription"]), $oPrescriptions, false);
            $this->urlComponents(config("businesslogic")[30]['menu'][8], $oResponse, config("businesslogic")[30]['title']);
            return $oResponse;
        }else{
            $oResponse = responseBuilder()->error(__('There is an issue with file'), 403, false);
            return $oResponse;
        }
        $oResponse = responseBuilder()->error(__('There is an issue with file'), 403, false);
        return $oResponse;
        
    }
    public function showUploadedPrescription(Request $request, $url)
    {
    	
    	$sFileUrl = decrypt($url);

    	$oPrescription = Prescription::where('url', $sFileUrl)->first();
        
        if(!isset($oPrescription))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
        
        if (!Gate::allows('prescription-show',$oPrescription))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
    	$iPatientId = $oPrescription->patient_id;
    	$iDoctorId = $oPrescription->doctor_id;
        
        if(auth()->user()->isPatient() && $iPatientId != auth()->user()->patient_id)
    		return responseBuilder()->error(__('message.EMR.noaccess'), 401, false); 


    	if(auth()->user()->isDoctor() && $iDoctorId != auth()->user()->doctor_id)
    		return responseBuilder()->error(__('message.EMR.noaccess'), 401, false); 

        // $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Prescriptions"]), $oPrescription, false);    	
        // $this->urlRec(30, 9, $oResponse);
        // return response(Storage::disk('s3')->get($sFileUrl))->header('Content-Type', $oPrescription->mime_type);
        $file = Storage::disk('s3')->get($sFileUrl);
        $data['image'] = base64_encode($file);
        $data['file_type'] = $oPrescription->file_type;
        $data['mime_type'] = $oPrescription->mime_type;
        $data['file_name'] = $oPrescription->file_name;
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Prescriptions"]), $data, false);
        return $oResponse;
    }
    public function allPrescription(Request $request)
    {
        $oInput = $request->all();
        $oAuth = Auth::user();
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        $oInput['url'] = isset($oInput['url'])?decrypt($oInput['url']):null;

        $oQb = Prescription::orderByDesc('updated_at')->with(['appointmentId','medicineId','patientId','doctorId','createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;
            
            $oQb = $oQb->whereHas('doctorId', function ($q) use($oInput) {
                $q->where('organization_id', $oInput['organization_id']);
            });
        }elseif ($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;    
        }elseif ($oAuth->isPatient()) {
            $oInput['patient_id'] = $oAuth->patient_id;    
        }
        $oQb = $oQb->whereHas('appointmentId', function ($q) use($oInput) {
            $q->whereIn('status', Appointment::$APPROVEDSTATUS);
        });
        if(!isset($oInput['appointment_id']) || (!isset($oInput['doctor_id']) && !isset($oInput['patient_id']))){
            $oQb = $oQb->groupBy('appointment_id');
        }
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"dose_quantity",$oQb);
        $oQb = QB::whereLike($oInput,"dose_remarks",$oQb);
        $oQb = QB::where($oInput,"is_morning",$oQb);
        $oQb = QB::where($oInput,"is_afternoon",$oQb);
        $oQb = QB::where($oInput,"is_evening",$oQb);
        $oQb = QB::where($oInput,"is_week",$oQb);
        $oQb = QB::where($oInput,"is_once",$oQb);
        $oQb = QB::where($oInput,"days_for",$oQb);
        $oQb = QB::whereLike($oInput,"file_type",$oQb);
        $oQb = QB::whereLike($oInput,"mime_type",$oQb);
        $oQb = QB::whereLike($oInput,"file_name",$oQb);
        $oQb = QB::whereLike($oInput,"url",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"patient_id",$oQb);
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        $oQb = QB::where($oInput,"medicine_id",$oQb);
        
        $oPrescriptions = $oQb->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Prescriptions"]), $oPrescriptions, false);
        return $oResponse;

    } 
}
