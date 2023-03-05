<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;

use App\Diagnostic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Helpers\QB;
use Illuminate\Support\Facades\Gate;
class DiagnosticController extends Controller
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
         
         if (!Gate::allows('diagnostic-index'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);

         $oQb = Diagnostic::orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         $oQb = QB::whereLike($oInput,"type",$oQb);
         $oQb = QB::whereLike($oInput,"description",$oQb);
         $oQb = QB::where($oInput,"is_active",$oQb);
         
         $oDiagnostic = $oQb->paginate(10);
        
         $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Diagnostics"]), $oDiagnostic, false);
         $this->urlComponents(config("businesslogic")[24]['menu'][0], $oResponse, config("businesslogic")[24]['title']);
         
         return $oResponse;
     }
 
     public function store(Request $request)
     {
         if (!Gate::allows('diagnostic-store'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oInput = $request->all();
 
         $oValidator = Validator::make($oInput,[
            'name'          => 'required|string|max:50|min:1|unique:diagnostics,name',
            'description'   => 'present|nullable',
            'is_active'     => 'required|in:0,1',
            'type'          => 'nullable|max:255',
            'preinstruction'=> 'present|nullable|max:150',
        ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         
         $oDiagnostic = Diagnostic::create([
             'name'         => $oInput['name'],
             'description'  => $oInput['description'],
             'is_active'    => $oInput['is_active'],
             'type'         => isset($oInput['type']) ? $oInput['type'] : '',
             'preinstruction'=> $oInput['preinstruction'],
             'created_by'   => Auth::user()->id,
             'updated_by'   => Auth::user()->id,
             'created_at'   => Carbon::now()->toDateTimeString(),
             'updated_at'   => Carbon::now()->toDateTimeString(),
         ]);
         $oDiagnostic= Diagnostic::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oDiagnostic->id);
         
         $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Diagnostic"]), $oDiagnostic, false);
         
         $this->urlComponents(config("businesslogic")[24]['menu'][1], $oResponse, config("businesslogic")[24]['title']);
         
         return $oResponse;
     }
 
     /**
      * Display the specified resource.
      *
      * @param  \App\Diagnostic  $Diagnostic
      * @return \Illuminate\Http\Response
      */
     public function show($Diagnostic_id)
     {
         $oDiagnostic = Diagnostic::with(['createdBy','updatedBy','deletedBy'])->findOrFail($Diagnostic_id);
         
         if (!Gate::allows('diagnostic-show',$oDiagnostic))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Diagnostic"]), $oDiagnostic, false);
         
         $this->urlComponents(config("businesslogic")[24]['menu'][2], $oResponse, config("businesslogic")[24]['title']);
         
         return $oResponse;
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\Diagnostic  $Diagnostic
      * @return \Illuminate\Http\Response
      */
     public function edit(Diagnostic $Diagnostic)
     {
         //
     }
 
     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \App\Diagnostic  $Diagnostic
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, $id)
     {
         $oInput = $request->all();
 
         $oValidator = Validator::make($oInput,[
            'name'          => 'required|string|max:50|min:3|unique:diagnostics,name,'.$id,
            'description'   => 'present|nullable',
            'is_active'     => 'required|in:0,1',
            'preinstruction'=> 'present|nullable|max:150',
            'type'          => 'nullable|max:250',
        ]);

 
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
 
         $oDiagnostic = Diagnostic::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
         
         if (!Gate::allows('diagnostic-update',$oDiagnostic))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oDiagnostic = $oDiagnostic->update([
            'name'         => $oInput['name'],
            'description'  => $oInput['description'],
            'is_active'    => $oInput['is_active'],
            'type'         => isset($oInput['type']) ? $oInput['type'] : '',
            'preinstruction'=> $oInput['preinstruction'],
            'updated_by'   => Auth::user()->id,
            'updated_at'   => Carbon::now()->toDateTimeString(),
         ]);
         $oDiagnostic = Diagnostic::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
         
         $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Diagnostic"]), $oDiagnostic, false);
         
         $this->urlComponents(config("businesslogic")[24]['menu'][3], $oResponse, config("businesslogic")[24]['title']);
         
         return $oResponse;
     }
 
     // Soft Delete Doctors 
 
     public function destroy(Request $request)
     {
         $oInput = $request->all();
         $oValidator = Validator::make($oInput,[
             'ids' => 'required|array',
             'ids.*' => 'exists:diagnostics,id',
         ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         
         $aIds = $request->ids;
 
         $allDiagnostic = Diagnostic::findOrFail($aIds);
         
         foreach($allDiagnostic as $oRow)
             if (!Gate::allows('diagnostic-destroy',$oRow))
                 return responseBuilder()->error(__('auth.not_authorized'), 403, false);
         
         if(is_array($aIds)){
             foreach($aIds as $id){
                 $oDiagnostic = Diagnostic::find($id);
                 if($oDiagnostic){
                     $oDiagnostic->update(['deleted_by' => Auth::user()->id]);
                     $oDiagnostic->delete();
                 }
             }
         }else{
             $oDiagnostic = Diagnostic::findOrFail($aIds);
         
             $oDiagnostic->update(['deleted_by' => Auth::user()->id]);
             $oDiagnostic->delete();
         }
         $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Diagnostic"]));
         $this->urlComponents(config("businesslogic")[24]['menu'][4], $oResponse, config("businesslogic")[24]['title']);
         
         return $oResponse;
     }
     
     // Get soft deleted data
     public function deleted(Request $request)
     {
         if (!Gate::allows('diagnostic-deleted'))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
 
         $oInput = $request->all();
         
         $oQb = Diagnostic::onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);
 
         $oQb = QB::where($oInput,"id",$oQb);
         $oQb = QB::whereLike($oInput,"name",$oQb);
         $oQb = QB::whereLike($oInput,"description",$oQb);
         $oQb = QB::whereLike($oInput,"type",$oQb);
         $oQb = QB::where($oInput,"is_active",$oQb);
 
         $oDiagnostic = $oQb->paginate(10);
         
         $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Diagnostic"]), $oDiagnostic, false);
         
         $this->urlComponents(config("businesslogic")[24]['menu'][5], $oResponse, config("businesslogic")[24]['title']);
         
         return $oResponse;
     }
     // Restore any deleted data
     public function restore(Request $request)
     {  
         $oInput = $request->all();
         $oValidator = Validator::make($oInput,[
             'ids' => 'required|array',
             'ids.*' => 'exists:diagnostics,id',
         ]);
         if($oValidator->fails()){
             abort(400,$oValidator->errors()->first());
         }
         $aIds = $request->ids;
         
         $allDiagnostic= Diagnostic::onlyTrashed()->findOrFail($aIds);
         
         foreach($allDiagnostic as $oRow)
             if (!Gate::allows('diagnostic-restore',$oRow))
                 return responseBuilder()->error(__('auth.not_authorized'), 403, false);
         
         if(is_array($aIds)){
             foreach($aIds as $id){
                 
                 $oDiagnostic = Diagnostic::onlyTrashed()->find($id);
                 if($oDiagnostic){
                     $oDiagnostic->restore();
                 }
             }
         }else{
             $oDiagnostic = Diagnostic::onlyTrashed()->findOrFail($aIds);
             $oDiagnostic->restore();
         }
         
 
         $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Diagnostic"]));
         
         $this->urlComponents(config("businesslogic")[24]['menu'][6], $oResponse, config("businesslogic")[24]['title']);
         
         return $oResponse;
     }
     // Permanent Delete
     public function delete($id)
     {
         $oDiagnostic = Diagnostic::onlyTrashed()->findOrFail($id);
         
         if (!Gate::allows('diagnostic-delete',$oDiagnostic))
             return responseBuilder()->error(__('auth.not_authorized'), 403, false);
 
         $oDiagnostic->forceDelete();
         
         $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Diagnostic"]));
         
         $this->urlComponents(config("businesslogic")[24]['menu'][7], $oResponse, config("businesslogic")[24]['title']);
         
         return $oResponse;
     }
}
