<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Appointment;
use App\DoctorReceipt;
use App\Http\Controllers\Controller;

use App\Partnership;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\QB;
use App\Organization;
use App\PaymentSummary;
use App\PlanCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class PartnershipController extends Controller
{
    use \App\Traits\WebServicesDoc;
    use \App\Traits\DoctorSettlement;

    // get list of all the Partnerships
   
    public function index(Request $request)
    {
        if (!Gate::allows('partnership-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oAuth = Auth::user();
        $oInput = $request->all();
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;

        $oQb = Partnership::orderByDesc('is_active')->orderByDesc('updated_at')->with(['doctorId','planId','createdBy','updatedBy','deletedBy']);
        
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;
            
            $oQb = $oQb->whereHas('doctorId', function ($q) use($oInput) {
                $q->where('organization_id', $oInput['organization_id']);
            });
            
        }elseif ($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;    
        }
        if(isset($oInput['date_from']) && isset($oInput['date_to'])){
            $oValidator = Validator::make($oInput,[
                'date_from'       => 'date',
                'date_to'         => 'date|after_or_equal:date_from',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->where("date_from",'>=',$oInput['date_from'])->where("date_to",'<=',$oInput['date_to']);
        }
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"plan_id",$oQb);
        // $oQb = QB::whereBetween($oInput,"date_from",$oQb);
        $oQb = QB::whereLike($oInput,"doctor_settlement_frequency",$oQb);
        // $oQb = QB::whereBetween($oInput,"date_to",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::where($oInput,"is_renewable",$oQb);
        
        $aPartnerships = $oQb->paginate(20);
        
        $oResponse = responseBuilder()->success(__('message.general.list',['mod'=>'Partnership']), $aPartnerships, false);
        $this->urlComponents(config("businesslogic")[33]['menu'][0], $oResponse, config("businesslogic")[33]['title']);
        return $oResponse;
    }
    // Store new Partnership

    public function store(Request $request)
    {
        if (!Gate::allows('partnership-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false); 
        
        $oInput = $request->all();
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oValidator = Validator::make($oInput,[
            'is_active'       => 'required|in:0,1',
            'is_renewable'    => 'required|in:0,1',
            'date_from'       => 'required|date|after:yesterday',
            'date_to'         => 'required|date|after_or_equal:date_from',
            'doctor_id'       => 'required|exists:doctors,id',
            'plan_id'         => 'required|exists:plan_categories,id',
            'doctor_settlement_frequency' => 'required|in:week,biweek,month',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $alreadyPartnerShip = Partnership::where('doctor_id',$oInput['doctor_id'])->where('is_active',1)->first();
        if(isset($alreadyPartnerShip)){
            return responseBuilder()->error(__('message.general.already',['mod'=>'PartnerShip']), 403, false);
        }

        $oPartnership = Partnership::create([
            'is_active'         =>  $oInput['is_active'],
            'is_renewable'      =>  $oInput['is_renewable'],
            'date_from'         =>  $oInput['date_from'],
            'date_to'           =>  $oInput['date_to'],
            'doctor_id'         =>  $oInput['doctor_id'],
            'plan_id'           =>  $oInput['plan_id'],
            'created_by'        =>  Auth::user()->id,
            'updated_by'        =>  Auth::user()->id,
            'created_at'        =>  Carbon::now()->toDateTimeString(),
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
            'doctor_settlement_frequency' =>  $oInput['doctor_settlement_frequency'],
        ]);

        $oPartnership= Partnership::with(['doctorId','planId','createdBy','updatedBy','deletedBy'])->findOrFail($oPartnership->id);

        $oResponse = responseBuilder()->success(__('message.general.created',['mod'=>'Partnership']), $oPartnership, false);
        
        $this->urlComponents(config("businesslogic")[33]['menu'][1], $oResponse, config("businesslogic")[33]['title']);
       
        
        return $oResponse;
    }
    // Show Partnership details

    public function show($id)
    {
        $oAuth = Auth::user();
        
        $oQb = Partnership::with(['doctorId','planId','createdBy','updatedBy','deletedBy']);

        if($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;  
            $oQb = QB::where($oInput,"doctor_id",$oQb);
        }
        $oPartnership = $oQb->findOrFail($id);
     
        if (!Gate::allows('partnership-show', $oPartnership))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oResponse = responseBuilder()->success(__('message.general.detail',['mod'=>'Partnership']), $oPartnership, false);
        
        $this->urlComponents(config("businesslogic")[33]['menu'][2], $oResponse, config("businesslogic")[33]['title']);
        
        return $oResponse;
    }

    // Update Partnership details
    
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oValidator = Validator::make($oInput,[
            'is_active'       => 'required|in:0,1',
            'is_renewable'    => 'required|in:0,1',
            'date_from'       => 'required|date|after:yesterday',
            'date_to'         => 'required|date|after_or_equal:date_from',
            'doctor_id'       => 'required|exists:doctors,id',
            'plan_id'         => 'required|exists:plan_categories,id',
            'doctor_settlement_frequency' => 'required|in:week,biweek,month',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oPartnership = Partnership::findOrFail($id); 
        
        if (!Gate::allows('partnership-update',$oPartnership))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oPartnerships = $oPartnership->update([
            'is_active'         =>  $oInput['is_active'],
            'is_renewable'      =>  $oInput['is_renewable'],
            'date_from'         =>  $oInput['date_from'],
            'date_to'           =>  $oInput['date_to'],
            'doctor_id'         =>  $oInput['doctor_id'],
            'plan_id'           =>  $oInput['plan_id'],
            'updated_by'        =>  Auth::user()->id,
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
            'doctor_settlement_frequency' =>  $oInput['doctor_settlement_frequency'],
        ]);
        $oPartnership = Partnership::with(['doctorId','planId','createdBy','updatedBy','deletedBy'])->findOrFail($id);

        $oResponse = responseBuilder()->success(__('message.general.update',['mod'=>'Partnership']), $oPartnership, false);
        
        $this->urlComponents(config("businesslogic")[33]['menu'][3], $oResponse, config("businesslogic")[33]['title']);
        
        return $oResponse;
    }

    // Soft Delete Partnership 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:partnerships,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;
        
        $oAllPartnership = Partnership::findOrFail($aIds);
        
        foreach($oAllPartnership as $oRow)
            if (!Gate::allows('partnership-destroy', $oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oPartnership = Partnership::find($id);
                if($oPartnership){
                    $oPartnership->update(['deleted_by' => Auth::user()->id]);
                    $oPartnership->delete();
                }
            }
        }else{
            $oPartnership = Partnership::findOrFail($aIds);
            $oPartnership->update(['deleted_by' => Auth::user()->id]);
            $oPartnership->delete();
        }
        
        $oResponse = responseBuilder()->success(__('message.general.delete',['mod'=>'Partnership']));
        $this->urlComponents(config("businesslogic")[33]['menu'][4], $oResponse, config("businesslogic")[33]['title']);
        
        return $oResponse;
    }

    // Get soft deleted data
    public function deleted(Request $request)
    {
        if (!Gate::allows('partnership-deleted'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;

        $oQb = Partnership::onlyTrashed()->orderByDESC('deleted_at')->with(['doctorId','planId','createdBy','updatedBy','deletedBy']);
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"id",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"plan_id",$oQb);
        $oQb = QB::whereBetween($oInput,"date_from",$oQb);
        $oQb = QB::whereLike($oInput,"doctor_settlement_frequency",$oQb);
        $oQb = QB::whereBetween($oInput,"date_to",$oQb);
        $oQb = QB::where($oInput,"is_active",$oQb);
        $oQb = QB::where($oInput,"is_renewable",$oQb);
        $aPartnerships = $oQb->paginate(20);
        $oResponse = responseBuilder()->success(__('message.general.deletedList',['mod'=>'Partnership']), $aPartnerships, false);
        
        $this->urlComponents(config("businesslogic")[33]['menu'][5], $oResponse, config("businesslogic")[33]['title']);
        
        return $oResponse;
    }
    // Restore any deleted data
    public function restore(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:partnerships,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $aIds = $request->ids;
        
       $oAllPartnership =  Partnership::onlyTrashed()->findOrFail($aIds);
        
        foreach($oAllPartnership as $oRow)
            if (!Gate::allows('partnership-restore', $oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
                if(is_array($aIds)){
            foreach($aIds as $id){
                
                $oPartnership = Partnership::onlyTrashed()->find($id);
                if($oPartnership){
                    $oPartnership->restore();
                }
            }
        }else{
            $oPartnership = Partnership::onlyTrashed()->findOrFail($aIds);
            $oPartnership->restore();
        } 
        $oResponse = responseBuilder()->success(__('message.general.restore',['mod'=>'Partnership']));
        
        $this->urlComponents(config("businesslogic")[33]['menu'][6], $oResponse, config("businesslogic")[33]['title']);
        
        return $oResponse;
    }
    // Permanent Delete
    public function delete($id)
    {
        $oPartnership = Partnership::onlyTrashed()->with(['doctorId','planId','createdBy','updatedBy','deletedBy'])->findOrFail($id);
        
        if (!Gate::allows('partnership-delete',$oPartnership))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);   
        
        $oPartnership->forceDelete();
        
        $oResponse = responseBuilder()->success(__('message.general.permanentDelete',['mod'=>'Partnership']));
        
        $this->urlComponents(config("businesslogic")[33]['menu'][7], $oResponse, config("businesslogic")[33]['title']);
        
        return $oResponse;
    }
    // Renew Partnership
    
    public function renewPartnerShip(Request $request)
    {
        if (!Gate::allows('partnership-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false); 
        
        $oInput = $request->all();
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])? decrypt($oInput['doctor_id']) : null;
        
        $oValidator = Validator::make($oInput,[
            // 'is_active'       => 'required|in:0,1',
            // 'is_renewable'    => 'required|in:0,1',
            'date_from'       => 'required|date|after:yesterday',
            'date_to'         => 'required|date|after_or_equal:date_from',
            'doctor_id'       => 'required|exists:doctors,id',
            'plan_id'         => 'required|exists:plan_categories,id',
            'doctor_settlement_frequency' => 'required|in:week,biweek,month',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $alreadyPartnerShip = Partnership::where('doctor_id',$oInput['doctor_id'])->where('is_active',1)->first();
        
        if(isset($alreadyPartnerShip)){
           
            $plan = PlanCategory::where('id',$alreadyPartnerShip->plan_id)->first();
            $oAppointments = Appointment::where('doctor_id',$oInput['doctor_id'])->where('paid_status','paid')->whereIn('status',['done','ongoing','follow_up','reschedual'])->where('is_settled',0)->get();
            
            if(count($oAppointments)>0){
                $total_commission = 0;
                $actual_amount      = collect($oAppointments)->sum('appointment_fee');
                $total_appointments = count($oAppointments);
                
                if($plan->fix_percentage_fee == 'P'){
                    $total_commission = ($plan->fee/100)*$actual_amount;
                }else{
                    $total_commission = $plan->fee*$total_appointments;
                }

                $system_amount = $actual_amount - $total_commission;
                
                $perviousPaymentSummary = PaymentSummary::where('doctor_id',$alreadyPartnerShip->doctor_id)->where('paid','in_progress')->latest()->first();
                
                $payment_summary = PaymentSummary::create([
                    'system_amount'   => $system_amount,
                    'doctor_id'       => $alreadyPartnerShip->doctor_id,
                    'actual_amount'   => $actual_amount,
                    'total_commission'=> $total_commission,
                    'outstanding'     => isset($perviousPaymentSummary)?$perviousPaymentSummary->system_amount:0,
                    'created_at'      => Carbon::now()->toDateTimeString(),
                ]);

                foreach ($oAppointments as $oAppointment) {
                    
                    if($plan->fix_percentage_fee == 'P'){
                        $service_charges = ($plan->fee/100)*$oAppointment->appointment_fee;
                    }else{
                        $service_charges = $plan->fee;
                    }

                    $doctor_receipt = DoctorReceipt::create([
                        'doctor_id'      => $alreadyPartnerShip->doctor_id,
                        'appointment_id' => $oAppointment->id,
                        'amount'         => $oAppointment->appointment_fee,
                        'summary_id'     => $payment_summary->id,
                        'service_charges'=> $service_charges,
                    ]);

                    $oAppointment->update(['is_settled' => 1]);
                }
                $this->doctorPaymentSettlementEmail($alreadyPartnerShip, $payment_summary);
            }

            $alreadyPartnerShip->update([
                'is_active' =>  0,
            ]);

        }else{
            return responseBuilder()->error(__('Doctor is not allowed to renew has partnership'), 403, false);
        }

        $oPartnership = Partnership::create([
            'is_active'         =>  1,
            // 'is_active'         =>  $oInput['is_active'],
            // 'is_renewable'      =>  $oInput['is_renewable'],
            'is_renewable'      =>  1,
            'date_from'         =>  $oInput['date_from'],
            'date_to'           =>  $oInput['date_to'],
            'doctor_id'         =>  $oInput['doctor_id'],
            'plan_id'           =>  $oInput['plan_id'],
            'created_by'        =>  Auth::user()->id,
            'updated_by'        =>  Auth::user()->id,
            'created_at'        =>  Carbon::now()->toDateTimeString(),
            'updated_at'        =>  Carbon::now()->toDateTimeString(),
            'doctor_settlement_frequency' =>  $oInput['doctor_settlement_frequency'],
        ]);

        $oPartnership= Partnership::with(['doctorId','planId','createdBy','updatedBy','deletedBy'])->findOrFail($oPartnership->id);

        $oResponse = responseBuilder()->success(__('message.general.created',['mod'=>'Partnership']), $oPartnership, false);
        
        $this->urlComponents(config("businesslogic")[33]['menu'][8], $oResponse, config("businesslogic")[33]['title']);

        return $oResponse;
    }
    public function doctorPartnerShip($doctor_id)
    {
        $doctor_id = isset($doctor_id)? decrypt($doctor_id): null;
        $oInput['doctor_id'] = isset($doctor_id)? $doctor_id :null;
        $oValidator = Validator::make($oInput,[
            'doctor_id'         => 'required|exists:doctors,id',
        ]);
        
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oPartnership = Partnership::where('is_active',1)->where('doctor_id',$doctor_id)->with(['doctorId','planId','createdBy','updatedBy','deletedBy'])->first();
        
        $oResponse = responseBuilder()->success(__('message.general.list',['mod'=>'Partnership']), $oPartnership, false);
        
        return $oResponse;
    }
}
