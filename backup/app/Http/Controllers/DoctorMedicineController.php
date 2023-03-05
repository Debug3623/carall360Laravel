<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\DoctorMedicine;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\Medicine;
use App\Prescription;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DoctorMedicineController extends Controller
{
    use \App\Traits\WebServicesDoc;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request, $doctor_id)
    {
        $oInput = $request->all();
        $doctor_id = decrypt($doctor_id);
        if (!Gate::allows('doctor-medicine-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorMedicine::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"medicine_name",$oQb);
        $oQb = QB::where($oInput,"medicine_id",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);
        
        $oMedicines = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Docotr Medicines"]), $oMedicines, false);
        $this->urlComponents(config("businesslogic")[16]['menu'][0], $oResponse, config("businesslogic")[16]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('doctor-medicine-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'medicine_name' => 'required|string|max:50|min:3',
            'description'   => 'present|nullable|string',
            'is_active'     => 'required|in:0,1',
            'type'          => 'required|in:tablet,capsule,syrup,drops,inhaler,injection,topical,patch,ointment,spray,sach,shampoo,vaccine,guaze,anticeptic,balm,vial,cream', 
            'medicine_id'   => 'present|nullable|exists:medicines,id',
            'doctor_id'     => 'required|exists:doctors,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oDate = DoctorMedicine::where('doctor_id',$oInput['doctor_id'])->where('medicine_name',$oInput['medicine_name'])->first();
        
        if($oDate){
            abort(400,__('Medicine name already entered'));
        }
        
        $oDoctorMedicine = DoctorMedicine::create([
            'doctor_id'     => $oInput['doctor_id'],
            'medicine_name' => $oInput['medicine_name'],
            'description'   => $oInput['description'],
            'is_active'     => $oInput['is_active'],
            'type'          => $oInput['type'],
            'medicine_id'   => $oInput['medicine_id'],
            'created_by'    => Auth::user()->id,
            'updated_by'    => Auth::user()->id,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorMedicine= DoctorMedicine::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oDoctorMedicine->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Doctor Medicine"]), $oDoctorMedicine, false);
        
        $this->urlComponents(config("businesslogic")[16]['menu'][1], $oResponse, config("businesslogic")[16]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DoctorMedicine  $DoctorMedicine
     * @return \Illuminate\Http\Response
     */
    public function show($medicine_id)
    {
        $oMedicine = DoctorMedicine::with(['createdBy','updatedBy','deletedBy'])->findOrFail($medicine_id);
        
        if (!Gate::allows('doctor-medicine-show',$oMedicine))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Doctor Medicine"]), $oMedicine, false);
        
        $this->urlComponents(config("businesslogic")[16]['menu'][2], $oResponse, config("businesslogic")[16]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DoctorMedicine  $DoctorMedicine
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorMedicine $DoctorMedicine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DoctorMedicine  $DoctorMedicine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'medicine_name' => 'required|string|max:50|min:3',
            'description'   => 'present|nullable|string',
            'is_active'     => 'required|in:0,1',
            'type'          => 'required|in:tablet,capsule,syrup,drops,inhaler,injection,topical,patch,ointment,spray,sach,shampoo,vaccine,guaze,anticeptic,balm,vial,cream', 
            'medicine_id'   => 'present|nullable|exists:medicines,id',
            'doctor_id'     => 'required|exists:doctors,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oDate = DoctorMedicine::where('doctor_id',$oInput['doctor_id'])->where('id','!=',$id)->where('medicine_name',$oInput['medicine_name'])->first();
        if($oDate){
            abort(400,__('Medicine name already entered'));
        }
        $oDoctorMedicine = DoctorMedicine::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('doctor-medicine-update',$oDoctorMedicine))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorMedicine = $oDoctorMedicine->update([
            'doctor_id'     => $oInput['doctor_id'],
            'medicine_name' => $oInput['medicine_name'],
            'description'   => $oInput['description'],
            'is_active'     => $oInput['is_active'],
            'medicine_id'   => $oInput['medicine_id'],
            'type'          => $oInput['type'],
            'updated_by'    => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorMedicine = DoctorMedicine::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Doctor Medicine"]), $oDoctorMedicine, false);
        
        $this->urlComponents(config("businesslogic")[16]['menu'][3], $oResponse, config("businesslogic")[16]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_medicines,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allDoctorMedicine = DoctorMedicine::findOrFail($aIds);
        
        foreach($allDoctorMedicine as $oRow)
            if (!Gate::allows('doctor-medicine-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctorMedicine = DoctorMedicine::find($id);
                $oPrescription = Prescription::where('medicine_id',$id)->first();
                if(isset($oPrescription)){
                   abort(400,'You are not allow to delete that medicine. Medicine is used in Prescription'); 
                }
                if($oDoctorMedicine){
                    $oDoctorMedicine->update(['deleted_by' => Auth::user()->id]);
                    $oDoctorMedicine->delete();
                }
            }
        }else{
            $oDoctorMedicine = DoctorMedicine::findOrFail($aIds);
        
            $oDoctorMedicine->update(['deleted_by' => Auth::user()->id]);
            $oDoctorMedicine->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Doctor Medicine"]));
        $this->urlComponents(config("businesslogic")[16]['menu'][4], $oResponse, config("businesslogic")[16]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$doctor_id)
    {
        $doctor_id = decrypt($doctor_id);
        if (!Gate::allows('doctor-medicine-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        $oInput = $request->all();
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = DoctorMedicine::where('doctor_id',$doctor_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"medicine_name",$oQb);
        $oQb = QB::where($oInput,"medicine_id",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::whereLike($oInput,"type",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);

        $oMedicine = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Doctor Medicine"]), $oMedicine, false);
        
        $this->urlComponents(config("businesslogic")[16]['menu'][5], $oResponse, config("businesslogic")[16]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_medicines,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allDoctorMedicine= DoctorMedicine::onlyTrashed()->findOrFail($aIds);
        
        foreach($allDoctorMedicine as $oRow)
            if (!Gate::allows('doctor-medicine-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oDoctorMedicine = DoctorMedicine::onlyTrashed()->find($id);
                if($oDoctorMedicine){
                    $oDoctorMedicine->restore();
                }
            }
        }else{
            $oDoctorMedicine = DoctorMedicine::onlyTrashed()->findOrFail($aIds);
            $oDoctorMedicine->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Doctor Medicine"]));
        
        $this->urlComponents(config("businesslogic")[16]['menu'][6], $oResponse, config("businesslogic")[16]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oDoctorMedicine = DoctorMedicine::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('doctor-medicine-delete',$oDoctorMedicine))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorMedicine->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"medicine"]));
        
        $this->urlComponents(config("businesslogic")[16]['menu'][7], $oResponse, config("businesslogic")[16]['title']);
        
        return $oResponse;
    }
    public function allMedicines(Request $request)
     {
         $oInput = $request->all();

         $oQb = Medicine::where('is_active',1)->orderByDesc('updated_at');
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         $oQb = QB::whereLike($oInput,"type",$oQb);
         
         $oMedicine = $oQb->get();
        
         $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"All Medicines"]), $oMedicine, false);
         $this->urlComponents(config("businesslogic")[16]['menu'][8], $oResponse, config("businesslogic")[16]['title']);
         
         return $oResponse;
     }
     public function allDoctorMedicines(Request $request, $doctor_id)
    {
        $doctor_id = decrypt($doctor_id);

        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oMedicines = DoctorMedicine::where('doctor_id',$doctor_id)->where('is_active',1)->orderByDesc('updated_at')->select('id','medicine_name','description','type')->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Doctor All Medicine"]), $oMedicines, false);
        $this->urlComponents(config("businesslogic")[16]['menu'][9], $oResponse, config("businesslogic")[16]['title']);
        
        return $oResponse;
    }
}
