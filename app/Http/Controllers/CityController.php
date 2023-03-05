<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Helpers\QB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CityController extends Controller
{
    use \App\Traits\WebServicesDoc;

    // get list of all the Cities
   
    public function index(Request $request)
    {
        $oInput = $request->all();

        $oQb = City::with(['countryCode']);
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"country_code",$oQb);
        
        $oCities = $oQb->get();
        
        $oResponse = responseBuilder()->success(__('message.city.list'), $oCities, false);
        $this->urlComponents(config("businesslogic")[4]['menu'][0], $oResponse, config("businesslogic")[4]['title']);
        
        return $oResponse;
    }

    // create new city
   
    public function create(Request $request)
    {
        
    }

    // Store new city

    public function store(Request $request)
    {
        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'name'         => 'required|max:50',
            'country_code' => 'required',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oCity = City::create([
            'name'          =>  $oInput['name'],
            'country_code'  =>  $oInput['country_code'],
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);

        $oCity= City::with(['countryCode'])->findOrFail($oCity->id);

        $oResponse = responseBuilder()->success(__('message.city.create'), $oCity, false);
        
        $this->urlComponents(config("businesslogic")[4]['menu'][1], $oResponse, config("businesslogic")[4]['title']);
       
        
        return $oResponse;
    }
    // Show city details

    public function show($id)
    {

        $oCity= City::with(['countryCode'])->findOrFail($id);

        $oResponse = responseBuilder()->success(__('message.city.detail'), $oCity, false);
        
        $this->urlComponents(config("businesslogic")[4]['menu'][2], $oResponse, config("businesslogic")[4]['title']);
        
        return $oResponse;
    }

    public function edit(City $oCity)
    {
        //
    } 

    // Update city details
    
    public function update(Request $request, $id)
    {
        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'name'          => 'required|max:50',
            'country_code'  => 'required',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oCity = City::findOrFail($id); 

        $oCitys = $oCity->update([
            'name'          =>  $oInput['name'],
            'country_code'  =>  $oInput['country_code'],
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);
        $oCity = City::with(['countryCode'])->findOrFail($id);

        $oResponse = responseBuilder()->success(__('message.city.update'), $oCity, false);
        
        $this->urlComponents(config("businesslogic")[4]['menu'][3], $oResponse, config("businesslogic")[4]['title']);
        
        return $oResponse;
    }

    // Soft Delete city 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids'        => 'required',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oCity = City::find($id);
                if($oCity){
                    $oCity->delete();
                }
            }
        }else{
            $oCity = City::findOrFail($aIds);
            $oCity->delete();
        }
        
        $oResponse = responseBuilder()->success(__('message.city.delete'));
        $this->urlComponents(config("businesslogic")[4]['menu'][4], $oResponse, config("businesslogic")[4]['title']);
        
        return $oResponse;
    }

    // Get soft deleted data
    public function deletedCity()
    {
        $oCity = City::onlyTrashed()->with(['countryCode'])->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.city.deletedList'), $oCity, false);
        
        $this->urlComponents(config("businesslogic")[4]['menu'][5], $oResponse, config("businesslogic")[4]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restoreCity(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids'        => 'required',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oCity = City::onlyTrashed()->with(['countryCode'])->find($id);
                if($oCity){
                    $oCity->restore();
                }
            }
        }else{
            $oCity = City::onlyTrashed()->with(['countryCode'])->findOrFail($aIds);
            $oCity->restore();
        } 
        $oResponse = responseBuilder()->success(__('message.city.restore'));
        
        $this->urlComponents(config("businesslogic")[4]['menu'][6], $oResponse, config("businesslogic")[4]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function deleteCity($id)
    {
        $oCity = City::onlyTrashed()->with(['countryCode'])->findOrFail($id);
        
        $oCity->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.city.permanentDelete'));
        
        $this->urlComponents(config("businesslogic")[4]['menu'][7], $oResponse, config("businesslogic")[4]['title']);
        
        return $oResponse;
    }
}
