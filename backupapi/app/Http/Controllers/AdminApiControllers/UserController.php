<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Media;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
class UserController extends Controller
{
    use \App\Traits\WebServicesDoc;

    // get list of all the Users
   
    public function index(Request $request)
    {
        if (!Gate::allows('user-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
       
        $oQb = User::orderByDesc('updated_at')->whereNull('doctor_id')->with(['organization','roles','doctor','patient','media','createdBy','updatedBy','restoredBy','deletedBy']);
        
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"phone",$oQb);
        $oQb = QB::whereLike($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"username",$oQb);

        $oUser = $oQb->paginate(20);
        foreach($oUser as $u){
            $u['image'] = (count($u->profilePic)>0)? config("app")['url'].$u->profilePic[0]->url:null;
        }
        $oResponse = responseBuilder()->success(__('message.users.list'), $oUser, false);
        $this->urlComponents(config("businesslogic")[8]['menu'][0], $oResponse, config("businesslogic")[8]['title']);
        return $oResponse;
    }

    // create new User
   
    public function create(Request $request)
    {
        
    }

    // Store new User

    public function store(Request $request)
    {
        if (!Gate::allows('user-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'name'          => 'required|max:50|min:3|string',
            'phone'         => 'required|digits_between:10,20|unique:users,phone',
            'email'         => 'nullable|max:50|email|unique:users',
            'password'      => 'required|string|min:6|confirmed',
            'role_id'       => 'required|exists:roles,id',
            'organization_id'=> 'required|exists:organizations,id'
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $oUser = User::create([
            'name'          =>  $oInput['name'],
            'phone'         =>  $oInput['phone'],
            'email'         =>  $oInput['email'],
            'username'      =>  $oInput['phone'],
            'organization_id'=>  $oInput['organization_id'],
            'password'      =>  Hash::make($oInput['password']),
            'remember_token'=>  Str::random(50),
            'created_by'    =>  Auth::user()->id,
            'updated_by'    =>  Auth::user()->id,
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        if($request->image){
            
            $oPaths = FileHelper::saveImages($request->image,'users');

            $media = Media::create([
                'user_id'   => $oUser->id,
                'url'       => $oPaths['url'],
                'alt_tag'   => $oInput['name'],
                'type'      => 'profile',
                'created_by'=> Auth::user()->id,
                'updated_by'=> Auth::user()->id,
                'created_at'=> Carbon::now()->toDateTimeString(),
                'updated_at'=> Carbon::now()->toDateTimeString(),
            ]); 
        }
       
        $oRole = Role::findOrFail($oInput['role_id']);

        $oUser->roles()->sync($oRole->id);

        $oUser= User::with(['organization','roles','doctor','patient','media','createdBy','updatedBy','restoredBy','deletedBy'])->findOrFail($oUser->id);
        $oUser['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
        $oResponse = responseBuilder()->success(__('message.users.create'), $oUser, false);        
        
        $this->urlComponents(config("businesslogic")[8]['menu'][1], $oResponse, config("businesslogic")[8]['title']);
        
        return $oResponse;
    }
    // Show User details

    public function show($id)
    {
        $oUser= User::with(['organization','roles','doctor','patient','media','createdBy','updatedBy','restoredBy','deletedBy'])->findOrFail($id);

        if (!Gate::allows('user-show',$oUser))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oUser['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
        $oResponse = responseBuilder()->success(__('message.users.detail'), $oUser, false);
        
        $this->urlComponents(config("businesslogic")[8]['menu'][2], $oResponse, config("businesslogic")[8]['title']);
        
        return $oResponse;
    }

    public function edit(User $oUser)
    {
        //
    } 

    // Update User details
    
    public function update(Request $request, $id)
    {
        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'name'          => 'required|max:50|min:3|string',
            'phone'         => 'required|digits_between:10,20|unique:users,phone,'.$id,
            'email'         => 'required|max:50|email|unique:users,email,'.$id,
            'role_id'       => 'required|exists:roles,id',
            'organization_id'=> 'required|exists:organizations,id'
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oUser = User::findOrFail($id); 
        $oOldUser = $oUser;

        if (!Gate::allows('user-update',$oUser))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
            
        if ($request->password || $request->password_confirmation) {
            
            $oValidator = Validator::make($oInput,[
                'password'    => 'required|string|min:6|confirmed',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oUser->update(['password'=>Hash::make($oInput['password'])]);
        }
        if($request->image){
            
            $oMedia = Media::where('user_id',$oUser->id)->where('type','profile')->first();
            
            if($oMedia){
                FileHelper::deleteImages($oMedia);
                $oMedia->forceDelete();
            }
           
            $oPaths = FileHelper::saveImages($request->image,'users');
            
            $media = Media::create([
                'user_id'   => $oUser->id,
                'url'       => $oPaths['url'],
                'alt_tag'   => $oInput['name'],
                'type'      => 'profile',
                'updated_by'=> Auth::user()->id,
                'updated_at'=> Carbon::now()->toDateTimeString(),
            ]); 
        }

        $aUserUpdateData = array(
            'name'          =>  $oInput['name'],
            'phone'         =>  $oInput['phone'],
            'email'         =>  $oInput['email'],
            'username'      =>  $oInput['phone'],
            'organization_id'=>  $oInput['organization_id'],
            'updated_by'    =>  Auth::user()->id,
            'updated_at'    =>  Carbon::now()->toDateTimeString()
        );


        //find if email address modified, if yes then switch flag off of email verified
        if($oOldUser->email != $oInput['email']){
            $aUserUpdateData['email_verified'] = 0;
        }

        //find if phone number modified, then switch flag off of phone verified
        if($oOldUser->phone != $oInput['phone']){
            $aUserUpdateData['phone_verified'] = 0;
        }


        $oUser->update($aUserUpdateData);
        
        $oRole = Role::findOrFail($oInput['role_id']);
        
        $oUser->roles()->sync($oRole->id);
            
        $oUser = User::with(['organization','roles','doctor','patient','media','createdBy','updatedBy','restoredBy','deletedBy'])->findOrFail($id);
        $oUser['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
        $oResponse = responseBuilder()->success(__('message.users.update'), $oUser, false);
        
        $this->urlComponents(config("businesslogic")[8]['menu'][3], $oResponse, config("businesslogic")[8]['title']);
        
        return $oResponse;
    }

    // Soft Delete User 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allUser = User::findOrFail($aIds);
     
        foreach($allUser as $oRow)
            if (!Gate::allows('user-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        if(is_array($aIds)){
        
            foreach($aIds as $id){
                $oUser = User::find($id);
                if($oUser){
                    $oUser->update(['deleted_by' => Auth::user()->id]);
                    $oUser->delete();
                }
            }
        }else{

            $oUser = User::findOrFail($aIds);
        
            $oUser->update(['deleted_by' => Auth::user()->id]);
            
            $oUser->delete();
        }
        
        $oResponse = responseBuilder()->success(__('message.users.delete'));
        
        $this->urlComponents(config("businesslogic")[8]['menu'][4], $oResponse, config("businesslogic")[8]['title']);
        
        return $oResponse;
    }
    // Get soft deleted data
    public function deleted(Request $request)
    {
        if (!Gate::allows('user-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();

        $oQb = User::onlyTrashed()->orderByDesc('deleted_at')->with(['organization','roles','doctor','patient','media','createdBy','updatedBy','restoredBy','deletedBy']);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::where($oInput,"phone",$oQb);
        $oQb = QB::where($oInput,"email",$oQb);
        $oQb = QB::whereLike($oInput,"username",$oQb);

        $oUser = $oQb->paginate(20);
        $url = null;
        foreach($oUser as $u){
            $u['image'] = (count($u->profilePic)>0)? config("app")['url'].$u->profilePic[0]->url:null;
        }
        $oResponse = responseBuilder()->success(__('message.users.deletedList'), $oUser, false);
        
        $this->urlComponents(config("businesslogic")[8]['menu'][5], $oResponse, config("businesslogic")[8]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;

        $allUser = User::onlyTrashed()->findOrFail($aIds);

        foreach($allUser as $oRow)
            if (!Gate::allows('user-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oUser = User::onlyTrashed()->with(['organization','roles','doctor','patient','media','createdBy','updatedBy','restoredBy','deletedBy'])->find($id);
                if($oUser){
                    $oUser->restore();
                    $oUser->update([
                            'restored_by' => Auth::user()->id,
                            'restored_at' => Carbon::now()->toDateTimeString(),                  
                        ]);
                }
            }
        }else{
            $oUser = User::onlyTrashed()->with(['organization','roles','doctor','patient','media','createdBy','updatedBy','restoredBy','deletedBy'])->findOrFail($aIds);
            $oUser->update([
                'restored_by' => Auth::user()->id,
                'restored_at' => Carbon::now()->toDateTimeString(),                  
                ]);
            $oUser->restore();
        }

        $oResponse = responseBuilder()->success(__('message.users.restore'));
        
        $this->urlComponents(config("businesslogic")[8]['menu'][6], $oResponse, config("businesslogic")[8]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oUser = User::onlyTrashed()->with(['organization','roles','doctor','patient','media','createdBy','updatedBy','restoredBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('user-delete',$oUser))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);   
        
        $oUser->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.users.permanentDelete'));
        
        $this->urlComponents(config("businesslogic")[8]['menu'][7], $oResponse, config("businesslogic")[8]['title']);
        
        return $oResponse;
    }
    public function createUserRole(Request $request){
        
        if (!Gate::allows('user-assign-role'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        
        $oValidator = Validator::make($oInput,[
            'user_id'   => 'required',
            'role_id'   => 'required',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oUser_id = $request->user_id;
        $oRole_id = $request->role_id;
       
        $oUser = User::with('roles')->findOrFail($oUser_id);
        $oRole = Role::findOrFail($oRole_id);
       
        $oUser->roles()->sync($oRole_id);
        
        $oResponse = responseBuilder()->success(__('message.users.createUserRole'));
        $this->urlComponents(config("businesslogic")[8]['menu'][8], $oResponse, config("businesslogic")[8]['title']);        
        
        return $oResponse;
    }
}
