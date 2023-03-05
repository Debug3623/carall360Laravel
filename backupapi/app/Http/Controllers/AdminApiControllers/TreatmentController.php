<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;

use App\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Helpers\QB;
use App\User;
use Illuminate\Support\Facades\Gate;
class TreatmentController extends Controller
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
         
         if (!Gate::allows('treatment-index'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);

         $oQb = Treatment::orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy','parentId']);
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         $oQb = QB::whereLike($oInput,"description",$oQb);
         $oQb = QB::where($oInput,"is_active",$oQb);
         $oQb = QB::where($oInput,"parent_id",$oQb);
         
         $oTreatment = $oQb->paginate(10);
        
         $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Treatments"]), $oTreatment, false);
         $this->urlComponents(config("businesslogic")[15]['menu'][0], $oResponse, config("businesslogic")[15]['title']);
         
         return $oResponse;
     }
 
     public function store(Request $request)
     {
         if (!Gate::allows('treatment-store'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oInput = $request->all();
 
         $oValidator = Validator::make($oInput,[
            'name'          => 'required|string|max:50|min:3|unique:treatments,name',
            'description'   => 'present|nullable|string',
            'is_active'     => 'required|in:0,1',
            'parent_id'     => 'present|nullable|exists:treatments,id',
        ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         
         $oTreatment = Treatment::create([
             'name'         => $oInput['name'],
             'description'  => $oInput['description'],
             'is_active'    => $oInput['is_active'],
             'parent_id'    => $oInput['parent_id'],
             'created_by'   => Auth::user()->id,
             'updated_by'   => Auth::user()->id,
             'created_at'   => Carbon::now()->toDateTimeString(),
             'updated_at'   => Carbon::now()->toDateTimeString(),
         ]);
         $oTreatment= Treatment::with(['createdBy','updatedBy','deletedBy','parentId'])->findOrFail($oTreatment->id);
         
         $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Treatment"]), $oTreatment, false);
         
         $this->urlComponents(config("businesslogic")[15]['menu'][1], $oResponse, config("businesslogic")[15]['title']);
         
         return $oResponse;
     }
 
     /**
      * Display the specified resource.
      *
      * @param  \App\Treatment  $Treatment
      * @return \Illuminate\Http\Response
      */
     public function show($Treatment_id)
     {
         $oTreatment = Treatment::with(['createdBy','updatedBy','deletedBy','parentId'])->findOrFail($Treatment_id);
         
         if (!Gate::allows('treatment-show',$oTreatment))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Treatment"]), $oTreatment, false);
         
         $this->urlComponents(config("businesslogic")[15]['menu'][2], $oResponse, config("businesslogic")[15]['title']);
         
         return $oResponse;
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\Treatment  $Treatment
      * @return \Illuminate\Http\Response
      */
     public function edit(Treatment $Treatment)
     {
         //
     }
 
     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \App\Treatment  $Treatment
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, $id)
     {
         $oInput = $request->all();
 
         $oValidator = Validator::make($oInput,[
            'name'          => 'required|string|max:50|min:3|unique:treatments,name,'.$id,
            'description'   => 'present|nullable|string',
            'is_active'     => 'required|in:0,1',
            'parent_id'     => 'present|nullable|exists:treatments,id',
        ]);

 
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
 
         $oTreatment = Treatment::with(['createdBy','updatedBy','deletedBy','parentId'])->findOrFail($id);
         
         if (!Gate::allows('treatment-update',$oTreatment))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oTreatment = $oTreatment->update([
            'name'         => $oInput['name'],
            'description'  => $oInput['description'],
            'is_active'    => $oInput['is_active'],
            'parent_id'    => $oInput['parent_id'],
             'updated_by'  => Auth::user()->id,
             'updated_at'  => Carbon::now()->toDateTimeString(),
         ]);
         $oTreatment = Treatment::with(['createdBy','updatedBy','deletedBy','parentId'])->findOrFail($id);
         
         $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Treatment"]), $oTreatment, false);
         
         $this->urlComponents(config("businesslogic")[15]['menu'][3], $oResponse, config("businesslogic")[15]['title']);
         
         return $oResponse;
     }
 
     // Soft Delete Doctors 
 
     public function destroy(Request $request)
     {
         $oInput = $request->all();
         $oValidator = Validator::make($oInput,[
             'ids' => 'required|array',
             'ids.*' => 'exists:treatments,id',
         ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         
         $aIds = $request->ids;
 
         $allTreatment = Treatment::findOrFail($aIds);
         
         foreach($allTreatment as $oRow)
             if (!Gate::allows('treatment-destroy',$oRow))
                 return responseBuilder()->error(__('auth.not_authorized'), 403, false);
         
         if(is_array($aIds)){
             foreach($aIds as $id){
                 $oTreatment = Treatment::find($id);
                 if($oTreatment){
                     $oTreatment->update(['deleted_by' => Auth::user()->id]);
                     $oTreatment->delete();
                 }
             }
         }else{
             $oTreatment = Treatment::findOrFail($aIds);
         
             $oTreatment->update(['deleted_by' => Auth::user()->id]);
             $oTreatment->delete();
         }
         $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Treatment"]));
         $this->urlComponents(config("businesslogic")[15]['menu'][4], $oResponse, config("businesslogic")[15]['title']);
         
         return $oResponse;
     }
     
     // Get soft deleted data
     public function deleted(Request $request)
     {
         if (!Gate::allows('treatment-deleted'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
 
         $oInput = $request->all();
         
         $oQb = Treatment::onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy','parentId']);
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         $oQb = QB::whereLike($oInput,"description",$oQb);
         $oQb = QB::where($oInput,"is_active",$oQb);
         $oQb = QB::where($oInput,"parent_id",$oQb);
 
         $oTreatment = $oQb->paginate(10);
         
         $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Treatment"]), $oTreatment, false);
         
         $this->urlComponents(config("businesslogic")[15]['menu'][5], $oResponse, config("businesslogic")[15]['title']);
         
         return $oResponse;
     }
     // Restore any deleted data
     public function restore(Request $request)
     {  
         $oInput = $request->all();
         $oValidator = Validator::make($oInput,[
             'ids' => 'required|array',
             'ids.*' => 'exists:treatments,id',
         ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         $aIds = $request->ids;
         
         $allTreatment= Treatment::onlyTrashed()->findOrFail($aIds);
         
         foreach($allTreatment as $oRow)
             if (!Gate::allows('treatment-restore',$oRow))
                 return responseBuilder()->error(__('auth.not_authorized'), 403, false);
         
         if(is_array($aIds)){
             foreach($aIds as $id){
                 
                 $oTreatment = Treatment::onlyTrashed()->find($id);
                 if($oTreatment){
                     $oTreatment->restore();
                 }
             }
         }else{
             $oTreatment = Treatment::onlyTrashed()->findOrFail($aIds);
             $oTreatment->restore();
         }
         
 
         $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Treatment"]));
         
         $this->urlComponents(config("businesslogic")[15]['menu'][6], $oResponse, config("businesslogic")[15]['title']);
         
         return $oResponse;
     }
     // Permanent Delete
     public function delete($id)
     {
         $oTreatment = Treatment::onlyTrashed()->findOrFail($id);
         
         if (!Gate::allows('treatment-delete',$oTreatment))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oTreatment->forceDelete();
         
         $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Treatment"]));
         
         $this->urlComponents(config("businesslogic")[15]['menu'][7], $oResponse, config("businesslogic")[15]['title']);
         
         return $oResponse;
     }
     public function parentTreatments(Request $request)
    {
         $oInput = $request->all();
         
         if (!Gate::allows('parent-treatment-index'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);

         $oQb = Treatment::whereNull('parent_id')->where('is_active',1)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy','parentId']);
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         $oQb = QB::whereLike($oInput,"description",$oQb);
         $oQb = QB::where($oInput,"is_active",$oQb);
         
         $oTreatment = $oQb->get();
        
         $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Parent Treatments"]), $oTreatment, false);
         $this->urlComponents(config("businesslogic")[15]['menu'][8], $oResponse, config("businesslogic")[15]['title']);
         
         return $oResponse;
    } 
}
