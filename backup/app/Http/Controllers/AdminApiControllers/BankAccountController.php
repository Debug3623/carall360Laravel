<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\BankAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\QB;
use Illuminate\Support\Facades\Gate;


class BankAccountController extends Controller
{
     use \App\Traits\WebServicesDoc;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $oInput = $request->all();
        if (!Gate::allows('bankaccount-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oQb = BankAccount::orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"organization_id",$oQb);
        $oQb = QB::whereLike($oInput,"bank_name",$oQb);
        $oQb = QB::whereLike($oInput,"branch_code",$oQb);
        $oQb = QB::whereLike($oInput,"iban",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::whereLike($oInput,"account_number",$oQb);
        
        $oBankAccount = $oQb->paginate(20);

        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Bank Account"]), $oBankAccount, false);
        $this->urlComponents(config("businesslogic")[27]['menu'][0], $oResponse, config("businesslogic")[27]['title']);
        return $oResponse;

    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('bankaccount-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|unique:bank_accounts|max:50',
            'iban'           => 'present|nullable|max:50',
            'branch_code'    => 'present|nullable|max:10',
            'organization_id'=> 'present|nullable|exists:organizations,id',
            'is_active'      => 'required|in:0,1',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oBankAccount = BankAccount::create([
            'bank_name'       =>  $oInput['bank_name'],
            'account_number'  =>  $oInput['account_number'],
            'iban'            =>  isset($oInput['iban'])?$oInput['iban']:null,
            'branch_code'     =>  isset($oInput['branch_code'])?$oInput['branch_code']:null,
            'organization_id' =>  isset($oInput['organization_id'])?$oInput['organization_id']:null,
            'is_active'       =>  $oInput['is_active'],
            'created_by'      =>  Auth::user()->id,
            'updated_by'      =>  Auth::user()->id,
            'created_at'      =>  Carbon::now()->toDateTimeString(),
            'updated_at'      =>  Carbon::now()->toDateTimeString(),
        ]);

        $oBankAccounts = BankAccount::with(['createdBy','updatedBy'])->findOrFail($oBankAccount->id);

        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Bank Account"]), $oBankAccounts, false);        
        $this->urlComponents(config("businesslogic")[27]['menu'][1], $oResponse, config("businesslogic")[27]['title']);
        return $oResponse;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $oBankaccount= BankAccount::with(['createdBy','updatedBy'])->findOrFail($id);

         if (!Gate::allows('bankaccount-show', $oBankaccount))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Bank Account"]), $oBankaccount, false);        
            
        $this->urlComponents(config("businesslogic")[27]['menu'][2], $oResponse, config("businesslogic")[2]['title']);
        
        return $oResponse;
    }

    public function update(Request $request, $id)
    {
        $oInput = $request->all();

        $oValidator = Validator::make($oInput,[
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'iban'           => 'present|nullable|max:50',
            'branch_code'    => 'present|nullable|max:10',
            'organization_id'=> 'present|nullable|exists:organizations,id',
            'is_active'      => 'required|in:0,1',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oBankAccount = BankAccount::where('id','!=',$id)->where('account_number',$oInput['account_number'])->first();
        if(isset($oBankAccount)){
            return responseBuilder()->error(__('message.general.already',['mod'=>'Bank Account']), 404, false);
        }
        $oBankAccount = BankAccount::findOrFail($id);
        
        if (!Gate::allows('bankaccount-update',$oBankAccount))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oBankAccount = $oBankAccount->update([
            'bank_name'       =>  $oInput['bank_name'],
            'account_number'  =>  $oInput['account_number'],
            'iban'            =>  isset($oInput['iban'])?$oInput['iban']:null,
            'branch_code'     =>  isset($oInput['branch_code'])?$oInput['branch_code']:null,
            'organization_id' =>  isset($oInput['organization_id'])?$oInput['organization_id']:null,
            'is_active'       =>  $oInput['is_active'],
            'updated_by'      =>  Auth::user()->id,
            'updated_at'      =>  Carbon::now()->toDateTimeString(),
        ]);

        $oBankAccounts = BankAccount::with(['createdBy','updatedBy'])->findOrFail($id);

        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Bank Account"]), $oBankAccounts, false);        
        $this->urlComponents(config("businesslogic")[27]['menu'][3], $oResponse, config("businesslogic")[27]['title']);
        return $oResponse;
    }

    // Soft Delete Bank Account 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:bank_accounts,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        $allBankAccount = BankAccount::findOrFail($aIds);
        
        foreach($allBankAccount as $oRow)
            if (!Gate::allows('bankaccount-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        if(is_array($aIds)){
            foreach($aIds as $id){
                $oBankAccount = BankAccount::find($id);
                if($oBankAccount){
                    $oBankAccount->delete();
                }
            }
        }else{
            $oBankAccount = BankAccount::findOrFail($aIds);
            $oBankAccount->delete();
        }
        
        $oResponse = responseBuilder()->success(__('message.general.delete',['mod'=>"Bank Account"]));
        $this->urlComponents(config("businesslogic")[27]['menu'][4], $oResponse, config("businesslogic")[27]['title']);
        
        return $oResponse;
    }

    // Get soft deleted data
    public function deleted()
    {
        if (!Gate::allows('bankaccount-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oBankAccount = BankAccount::onlyTrashed()->with(['createdBy','updatedBy'])->paginate(10);
        
        $oResponse = responseBuilder()->success(__('message.general.deletedList',['mod'=>"Bank Account"]), $oBankAccount, false);
        
        $this->urlComponents(config("businesslogic")[27]['menu'][5], $oResponse, config("businesslogic")[27]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:bank_accounts,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        $allBankAccount = BankAccount::onlyTrashed()->findOrFail($aIds);
        foreach($allBankAccount as $oRow)
            if (!Gate::allows('bankaccount-restore',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oBankAccount = BankAccount::onlyTrashed()->find($id);
                if($oBankAccount){
                    $oBankAccount->restore();
                }
            }
        }else{
            $oBankAccount = BankAccount::onlyTrashed()->findOrFail($aIds);
            $oBankAccount->restore();
        } 
        $oResponse = responseBuilder()->success(__('message.general.restore',['mod'=>"Bank Account"]));
        
        $this->urlComponents(config("businesslogic")[27]['menu'][6], $oResponse, config("businesslogic")[27]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        
        $oBankAccount = BankAccount::onlyTrashed()->findOrFail($id);
        if (!Gate::allows('bankaccount-delete',$oBankAccount))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oBankAccount->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',['mod'=>"Bank Account"]));
        
        $this->urlComponents(config("businesslogic")[27]['menu'][7], $oResponse, config("businesslogic")[27]['title']);
        
        return $oResponse;
    }
    public function landingPageAccount(Request $request)
    {
        //
        $oInput = $request->all();
        $oQb = BankAccount::where('is_active',1)->orderByDesc('updated_at')->with(['createdBy','updatedBy','deletedBy']);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"organization_id",$oQb);
        $oQb = QB::whereLike($oInput,"bank_name",$oQb);
        $oQb = QB::whereLike($oInput,"account_number",$oQb);
        
        $oBankAccount = $oQb->paginate(20);

        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Bank Account"]), $oBankAccount, false);
        
        return $oResponse;

    }
}
