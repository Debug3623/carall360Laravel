<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;

use App\DoctorSpecialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Helpers\QB;
use Illuminate\Support\Facades\Gate;
class DoctorSpecialtyController extends Controller
{
     use \App\Traits\WebServicesDoc;
     /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
         $oInput = $request->all();
         
         if (!Gate::allows('doctor-specialty-index'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);

         $oQb = DoctorSpecialty::orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         $oQb = QB::whereLike($oInput,"description",$oQb);
         $oQb = QB::where($oInput,"is_active",$oQb);
         
         $oDoctorSpecialty = $oQb->paginate(10);
        
         $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Doctor Specialty"]), $oDoctorSpecialty, false);
         $this->urlComponents(config("businesslogic")[35]['menu'][0], $oResponse, config("businesslogic")[35]['title']);
         
         return $oResponse;
     }
 
     public function store(Request $request)
     {
         if (!Gate::allows('doctor-specialty-store'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oInput = $request->all();
 
         $oValidator = Validator::make($oInput,[
            'name'          => 'required|string|max:100|min:1|unique:doctor_specialties,name',
            'description'   => 'present|nullable',
            'is_active'     => 'required|in:0,1',
        ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         
         $oDoctorSpecialty = DoctorSpecialty::create([
             'name'         => $oInput['name'],
             'description'  => $oInput['description'],
             'is_active'    => $oInput['is_active'],
             'created_by'   => Auth::user()->id,
             'updated_by'   => Auth::user()->id,
             'created_at'   => Carbon::now()->toDateTimeString(),
             'updated_at'   => Carbon::now()->toDateTimeString(),
         ]);
         
         $oDoctorSpecialty= DoctorSpecialty::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oDoctorSpecialty->id);
         
         $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Doctor Specialty"]), $oDoctorSpecialty, false);
         
         $this->urlComponents(config("businesslogic")[35]['menu'][1], $oResponse, config("businesslogic")[35]['title']);
         
         return $oResponse;
     }
 
     /**
      * Display the specified resource.
      *
      * @param  \App\Doctor Specialty  $Doctor Specialty
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         $oDoctorSpecialty = DoctorSpecialty::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
         
         if (!Gate::allows('doctor-specialty-show',$oDoctorSpecialty))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Doctor Specialty"]), $oDoctorSpecialty, false);
         
         $this->urlComponents(config("businesslogic")[35]['menu'][2], $oResponse, config("businesslogic")[35]['title']);
         
         return $oResponse;
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\Doctor Specialty  $Doctor Specialty
      * @return \Illuminate\Http\Response
      */
     public function edit(DoctorSpecialty $DoctorSpecialty)
     {
         //
     }
 
     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \App\Doctor Specialty  $Doctor Specialty
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, $id)
     {
         $oInput = $request->all();
 
         $oValidator = Validator::make($oInput,[
            'name'          => 'required|string|max:100|min:3|unique:doctor_specialties,name,'.$id,
            'description'   => 'present|nullable',
            'is_active'     => 'required|in:0,1',
        ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
 
         $oDoctorSpecialty = DoctorSpecialty::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
         
         if (!Gate::allows('doctor-specialty-update',$oDoctorSpecialty))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oDoctorSpecialty = $oDoctorSpecialty->update([
            'name'         => $oInput['name'],
            'description'  => $oInput['description'],
            'is_active'    => $oInput['is_active'],
            'updated_by'   => Auth::user()->id,
            'updated_at'   => Carbon::now()->toDateTimeString(),
         ]);
         $oDoctorSpecialty = DoctorSpecialty::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
         
         $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Doctor Specialty"]), $oDoctorSpecialty, false);
         
         $this->urlComponents(config("businesslogic")[35]['menu'][3], $oResponse, config("businesslogic")[35]['title']);
         
         return $oResponse;
     }
 
     // Soft Delete Doctors 
 
     public function destroy(Request $request)
     {
         $oInput = $request->all();
         $oValidator = Validator::make($oInput,[
             'ids' => 'required|array',
             'ids.*' => 'exists:doctor_specialties,id',
         ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         
         $aIds = $request->ids;
 
         $allDoctorSpecialty = DoctorSpecialty::findOrFail($aIds);
         
         foreach($allDoctorSpecialty as $oRow)
             if (!Gate::allows('doctor-specialty-destroy',$oRow))
                 return responseBuilder()->error(__('auth.not_authorized'), 403, false);
         
         if(is_array($aIds)){
             foreach($aIds as $id){
                 $oDoctorSpecialty = DoctorSpecialty::find($id);
                 if($oDoctorSpecialty){
                     $oDoctorSpecialty->update(['deleted_by' => Auth::user()->id]);
                     $oDoctorSpecialty->delete();
                 }
             }
         }else{
             $oDoctorSpecialty = DoctorSpecialty::findOrFail($aIds);
         
             $oDoctorSpecialty->update(['deleted_by' => Auth::user()->id]);
             $oDoctorSpecialty->delete();
         }
         $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Doctor Specialty"]));
         $this->urlComponents(config("businesslogic")[35]['menu'][4], $oResponse, config("businesslogic")[35]['title']);
         
         return $oResponse;
     }
     
     // Get soft deleted data
     public function deleted(Request $request)
     {
         if (!Gate::allows('doctor-specialty-deleted'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
 
         $oInput = $request->all();
         
         $oQb = DoctorSpecialty::onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         $oQb = QB::whereLike($oInput,"description",$oQb);
         $oQb = QB::where($oInput,"is_active",$oQb);
 
         $oDoctorSpecialty = $oQb->paginate(10);
         
         $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Doctor Specialty"]), $oDoctorSpecialty, false);
         
         $this->urlComponents(config("businesslogic")[35]['menu'][5], $oResponse, config("businesslogic")[35]['title']);
         
         return $oResponse;
     }
     // Restore any deleted data
     public function restore(Request $request)
     {  
         $oInput = $request->all();
         $oValidator = Validator::make($oInput,[
             'ids' => 'required|array',
             'ids.*' => 'exists:doctor_specialties,id',
         ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         $aIds = $request->ids;
         
         $allDoctorSpecialty= DoctorSpecialty::onlyTrashed()->findOrFail($aIds);
         
         foreach($allDoctorSpecialty as $oRow)
             if (!Gate::allows('doctor-specialty-restore',$oRow))
                 return responseBuilder()->error(__('auth.not_authorized'), 403, false);
         
         if(is_array($aIds)){
             foreach($aIds as $id){
                 
                 $oDoctorSpecialty = DoctorSpecialty::onlyTrashed()->find($id);
                 if($oDoctorSpecialty){
                     $oDoctorSpecialty->restore();
                 }
             }
         }else{
             $oDoctorSpecialty = DoctorSpecialty::onlyTrashed()->findOrFail($aIds);
             $oDoctorSpecialty->restore();
         }
         
 
         $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Doctor Specialty"]));
         
         $this->urlComponents(config("businesslogic")[35]['menu'][6], $oResponse, config("businesslogic")[35]['title']);
         
         return $oResponse;
     }
     // Permanent Delete
     public function delete($id)
     {
         $oDoctorSpecialty = DoctorSpecialty::onlyTrashed()->findOrFail($id);
         
         if (!Gate::allows('doctor-specialty-delete',$oDoctorSpecialty))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oDoctorSpecialty->forceDelete();
         
         $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Doctor Specialty"]));
         
         $this->urlComponents(config("businesslogic")[35]['menu'][7], $oResponse, config("businesslogic")[35]['title']);
         
         return $oResponse;
     }
     public function allDoctorSpecialty(Request $request)
    {
        $oInput = $request->all();
        
        $oQb = DoctorSpecialty::where('is_active',1)->orderByDesc('updated_at');
        $oQb = QB::whereLike($oInput,"name",$oQb);
        
        $oDoctorSpecialty = $oQb->select('id','name')->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Doctor Specialty"]), $oDoctorSpecialty, false);
        $this->urlComponents(config("businesslogic")[35]['menu'][8], $oResponse, config("businesslogic")[35]['title']);
        
        return $oResponse;
    }
}
