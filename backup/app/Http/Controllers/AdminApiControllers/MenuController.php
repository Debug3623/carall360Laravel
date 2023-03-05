<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class MenuController extends Controller
{
    use \App\Traits\WebServicesDoc;

    // get list of all the permissions
   
    public function index(Request $request)
    {
        if (!Gate::allows('menu-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();

        $aRole = Role::where('is_active',1)->with('menu')->get();
        
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Role Menu"]), $aRole, false);
        $this->urlComponents(config("businesslogic")[22]['menu'][0], $oResponse, config("businesslogic")[22]['title']);
        
        return $oResponse;
    }

    // create new permission
   
    public function create(Request $request)
    {
        
    }

    // Store new permission

    public function store(Request $request)
    {
        if (!Gate::allows('menu-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false); 
        
        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'title'             => 'required|string|max:50',
            'permission_code'   => 'required|unique:permissions|max:50',
            'description'       => 'nullable|string|max:500',
            'url'               => 'required|unique:permissions|max:150',
            'type'              => 'required|in:allow,disallow',
            'is_active'         => 'required|in:0,1',
            'parent_id'         => 'present|nullable|exists:permissions,id',
            'role_id'           => 'required|exists:roles,id',
            'alias'             => 'sometimes|nullable|string|max:100',
            'menu_order'        => 'sometimes|nullable',
            'selected_menu'     => 'sometimes|nullable|max:25',
            'is_menu'           => 'required|in:0,1',
            'icon'              => 'sometimes|nullable|max:250',
            'css_class'         => 'sometimes|nullable|max:500',
            'div_id'            => 'sometimes|nullable|max:500',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oPermission = Permission::create([
            'title'             =>  $oInput['title'],
            'permission_code'   =>  $oInput['permission_code'],
            'description'       =>  $oInput['description'],
            'url'               =>  $oInput['url'],
            'type'              =>  $oInput['type'],
            'is_active'         =>  $oInput['is_active'],
            'parent_id'         =>  $oInput['parent_id'],
            'alias'             =>  isset($oInput['alias'])?$oInput['alias']:null,
            'menu_order'        =>  isset($oInput['menu_order'])?$oInput['menu_order']:null,
            'is_menu'           =>  isset($oInput['is_menu'])?$oInput['is_menu']:1,
            'selected_menu'     =>  isset($oInput['selected_menu'])?$oInput['selected_menu']:null,
            'icon'              =>  isset($oInput['icon'])?$oInput['icon']:null,
            'css_class'         =>  isset($oInput['css_class'])?$oInput['css_class']:null,
            'div_id'            =>  isset($oInput['div_id'])?$oInput['div_id']:null,
            'created_by'        =>  Auth::user()->id,
            'updated_by'        =>  Auth::user()->id,
            'created_at'        =>  Carbon::now()->toDateTimeString(),
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
        ]);
        
        $oRole = Role::findOrFail($oInput['role_id']);

        $rolePermission = DB::table('permission_role')->insert([
            "role_id" => $oInput['role_id'],
            "permission_id" => $oPermission->id,
        ]);
        
        $aRole = Role::where('is_active',1)->with('menu')->get();

        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Role Menu"]), $aRole, false);
        
        $this->urlComponents(config("businesslogic")[22]['menu'][1], $oResponse, config("businesslogic")[22]['title']);

        return $oResponse;
    }
    // Show permission details

    public function show($id)
    {
        $oRole= Role::with('menu')->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Role Menu"]), $oRole, false);
        
        $this->urlComponents(config("businesslogic")[22]['menu'][2], $oResponse, config("businesslogic")[22]['title']);

        return $oResponse;
    }

    public function edit(Permission $oPermission)
    {
        //
    } 

    // Update permission details
    
    public function update(Request $request, $id)
    { 

        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'title'             => 'required|max:50',
            'permission_code'   => 'required|max:50|unique:permissions,permission_code,'.$id,
            'description'       => 'nullable|max:500',
            'url'               => 'required|max:150|unique:permissions,url,'.$id,
            'type'              => 'required|in:allow,disallow',
            'is_active'         => 'required|in:0,1',
            'is_menu'           => 'sometimes|in:0,1',
            'parent_id'         => 'present|nullable|exists:permissions,id',
            'role_id'           => 'required|exists:roles,id',
            'alias'             => 'sometimes|nullable|string|max:100',
            'menu_order'        => 'sometimes|nullable',
            'selected_menu'     => 'sometimes|nullable|max:25',
            'is_menu'           => 'sometimes|in:0,1',
            'icon'              => 'sometimes|nullable|max:250',
            'css_class'         => 'sometimes|nullable|max:500',
            'div_id'            => 'sometimes|nullable|max:500',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oPermission = Permission::findOrFail($id); 
        
        if (!Gate::allows('menu-update',$oPermission))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oPermissions = $oPermission->update([
            'title'             =>  $oInput['title'],
            'permission_code'   =>  $oInput['permission_code'],
            'description'       =>  $oInput['description'],
            'url'               =>  $oInput['url'],
            'type'              =>  $oInput['type'],
            'is_active'         =>  $oInput['is_active'],
            'parent_id'         =>  $oInput['parent_id'],
            'alias'             =>  isset($oInput['alias'])?$oInput['alias']:null,
            'menu_order'        =>  isset($oInput['menu_order'])?$oInput['menu_order']:null,
            'is_menu'           =>  isset($oInput['is_menu'])?$oInput['is_menu']:1,
            'selected_menu'     =>  isset($oInput['selected_menu'])?$oInput['selected_menu']:null,
            'icon'              =>  isset($oInput['icon'])?$oInput['icon']:null,
            'css_class'         =>  isset($oInput['css_class'])?$oInput['css_class']:null,
            'div_id'            =>  isset($oInput['div_id'])?$oInput['div_id']:null,
            'updated_by'        =>  Auth::user()->id,
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
        ]);
        $oRole = Role::findOrFail($oInput['role_id']);

        $rolePermission = DB::table('permission_role')->insert([
            "role_id" => $oInput['role_id'],
            "permission_id" => $oPermission->id,
        ]);
        
        $aRole = Role::where('is_active',1)->with('menu')->get();
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Role Menu"]), $aRole, false);
        
        $this->urlComponents(config("businesslogic")[22]['menu'][3], $oResponse, config("businesslogic")[22]['title']);

        return $oResponse;
    }

    // Soft Delete permission 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:permissions,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;
        
        $oAllPermission = Permission::findOrFail($aIds);
        
        foreach($oAllPermission as $oRow)
            if (!Gate::allows('menu-destroy', $oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oPermission = Permission::find($id);
                if($oPermission){
                    $oPermission->update(['deleted_by' => Auth::user()->id]);
                    $oPermission->delete();
                }
            }
        }else{
            $oPermission = Permission::findOrFail($aIds);
            $oPermission->update(['deleted_by' => Auth::user()->id]);
            $oPermission->delete();
        }
        
        $oResponse = responseBuilder()->success(__('message.permissions.delete'));
        
        $this->urlComponents(config("businesslogic")[22]['menu'][4], $oResponse, config("businesslogic")[22]['title']);
        
        return $oResponse;
    }

    // Get soft deleted data
    public function deleted(Request $request)
    {
        if (!Gate::allows('menu-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oQb = Permission::onlyTrashed()->orderByDESC('deleted_at')->with(['childPermission','parentPermission','createdBy','updatedBy','deletedBy']);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"title",$oQb);
        $oQb = QB::whereLike($oInput,"permission_code",$oQb);
        $oQb = QB::whereLike($oInput,"description",$oQb);
        $oQb = QB::whereLike($oInput,"url",$oQb);
        $oQb = QB::where($oInput,"type",$oQb);
        
        $aPermissions = $oQb->paginate(20);
        $oResponse = responseBuilder()->success(__('message.permissions.deletedList'), $aPermissions, false);
        
        $this->urlComponents(config("businesslogic")[22]['menu'][5], $oResponse, config("businesslogic")[22]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids'  => 'required|array',
            'ids.*' => 'exists:permissions,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
       $oAllPermission =  Permission::onlyTrashed()->findOrFail($aIds);
        
        foreach($oAllPermission as $oRow)
            if (!Gate::allows('menu-restore', $oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
            if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oPermission = Permission::onlyTrashed()->with(['childPermission','parentPermission','createdBy','updatedBy','deletedBy'])->find($id);
                if($oPermission){
                    $oPermission->restore();
                }
            }
        }else{
            $oPermission = Permission::onlyTrashed()->with(['childPermission','parentPermission','createdBy','updatedBy','deletedBy'])->findOrFail($aIds);
            $oPermission->restore();
        } 
        $oResponse = responseBuilder()->success(__('message.permissions.restore'));
        
        $this->urlComponents(config("businesslogic")[22]['menu'][6], $oResponse, config("businesslogic")[22]['title']);

        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oPermission = Permission::onlyTrashed()->with(['childPermission','parentPermission','createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('menu-delete',$oPermission))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);   
        
        $oPermission->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.permissions.permanentDelete'));
        
        $this->urlComponents(config("businesslogic")[22]['menu'][7], $oResponse, config("businesslogic")[22]['title']);
        
        return $oResponse;
    }
}
