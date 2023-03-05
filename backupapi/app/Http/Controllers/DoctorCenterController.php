<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Center;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Doctor;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DoctorCenterController extends Controller
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
        
        if (!Gate::allows('doctor-center-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $doctor_id = decrypt($doctor_id);
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = Center::where('doctor_id',$doctor_id)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy','cityId','countryCode']);
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"address",$oQb);
        $oQb = QB::where($oInput,"city_id",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::where($oInput,"is_primary",$oQb);
        
        $oCenters = $oQb->paginate(10);
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Doctor Center"]), $oCenters, false);
        $this->urlComponents(config("businesslogic")[18]['menu'][0], $oResponse, config("businesslogic")[18]['title']);
        
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('doctor-center-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'name'          => 'required|string|max:100|min:3',
            'address'       => 'present|nullable|string|max:250',
            'is_active'     => 'required|in:0,1',
            'is_primary'    => 'required|in:0,1',
            'lat'           => 'required',
            'lng'           => 'required',
            'doctor_id'     => 'required|exists:doctors,id',
            'city_id'       => 'required|exists:cities,id',
            'country_code'  => 'required|exists:countries,code',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        if($oInput['is_primary'] == 1){
            
            $doctor_center = Center::where('doctor_id',$oInput['doctor_id'])->where('is_primary',1)->first();
            if(isset($doctor_center)){
                return responseBuilder()->error(__('message.general.already',["mod"=>"Primary Center"]), 403, false);        
            }
        }
        
        $oDate = Center::where('doctor_id',$oInput['doctor_id'])->where('name',$oInput['name'])->first();
        if($oDate){
            abort(400,__('Center name already entered'));
        }
        $oDoctorcenter = Center::create([
            'doctor_id' => $oInput['doctor_id'],
            'name'      => $oInput['name'],
            'address'   => $oInput['address'],
            'is_active' => $oInput['is_active'],
            'is_primary'=> $oInput['is_primary'],
            'lat'       => $oInput['lat'],
            'lng'       => $oInput['lng'],
            'city_id'   => $oInput['city_id'],
            'country_code'=> $oInput['country_code'],
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'created_at'=> Carbon::now()->toDateTimeString(),
            'updated_at'=> Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorcenter= Center::with(['createdBy','updatedBy','deletedBy','cityId','countryCode'])->findOrFail($oDoctorcenter->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Doctor Center"]), $oDoctorcenter, false);
        
        $this->urlComponents(config("businesslogic")[18]['menu'][1], $oResponse, config("businesslogic")[18]['title']);
        
        return $oResponse;
    }

    public function show($center_id)
    {
        $oCenter = Center::with(['createdBy','updatedBy','deletedBy','cityId','countryCode'])->findOrFail($center_id);
        
        if (!Gate::allows('doctor-center-show',$oCenter))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Doctor Center"]), $oCenter, false);
        
        $this->urlComponents(config("businesslogic")[18]['menu'][2], $oResponse, config("businesslogic")[18]['title']);
        
        return $oResponse;
    }

    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'name'          => 'required|string|max:100|min:3',
            'address'       => 'present|nullable|string|max:250',
            'is_active'     => 'required|in:0,1',
            'is_primary'    => 'required|in:0,1',
            'lat'           => 'required',
            'lng'           => 'required',
            'doctor_id'     => 'required|exists:doctors,id',
            'city_id'       => 'required|exists:cities,id',
            'country_code'  => 'required|exists:countries,code',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        if($oInput['is_primary'] == 1){
            $doctor_center = Center::where('id','!=',$id)->where('doctor_id',$oInput['doctor_id'])->where('is_primary',1)->first();
            if(isset($doctor_center)){
                return responseBuilder()->error(__('message.general.already',["mod"=>"Primary Center"]), 403, false);
            }
        }
        $oDate = Center::where('doctor_id',$oInput['doctor_id'])->where('id','!=',$id)->where('name',$oInput['name'])->first();
        if($oDate){
            abort(400,__('Center name already entered'));
        }

        $oDoctorcenter = Center::with(['createdBy','updatedBy','deletedBy','cityId','countryCode'])->findOrFail($id);
        
        if (!Gate::allows('doctor-center-update',$oDoctorcenter))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorcenter = $oDoctorcenter->update([
            'doctor_id'     => $oInput['doctor_id'],
            'name'          => $oInput['name'],
            'address'       => $oInput['address'],
            'is_active'     => $oInput['is_active'],
            'is_primary'    => $oInput['is_primary'],
            'lat'           => $oInput['lat'],
            'lng'           => $oInput['lng'],
            'city_id'       => $oInput['city_id'],
            'country_code'  => $oInput['country_code'],
            'updated_by'    =>  Auth::user()->id,
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $oDoctorcenter = Center::with(['createdBy','updatedBy','deletedBy','cityId','countryCode'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Doctor Center"]), $oDoctorcenter, false);
        
        $this->urlComponents(config("businesslogic")[18]['menu'][3], $oResponse, config("businesslogic")[18]['title']);
        
        return $oResponse;
    }

    // Soft Delete Doctors 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:centers,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allDoctorcenter = Center::findOrFail($aIds);
        
        foreach($allDoctorcenter as $oRow)
            if (!Gate::allows('doctor-center-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oDoctorcenter = Center::find($id);
                if($oDoctorcenter){
                    $oDoctorcenter->update(['deleted_by' => Auth::user()->id]);
                    $oDoctorcenter->delete();
                }
            }
        }else{
            $oDoctorcenter = Center::findOrFail($aIds);
        
            $oDoctorcenter->update(['deleted_by' => Auth::user()->id]);
            $oDoctorcenter->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Doctor Center"]));
        $this->urlComponents(config("businesslogic")[18]['menu'][4], $oResponse, config("businesslogic")[18]['title']);
        
        return $oResponse;
    }
    
    // Get soft deleted data
    public function deleted(Request $request,$doctor_id)
    {
        if (!Gate::allows('doctor-center-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);


        $oInput = $request->all();
        $doctor_id = decrypt($doctor_id);
        $oDoctor = AuthUserRoleChecker($doctor_id);
        
        $oQb = Center::where('doctor_id',$doctor_id)->onlyTrashed()->orderByDesc('deleted_at')->with(['createdBy','updatedBy','deletedBy','cityId','countryCode']);

        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"address",$oQb);
        $oQb = QB::where($oInput,"city_id",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::where($oInput,"is_primary",$oQb);

        $oCenter = $oQb->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',["mod"=>"Doctor Center"]), $oCenter, false);
        
        $this->urlComponents(config("businesslogic")[18]['menu'][5], $oResponse, config("businesslogic")[18]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {  
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:centers,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
        $allDoctorcenter= Center::onlyTrashed()->findOrFail($aIds);
        
        foreach($allDoctorcenter as $oRow)
            if (!Gate::allows('doctor-center-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oDoctorcenter = Center::onlyTrashed()->find($id);
                if($oDoctorcenter){
                    $oDoctorcenter->restore();
                }
            }
        }else{
            $oDoctorcenter = Center::onlyTrashed()->findOrFail($aIds);
            $oDoctorcenter->restore();
        }
        

        $oResponse = responseBuilder()->success(__('message.general.restore',["mod"=>"Doctor Center"]));
        
        $this->urlComponents(config("businesslogic")[18]['menu'][6], $oResponse, config("businesslogic")[18]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oDoctorcenter = Center::onlyTrashed()->findOrFail($id);
        
        if (!Gate::allows('doctor-center-delete',$oDoctorcenter))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oDoctorcenter->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',["mod"=>"Doctor Center"]));
        
        $this->urlComponents(config("businesslogic")[18]['menu'][7], $oResponse, config("businesslogic")[18]['title']);
        
        return $oResponse;
    }
    public function doctorCenter(Request $request, $doctor_id)
    {
        $oInput = $request->all();
        
        $doctor_id = decrypt($doctor_id);

        $oCenters = Center::where('is_active',1)->where('doctor_id',$doctor_id)->orderByDesc('updated_at')->select('id','name','doctor_id')->get();
       
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Doctor Center"]), $oCenters, false);
        
        return $oResponse;
    }
}
