<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;

use App\Organization;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
class OrganizationController extends Controller
{
    use \App\Traits\WebServicesDoc;

    // get list of all the Organizations
   
    public function index(Request $request)
    {
        if (!Gate::allows('organization-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
       
        $oQb = Organization::orderBYDesc('updated_at')->with(['countryCode','cityId','createdBy','updatedBy','restoredBy','deletedBy']);
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"phone",$oQb);
        $oQb = QB::whereLike($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        
        $oOrganizations = $oQb->paginate(20);
        
        $oResponse = responseBuilder()->success(__('message.organization.list'), $oOrganizations, false);
        
        $this->urlComponents(config("businesslogic")[0]['menu'][0], $oResponse, config("businesslogic")[0]['title']);
        
        return $oResponse;
    }

    // Store new Organization

    public function store(Request $request)
    {
        if (!Gate::allows('organization-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'name'        => 'required|string|max:50|min:3',
            'phone'       => 'required|digits_between:10,20|unique:organizations',
            'email'       => 'required|email|unique:organizations|max:50',
            'country_code'=> 'required|max:3',
            'city_id'     => 'required|integer',
            'doctor_max_discount'=> 'required|numeric',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oOrganization = Organization::create([
            'name'      =>  $oInput['name'],
            'phone'     =>  $oInput['phone'],
            'email'     =>  $oInput['email'],
            'city_id'   =>  $oInput['city_id'],
            'country_code'=>  $oInput['country_code'],
            'doctor_max_discount'=>  $oInput['doctor_max_discount'],
            'created_by'=>  Auth::user()->id,
            'updated_by'=>  Auth::user()->id,
            'created_at'=>  Carbon::now()->toDateTimeString(),
            'updated_at'=>  Carbon::now()->toDateTimeString(),
        ]);

        $oOrganization= Organization::with(['countryCode','cityId','createdBy','updatedBy','restoredBy','deletedBy'])->findOrFail($oOrganization->id);

        $oResponse = responseBuilder()->success(__('message.organization.create'), $oOrganization, false);
        
        $this->urlComponents(config("businesslogic")[0]['menu'][1], $oResponse, config("businesslogic")[0]['title']);
        
        return $oResponse;
    }
    // Show Organization details

    public function show($id)
    {
        $oOrganization= Organization::with(['countryCode','cityId','createdBy','updatedBy','restoredBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('organization-show',$oOrganization))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oResponse = responseBuilder()->success(__('message.organization.detail'), $oOrganization, false);
        
        $this->urlComponents(config("businesslogic")[0]['menu'][2], $oResponse, config("businesslogic")[0]['title']);
        
        return $oResponse;
    }

    public function edit(Organization $oOrganization)
    {
        //
    } 

    // Update Organization details
    
    public function update(Request $request, $id)
    {
        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'name'        => 'required|string|max:50|min:3',
            'phone'       => 'required|digits_between:10,20|unique:organizations,phone,'.$id,
            'email'       => 'required|max:50|unique:organizations,email,'.$id,
            'city_id'     => 'required|numeric',
            'country_code'=> 'required|max:3',
            'doctor_max_discount'=> 'required|numeric',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oOrganization = Organization::findOrFail($id); 

        if (!Gate::allows('organization-update',$oOrganization))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oOrganization = $oOrganization->update([
            'name'        =>  $oInput['name'],
            'phone'       =>  $oInput['phone'],
            'email'       =>  $oInput['email'],
            'city_id'     =>  $oInput['city_id'],
            'country_code'=>  $oInput['country_code'],
            'doctor_max_discount' =>  $oInput['doctor_max_discount'],
            'updated_by'  =>  Auth::user()->id,
            'updated_at'  =>  Carbon::now()->toDateTimeString(),
        ]);
        $oOrganization = Organization::with(['countryCode','cityId','createdBy','updatedBy','restoredBy','deletedBy'])->findOrFail($id);

        $oResponse = responseBuilder()->success(__('message.organization.update'), $oOrganization, false);
        
        $this->urlComponents(config("businesslogic")[0]['menu'][3], $oResponse, config("businesslogic")[0]['title']);
        
        return $oResponse;
    }

    // Soft Delete Organization 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:organizations,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allOrganization = Organization::findOrFail($aIds);
        
        foreach($allOrganization as $oRow)
            if (!Gate::allows('organization-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oOrganization = Organization::find($id);
                if($oOrganization){
                    $oOrganization->update(['deleted_by' => Auth::user()->id]);
                    $oOrganization->delete();
                }
            }
        }else{
            $oOrganization = Organization::findOrFail($aIds);
        
            $oOrganization->update(['deleted_by' => Auth::user()->id]);
            $oOrganization->delete();
        }
        $oResponse = responseBuilder()->success(__('message.organization.delete'));
        $this->urlComponents(config("businesslogic")[0]['menu'][4], $oResponse, config("businesslogic")[0]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request)
    {
        if (!Gate::allows('organization-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oQb = Organization::onlyTrashed()->orderBYDesc('deleted_at')->with(['countryCode','cityId','createdBy','updatedBy','restoredBy','deletedBy']);  
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::where($oInput,"phone",$oQb);
        $oQb = QB::where($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        
        $oOrganizations = $oQb->paginate(20);
        $oResponse = responseBuilder()->success(__('message.organization.deletedList'), $oOrganizations, false);
        
        $this->urlComponents(config("businesslogic")[0]['menu'][5], $oResponse, config("businesslogic")[0]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {
        
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:organizations,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
       
        $allOrganization = Organization::onlyTrashed()->findOrFail($aIds);
        
        foreach($allOrganization as $oRow)
            if (!Gate::allows('organization-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oOrganization = Organization::onlyTrashed()->with(['countryCode','cityId','createdBy','updatedBy','restoredBy','deletedBy'])->find($id);
                if($oOrganization){
                    $oOrganization->update([
                        'restored_by' => Auth::user()->id,
                        'restored_at' => Carbon::now()->toDateTimeString(),                  
                        ]);
                    $oOrganization->restore();
                }
            }
        }else{
            $oOrganization = Organization::onlyTrashed()->with(['countryCode','cityId','createdBy','updatedBy','restoredBy','deletedBy'])->findOrFail($aIds);
            $oOrganization->update([
                'restored_by' => Auth::user()->id,
                'restored_at' => Carbon::now()->toDateTimeString(),                  
                ]);
            $oOrganization->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.organization.restore'));
        
        $this->urlComponents(config("businesslogic")[0]['menu'][6], $oResponse, config("businesslogic")[0]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oOrganization = Organization::onlyTrashed()->with(['countryCode','cityId','createdBy','updatedBy','restoredBy','deletedBy'])->findOrFail($id);
    
        if (!Gate::allows('organization-delete',$oOrganization))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);   
    
        $oOrganization->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.organization.permanentDelete'));
        
        $this->urlComponents(config("businesslogic")[0]['menu'][7], $oResponse, config("businesslogic")[0]['title']);
        
        return $oResponse;
    }
    public function createOrganizationPermissions(Request $request){
        

        if (!Gate::allows('create-organization-permissions')) 
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oInput = $request->all();
        
        $oValidator = Validator::make($oInput,[
            'organization_id'=> 'required|exists:organizations,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oOrganization_id = $request->organization_id;
        $aPermission_ids = $request->permission_ids;
       
        $oOrganization = Organization::with('permissions')->findOrFail($oOrganization_id);
        $aPermissions = Permission::findOrFail($aPermission_ids);
       
        foreach($aPermissions as $p){
            $aIds[] = $p->id;
        }
       
        $oOrganization->permissions()->sync($aIds);
        
        $oResponse = responseBuilder()->success(__('message.organization.createOrganizationPermission'));
        $this->urlComponents(config("businesslogic")[0]['menu'][8], $oResponse, config("businesslogic")[0]['title']);        
        
        return $oResponse;
    }
}