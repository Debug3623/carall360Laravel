<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\PlanCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Helpers\QB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class PlanCategoryController extends Controller
{
    use \App\Traits\WebServicesDoc;

    // get list of all the PlanCategorys
   
    public function index(Request $request)
    {
        if (!Gate::allows('plan-category-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();

        $oQb = PlanCategory::orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"category_name",$oQb);
        $oQb = QB::whereLike($oInput,"fee_frequency",$oQb);
        $oQb = QB::whereLike($oInput,"freq_value",$oQb);
        $oQb = QB::whereLike($oInput,"fix_percentage_fee",$oQb);
        $oQb = QB::where($oInput,"fee",$oQb);
        // $oQb = QB::where($oInput,"service_charges",$oQb);
        $oQb = QB::where($oInput,"fee_grace_days",$oQb);
        
        $aPlanCategorys = $oQb->paginate(20);
        
        $oResponse = responseBuilder()->success(__('message.general.list',['mod'=>'Plan Categories']), $aPlanCategorys, false);
        $this->urlComponents(config("businesslogic")[32]['menu'][0], $oResponse, config("businesslogic")[32]['title']);
        return $oResponse;
    }

    // create new PlanCategory
   
    public function create(Request $request)
    {
        
    }

    // Store new PlanCategory

    public function store(Request $request)
    {
        if (!Gate::allows('plan-category-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false); 
        
        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'category_name'     => 'required|string|max:20',
            'fee_frequency'     => 'required|in:once,weeks,months',
            'freq_value'        => 'required|max:5',
            'fix_percentage_fee'=> 'required|in:F,P',
            'fee'               => 'required|max:5|min:0',
            // 'service_charges'   => 'present|nullable|max:5|min:0',
            'fee_grace_days'    => 'required|max:5',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        if($oInput['fix_percentage_fee'] =='P' && $oInput['fee'] > 100){
            return responseBuilder()->error(__('Percentage should be less then 100%'), 403, false);
        }
        // if(($oInput['fee_frequency'] == 'weeks') && ($oInput['freq_value'] > 2)){
        //     return responseBuilder()->error(__('message.weeks_fee_frequency'), 403, false);
        // }
        // if(($oInput['fee_frequency'] == 'months') && ($oInput['freq_value'] > 12)){
        //     return responseBuilder()->error(__('message.months_fee_frequency'), 403, false);
        // }
        // if(($oInput['fee_frequency'] == 'months') && ($oInput['freq_value'] == 0)){
        //     return responseBuilder()->error(__('message.months_fee_frequency'), 403, false);
        // }

        $oPlanCategory = PlanCategory::create([
            'category_name'     =>  $oInput['category_name'],
            'fee_frequency'     =>  $oInput['fee_frequency'],
            'freq_value'        =>  $oInput['freq_value'],
            'fix_percentage_fee'=>  $oInput['fix_percentage_fee'],
            'fee'               =>  $oInput['fee'],
            // 'service_charges'   =>  $oInput['service_charges'],
            'fee_grace_days'    =>  $oInput['fee_grace_days'],
            'created_by'        =>  Auth::user()->id,
            'updated_by'        =>  Auth::user()->id,
            'created_at'        =>  Carbon::now()->toDateTimeString(),
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
        ]);

        $oPlanCategory= PlanCategory::with(['createdBy','updatedBy','deletedBy'])->findOrFail($oPlanCategory->id);

        $oResponse = responseBuilder()->success(__('message.general.created',['mod'=>'Plan Categories']), $oPlanCategory, false);
        
        $this->urlComponents(config("businesslogic")[32]['menu'][1], $oResponse, config("businesslogic")[32]['title']);
       
        
        return $oResponse;
    }
    // Show PlanCategory details

    public function show($id)
    {
        $oPlanCategory= PlanCategory::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
     
        if (!Gate::allows('plan-category-show', $oPlanCategory))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
     
        $oResponse = responseBuilder()->success(__('message.general.detail',['mod'=>'Plan Categories']), $oPlanCategory, false);
        
        $this->urlComponents(config("businesslogic")[32]['menu'][2], $oResponse, config("businesslogic")[32]['title']);
        
        return $oResponse;
    }

    public function edit(PlanCategory $oPlanCategory)
    {
        //
    } 

    // Update PlanCategory details
    
    public function update(Request $request, $id)
    { 

        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'category_name'     => 'required|string|max:20',
            'fee_frequency'     => 'required|in:once,weeks,months',
            'freq_value'        => 'required|max:5',
            'fix_percentage_fee'=> 'required|in:F,P',
            'fee'               => 'required|max:5|min:0',
            // 'service_charges'   => 'present|nullable|max:5|min:0',
            'fee_grace_days'    => 'required|max:5',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        if($oInput['fix_percentage_fee'] =='P' && $oInput['fee'] > 100){
            return responseBuilder()->error(__('Percentage should be less then 100%'), 403, false);
        }
        $oPlanCategory = PlanCategory::findOrFail($id); 
        
        if (!Gate::allows('plan-category-update',$oPlanCategory))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oPlanCategorys = $oPlanCategory->update([
            'category_name'     =>  $oInput['category_name'],
            'fee_frequency'     =>  $oInput['fee_frequency'],
            'freq_value'        =>  $oInput['freq_value'],
            'fix_percentage_fee'=>  $oInput['fix_percentage_fee'],
            'fee'               =>  $oInput['fee'],
            // 'service_charges'   =>  $oInput['service_charges'],
            'fee_grace_days'    =>  $oInput['fee_grace_days'],
            'updated_by'        =>  Auth::user()->id,
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
        ]);
        $oPlanCategory = PlanCategory::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);

        $oResponse = responseBuilder()->success(__('message.general.update',['mod'=>'Plan Categories']), $oPlanCategory, false);
        
        $this->urlComponents(config("businesslogic")[32]['menu'][3], $oResponse, config("businesslogic")[32]['title']);
        
        return $oResponse;
    }

    // Soft Delete PlanCategory 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:plan_categories,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;
        
        $oAllPlanCategory = PlanCategory::findOrFail($aIds);
        
        foreach($oAllPlanCategory as $oRow)
            if (!Gate::allows('plan-category-destroy', $oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oPlanCategory = PlanCategory::find($id);
                if($oPlanCategory){
                    $oPlanCategory->update(['deleted_by' => Auth::user()->id]);
                    $oPlanCategory->delete();
                }
            }
        }else{
            $oPlanCategory = PlanCategory::findOrFail($aIds);
            $oPlanCategory->update(['deleted_by' => Auth::user()->id]);
            $oPlanCategory->delete();
        }
        
        $oResponse = responseBuilder()->success(__('message.general.delete',['mod'=>'Plan Categories']));
        $this->urlComponents(config("businesslogic")[32]['menu'][4], $oResponse, config("businesslogic")[32]['title']);
        
        return $oResponse;
    }

    // Get soft deleted data
    public function deleted(Request $request)
    {
        if (!Gate::allows('plan-category-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oQb = PlanCategory::onlyTrashed()->orderByDESC('deleted_at')->with(['createdBy','updatedBy','deletedBy']);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"category_name",$oQb);
        $oQb = QB::whereLike($oInput,"fee_frequency",$oQb);
        $oQb = QB::whereLike($oInput,"freq_value",$oQb);
        $oQb = QB::whereLike($oInput,"fix_percentage_fee",$oQb);
        $oQb = QB::where($oInput,"fee",$oQb);
        $oQb = QB::where($oInput,"fee_grace_days",$oQb);
        
        $aPlanCategorys = $oQb->paginate(20);
        $oResponse = responseBuilder()->success(__('message.general.deletedList',['mod'=>'Plan Categories']), $aPlanCategorys, false);
        
        $this->urlComponents(config("businesslogic")[32]['menu'][5], $oResponse, config("businesslogic")[32]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:plan_categories,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
       $oAllPlanCategory =  PlanCategory::onlyTrashed()->findOrFail($aIds);
        
        foreach($oAllPlanCategory as $oRow)
            if (!Gate::allows('plan-category-restore', $oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
                if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oPlanCategory = PlanCategory::onlyTrashed()->find($id);
                if($oPlanCategory){
                    $oPlanCategory->restore();
                }
            }
        }else{
            $oPlanCategory = PlanCategory::onlyTrashed()->findOrFail($aIds);
            $oPlanCategory->restore();
        } 
        $oResponse = responseBuilder()->success(__('message.general.restore',['mod'=>'Plan Categories']));
        
        $this->urlComponents(config("businesslogic")[32]['menu'][6], $oResponse, config("businesslogic")[32]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oPlanCategory = PlanCategory::onlyTrashed()->with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('plan-category-delete',$oPlanCategory))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);   
        
        $oPlanCategory->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',['mod'=>'Plan Categories']));
        
        $this->urlComponents(config("businesslogic")[32]['menu'][7], $oResponse, config("businesslogic")[32]['title']);
        
        return $oResponse;
    }
    public function allPlanCategories(Request $request)
    {
        $aPlanCategorys = PlanCategory::orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy'])->get();
        
        $oResponse = responseBuilder()->success(__('message.general.list',['mod'=>'Plan Categories']), $aPlanCategorys, false);
        return $oResponse;
    }
}
