<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;

use App\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Helpers\QB;
use App\User;
use Illuminate\Support\Facades\Gate;
class MedicineController extends Controller
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
         
         if (!Gate::allows('medicine-index'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);

         $oQb = Medicine::orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         $oQb = QB::whereLike($oInput,"description",$oQb);
         $oQb = QB::where($oInput,"is_active",$oQb);
         $oQb = QB::whereLike($oInput,"type",$oQb);
         
         $oMedicine = $oQb->paginate(10);
        
         $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Medicines"]), $oMedicine, false);
         $this->urlComponents(config("businesslogic")[17]['menu'][0], $oResponse, config("businesslogic")[17]['title']);
         
         return $oResponse;
     }
 
     public function store(Request $request)
     {
         if (!Gate::allows('medicine-store'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oInput = $request->all();
 
         $oValidator = Validator::make($oInput,[
            'name'          => 'required|string|max:50|min:3|unique:medicines,name',
            'description'   => 'present|nullable|string',
            'is_active'     => 'required|in:0,1',
            'type'          => 'required|in:tablet,capsule,syrup,drops,inhaler,injection,topical,patch,ointment,spray,sach,shampoo,vaccine,guaze,anticeptic,balm,vial,cream',
        ]);
        
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         
         $oMedicine = Medicine::create([
             'name'         => $oInput['name'],
             'description'  => $oInput['description'],
             'is_active'    => $oInput['is_active'],
             'type'         => $oInput['type'],
             'created_by'   => Auth::user()->id,
             'updated_by'   => Auth::user()->id,
             'created_at'   => Carbon::now()->toDateTimeString(),
             'updated_at'   => Carbon::now()->toDateTimeString(),
         ]);
         $oMedicine= Medicine::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oMedicine->id);
         
         $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Medicine"]), $oMedicine, false);
         
         $this->urlComponents(config("businesslogic")[17]['menu'][1], $oResponse, config("businesslogic")[17]['title']);
         
         return $oResponse;
     }
 
     /**
      * Display the specified resource.
      *
      * @param  \App\Medicine  $Medicine
      * @return \Illuminate\Http\Response
      */
     public function show($Medicine_id)
     {
         $oMedicine = Medicine::with(['createdBy','updatedBy','deletedBy'])->findOrFail($Medicine_id);
         
         if (!Gate::allows('medicine-show',$oMedicine))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Medicine"]), $oMedicine, false);
         
         $this->urlComponents(config("businesslogic")[17]['menu'][2], $oResponse, config("businesslogic")[17]['title']);
         
         return $oResponse;
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\Medicine  $Medicine
      * @return \Illuminate\Http\Response
      */
     public function edit(Medicine $Medicine)
     {
         //
     }
 
     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \App\Medicine  $Medicine
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, $id)
     {
         $oInput = $request->all();
 
         $oValidator = Validator::make($oInput,[
            'name'          => 'required|string|max:50|min:3|unique:medicines,name,'.$id,
            'description'   => 'present|nullable|string',
            'is_active'     => 'required|in:0,1',
            'type'          => 'required|in:tablet,capsule,syrup,drops,inhaler,injection,topical,patch,ointment,spray,sach,shampoo,vaccine,guaze,anticeptic,balm,vial,cream',
        ]);

 
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
 
         $oMedicine = Medicine::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
         
         if (!Gate::allows('medicine-update',$oMedicine))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oMedicine = $oMedicine->update([
            'name'         => $oInput['name'],
            'description'  => $oInput['description'],
            'is_active'    => $oInput['is_active'],
            'type'         => $oInput['type'],
            'updated_by'  => Auth::user()->id,
            'updated_at'  => Carbon::now()->toDateTimeString(),
         ]);
         $oMedicine = Medicine::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
         
         $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Medicine"]), $oMedicine, false);
         
         $this->urlComponents(config("businesslogic")[17]['menu'][3], $oResponse, config("businesslogic")[17]['title']);
         
         return $oResponse;
     }
 
     // Soft Delete Doctors 
 
     public function destroy(Request $request)
     {
         $oInput = $request->all();
         $oValidator = Validator::make($oInput,[
             'ids' => 'required|array',
             'ids.*' => 'exists:medicines,id',
         ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         
         $aIds = $request->ids;
 
         $allMedicine = Medicine::findOrFail($aIds);
         
         foreach($allMedicine as $oRow)
             if (!Gate::allows('medicine-destroy',$oRow))
                 return responseBuilder()->error(__('auth.not_authorized'), 403, false);
         
         if(is_array($aIds)){
             foreach($aIds as $id){
                 $oMedicine = Medicine::find($id);
                 if($oMedicine){
                     $oMedicine->update(['deleted_by' => Auth::user()->id]);
                     $oMedicine->delete();
                 }
             }
         }else{
             $oMedicine = Medicine::findOrFail($aIds);
         
             $oMedicine->update(['deleted_by' => Auth::user()->id]);
             $oMedicine->delete();
         }
         $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Medicine"]));
         $this->urlComponents(config("businesslogic")[17]['menu'][4], $oResponse, config("businesslogic")[17]['title']);
         
         return $oResponse;
     }
     
     // Get soft deleted data
     public function deleted(Request $request)
     {
         if (!Gate::allows('medicine-deleted'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
 
         $oInput = $request->all();
         
         $oQb = Medicine::onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         $oQb = QB::whereLike($oInput,"description",$oQb);
         $oQb = QB::where($oInput,"is_active",$oQb);
         $oQb = QB::whereLike($oInput,"type",$oQb);
 
         $oMedicine = $oQb->paginate(10);
         
         $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Medicine"]), $oMedicine, false);
         
         $this->urlComponents(config("businesslogic")[17]['menu'][5], $oResponse, config("businesslogic")[17]['title']);
         
         return $oResponse;
     }
     // Restore any deleted data
     public function restore(Request $request)
     {  
         $oInput = $request->all();
         $oValidator = Validator::make($oInput,[
             'ids' => 'required|array',
             'ids.*' => 'exists:medicines,id',
         ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         $aIds = $request->ids;
         
         $allMedicine= Medicine::onlyTrashed()->findOrFail($aIds);
         
         foreach($allMedicine as $oRow)
             if (!Gate::allows('medicine-restore',$oRow))
                 return responseBuilder()->error(__('auth.not_authorized'), 403, false);
         
         if(is_array($aIds)){
             foreach($aIds as $id){
                 
                 $oMedicine = Medicine::onlyTrashed()->find($id);
                 if($oMedicine){
                     $oMedicine->restore();
                 }
             }
         }else{
             $oMedicine = Medicine::onlyTrashed()->findOrFail($aIds);
             $oMedicine->restore();
         }
         
 
         $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Medicine"]));
         
         $this->urlComponents(config("businesslogic")[17]['menu'][6], $oResponse, config("businesslogic")[17]['title']);
         
         return $oResponse;
     }
     // Permanent Delete
     public function delete($id)
     {
         $oMedicine = Medicine::onlyTrashed()->findOrFail($id);
         
         if (!Gate::allows('medicine-delete',$oMedicine))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oMedicine->forceDelete();
         
         $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Medicine"]));
         
         $this->urlComponents(config("businesslogic")[17]['menu'][7], $oResponse, config("businesslogic")[17]['title']);
         
         return $oResponse;
     }
}
