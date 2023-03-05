<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use App\Helpers\QB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    use \App\Traits\WebServicesDoc;

    // get list of all the Roles
   
    public function index(Request $request)
    {

        if (!Gate::allows('role-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();

        $oQb = Role::orderByDESC("updated_at")->with(['createdBy','updatedBy','deletedBy',]);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);
        
        $oRole = $oQb->paginate(20);

        $oResponse = responseBuilder()->success(__('message.roles.list'), $oRole, false);
        $this->urlComponents(config("businesslogic")[2]['menu'][0], $oResponse, config("businesslogic")[2]['title']);
        return $oResponse;
    }

    // create new Role
   
    public function create(Request $request)
    {
        
    }

    // Store new Role

    public function store(Request $request)
    {

        if (!Gate::allows('role-create'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'name'        => 'required|string|unique:roles|max:50',
            'description' => 'nullable|max:500',
            'is_active'   => 'required|in:0,1',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oRole = Role::create([
            'name'          =>  $oInput['name'],
            'description'   =>  $oInput['description'],
            'is_active'     =>  $oInput['is_active'],
            'created_by'    =>  Auth::user()->id,
            'updated_by'    =>  Auth::user()->id,
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);

        $oRole= Role::with(['createdBy','updatedBy'])->findOrFail($oRole->id);

        $oResponse = responseBuilder()->success(__('message.roles.create'), $oRole, false);
        
        $this->urlComponents(config("businesslogic")[2]['menu'][1], $oResponse, config("businesslogic")[2]['title']);
       
        
        return $oResponse;
    }
    // Show Role details

    public function show($id)
    {

        $oRole= Role::with(['createdBy','updatedBy','permissions'])->findOrFail($id);

         if (!Gate::allows('role-show', $oRole))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.roles.detail'), $oRole, false);
        
        $this->urlComponents(config("businesslogic")[2]['menu'][2], $oResponse, config("businesslogic")[2]['title']);
        
        return $oResponse;
    }

    public function edit(Role $oRole)
    {
        //
    } 

    // Update Role details
    
    public function update(Request $request, $id)
    {
        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'name'       => 'required|string|max:50|unique:roles,name,'.$id,
            'is_active'  => 'required|in:0,1',
            'description'=> 'nullable|max:500',

        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oRole = Role::findOrFail($id); 

        if (!Gate::allows('role-update', $oRole))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        $oRole = $oRole->update([
            'name'             =>  $oInput['name'],
            'description'       =>  $oInput['description'],
            'is_active'         =>  $oInput['is_active'],
            'updated_by'        =>  Auth::user()->id,
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
        ]);
        $oRole = Role::with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);

        $oResponse = responseBuilder()->success(__('message.roles.update'), $oRole, false);
        
        $this->urlComponents(config("businesslogic")[2]['menu'][3], $oResponse, config("businesslogic")[2]['title']);
        
        return $oResponse;
    }

    // Soft Delete Role 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:roles,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;

        $oAllRoles = Role::whereIn('id', $aIds)->get();
        foreach($oAllRoles as $oRow)
            if (!Gate::allows('role-destroy', $oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        Role::findOrFail($aIds);
        if(is_array($aIds)){
        
            foreach($aIds as $id){
                $oRole = Role::find($id);
                if($oRole){
                    $oRole->update(['deleted_by' => Auth::user()->id]);
                    $oRole->delete();
                }
            }
        }else{

            $oRole = Role::findOrFail($aIds);
        
            $oRole->update(['deleted_by' => Auth::user()->id]);
            
            $oRole->delete();
        }
        
        $oResponse = responseBuilder()->success(__('message.roles.delete'));
        
        $this->urlComponents(config("businesslogic")[2]['menu'][4], $oResponse, config("businesslogic")[2]['title']);
        
        return $oResponse;
    }
    // Get soft deleted data
    public function deleted(Request $request)
    {

        if (!Gate::allows('role-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oQb = Role::onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy']);
         
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);
        
        $oRole = $oQb->paginate(20);
        
        $oResponse = responseBuilder()->success(__('message.roles.deletedList'), $oRole, false);
        
        $this->urlComponents(config("businesslogic")[2]['menu'][5], $oResponse, config("businesslogic")[2]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:roles,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;

        $oAllRoles = Role::whereIn('id', $aIds)->get();
        foreach($oAllRoles as $oRow)
            if (!Gate::allows('role-restore', $oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        Role::onlyTrashed()->findOrFail($aIds);
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oRole = Role::onlyTrashed()->with(['createdBy','updatedBy'])->find($id);
                if($oRole){
                    $oRole->restore();
                }
            }
        }else{
            $oRole = Role::onlyTrashed()->with(['createdBy','updatedBy'])->findOrFail($aIds);
            $oRole->restore();
        }

        $oResponse = responseBuilder()->success(__('message.roles.restore'));
        
        $this->urlComponents(config("businesslogic")[2]['menu'][6], $oResponse, config("businesslogic")[2]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oRole = Role::onlyTrashed()->with(['createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('role-delete', $oRole))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oRole->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.roles.permanentDelete'));
        
        $this->urlComponents(config("businesslogic")[2]['menu'][7], $oResponse, config("businesslogic")[2]['title']);
        
        return $oResponse;
    }
    public function createRolePermissions(Request $request){
        

        if (!Gate::allows('role-create-permission')) 
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oInput = $request->all();
        
        $oValidator = Validator::make($oInput,[
            'role_id'=> 'required|exists:roles,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oRole_id = $request->role_id;
        $aPermission_ids = $request->permission_ids;
       
        $oRole = Role::with('permissions')->findOrFail($oRole_id);
        $aPermissions = Permission::findOrFail($aPermission_ids);
       
        foreach($aPermissions as $p){
            $aIds[] = $p->id;
        }
       
        $oRole->permissions()->sync($aIds);
        
        $oResponse = responseBuilder()->success(__('message.roles.createRolePermission'));
        $this->urlComponents(config("businesslogic")[2]['menu'][8], $oResponse, config("businesslogic")[2]['title']);        
        
        return $oResponse;
    }
    public function selectedRole(){
      
        $notAllowRoles = config("businesslogic")['notAllowRoles'];
        $oRole = Role::whereNotIn('name',$notAllowRoles)->orderByDESC("updated_at")->get();
       
        $oResponse = responseBuilder()->success(__('message.roles.list'), $oRole, false);
        $this->urlComponents(config("businesslogic")[2]['menu'][9], $oResponse, config("businesslogic")[2]['title']);
       
        return $oResponse;
    }
}
