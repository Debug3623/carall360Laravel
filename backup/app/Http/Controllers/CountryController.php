<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Helpers\QB;
use Illuminate\Support\Facades\Validator;


class CountryController extends Controller
{
    use \App\Traits\WebServicesDoc;

    // get list of all the Cities
   
    public function index(Request $request)
    {
        $oInput = $request->all();

        $oQb = Country::with(['city']);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::whereLike($oInput,"name",$oQb);
        $oQb = QB::whereLike($oInput,"code",$oQb);
        $oQb = QB::whereLike($oInput,"numcode",$oQb);
        $oQb = QB::whereLike($oInput,"phonecode",$oQb);
        
        $oCountries = $oQb->get();
        
        $oResponse = responseBuilder()->success(__('message.country.list'), $oCountries, false);
        $this->urlComponents(config("businesslogic")[5]['menu'][0], $oResponse, config("businesslogic")[5]['title']);
        
        return $oResponse;
    }

    // create new country
   
    public function create(Request $request)
    {
        
    }

    // Store new country

    public function store(Request $request)
    {
        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'name'   => 'required|max:50',
            'code'   => 'required',
            'phonecode'=> 'required',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oCountry = Country::create([
            'name'          =>  $oInput['name'],
            'code'          =>  $oInput['code'],
            'phonecode'     =>  $oInput['phonecode'],
            'created_at'    =>  Carbon::now()->toDateTimeString(),
            'updated_at'    =>  Carbon::now()->toDateTimeString(),
        ]);

        $oCountry= Country::with(['city'])->findOrFail($oCountry->id);

        $oResponse = responseBuilder()->success(__('message.country.create'), $oCountry, false);
        
        $this->urlComponents(config("businesslogic")[5]['menu'][1], $oResponse, config("businesslogic")[5]['title']);
       
        
        return $oResponse;
    }
    // Show country details

    public function show($id)
    {

        $oCountry= Country::with(['city'])->findOrFail($id);

        $oResponse = responseBuilder()->success(__('message.country.detail'), $oCountry, false);
        
        $this->urlComponents(config("businesslogic")[5]['menu'][2], $oResponse, config("businesslogic")[5]['title']);
        
        return $oResponse;
    }

    public function edit(country $oCountry)
    {
        //
    } 

    // Update country details
    
    public function update(Request $request, $id)
    {
        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'name'      => 'required|max:50',
            'code'      => 'required',
            'phonecode' => 'required',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oCountry = Country::findOrFail($id); 

        $oCountrys = $oCountry->update([
            'name'      =>  $oInput['name'],
            'code'      =>  $oInput['code'],
            'phonecode' =>  $oInput['phonecode'],
            'updated_at'=>  Carbon::now()->toDateTimeString(),
        ]);
        $oCountry = Country::with(['city'])->findOrFail($id);

        $oResponse = responseBuilder()->success(__('message.country.update'), $oCountry, false);
        
        $this->urlComponents(config("businesslogic")[5]['menu'][3], $oResponse, config("businesslogic")[5]['title']);
        
        return $oResponse;
    }

    // Soft Delete country 

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
                $oCountry = Country::find($id);
                if($oCountry){
                    $oCountry->delete();
                }
            }
        }else{
            $oCountry = Country::findOrFail($aIds);
            $oCountry->delete();
        }
        
        $oResponse = responseBuilder()->success(__('message.country.delete'));
        $this->urlComponents(config("businesslogic")[5]['menu'][4], $oResponse, config("businesslogic")[5]['title']);
        
        return $oResponse;
    }

    // Get soft deleted data
    public function deletedCountry()
    {
        $oCountry = Country::onlyTrashed()->with(['city'])->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.country.deletedList'), $oCountry, false);
        
        $this->urlComponents(config("businesslogic")[5]['menu'][5], $oResponse, config("businesslogic")[5]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restoreCountry(Request $request)
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
                
                $oCountry = Country::onlyTrashed()->with(['city'])->find($id);
                if($oCountry){
                    $oCountry->restore();
                }
            }
        }else{
            $oCountry = Country::onlyTrashed()->with(['city'])->findOrFail($aIds);
            $oCountry->restore();
        } 
        $oResponse = responseBuilder()->success(__('message.country.restore'));
        
        $this->urlComponents(config("businesslogic")[5]['menu'][6], $oResponse, config("businesslogic")[5]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function deleteCountry($id)
    {
        $oCountry = Country::onlyTrashed()->with(['city'])->findOrFail($id);
        
        $oCountry->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.country.permanentDelete'));
        
        $this->urlComponents(config("businesslogic")[5]['menu'][7], $oResponse, config("businesslogic")[5]['title']);
        
        return $oResponse;
    }
}
