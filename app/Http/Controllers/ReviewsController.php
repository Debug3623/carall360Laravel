<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Doctor;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Helpers\QB;
use App\Patient;
use App\Review;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReviewsController extends Controller
{
    use \App\Traits\WebServicesDoc;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        if (!Gate::allows('review-index') && !Gate::allows('patient-review-index'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        $oInput = $request->all();
        $oAuth = Auth::user();
        
        $oInput['doctor_id'] = isset($oInput['doctor_id'])?decrypt($oInput['doctor_id']):null;
        $oInput['patient_id'] = isset($oInput['patient_id'])?decrypt($oInput['patient_id']):null;
        
        $oQb = Review::orderByDesc('updated_at')->with(['appointmentId','responseBy']);

        $oQb = QB::where($oInput,"id",$oQb);
        if($oAuth->isClient()){
            $oInput['organization_id'] = $oAuth->organization_id;
            
            $oQb = $oQb->whereHas('doctorId', function ($q) use($oInput) {
                $q->where('organization_id', $oInput['organization_id']);
            });
        }elseif ($oAuth->isDoctor()) {
            $oInput['doctor_id'] = $oAuth->doctor_id;    
        }
        // elseif ($oAuth->isPatient()) {
        //     $oInput['patient_id'] = $oAuth->patient_id;    
        // }
        $oQb = QB::filters($oInput,$oQb);
        $oQb = QB::where($oInput,"status",$oQb);
        $oQb = QB::where($oInput,"doctor_id",$oQb);
        $oQb = QB::where($oInput,"patient_id",$oQb);
        $oQb = QB::where($oInput,"appointment_id",$oQb);
        
        $oReviews = $oQb->paginate(10);

        foreach ($oReviews as $oReview) {
            $patient      = Patient::where('id',$oReview->patient_id)->first();
            $doctor       = Doctor::where('id',$oReview->doctor_id)->first();
            $oPatientUser = User::where('patient_id',$oReview->patient_id)->first();
            $oDoctorUser  = User::where('doctor_id',$oReview->doctor_id)->whereNull('patient_id')->where('organization_id',$doctor->organization_id)->first();
            
            if($oPatientUser){
                $patient['image'] = (count($oPatientUser->profilePic)>0)? config("app")['url'].$oPatientUser->profilePic[0]->url:null;
            }
            if($oDoctorUser){
                $doctor['image'] = (count($oDoctorUser->profilePic)>0)? config("app")['url'].$oDoctorUser->profilePic[0]->url:null;
            }
            $oReview['patient_id'] = $patient;
            $oReview['doctor_id'] = $doctor;
        }
        $oResponse = responseBuilder()->success(__('message.general.list',["mod"=>"Appointment Review"]), $oReviews, false);
        $this->urlComponents(config("businesslogic")[28]['menu'][0], $oResponse, config("businesslogic")[28]['title']);
        return $oResponse;
    }

    public function store(Request $request)
    {
        if (!Gate::allows('review-store') && !Gate::allows('patient-review-store'))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oInput = $request->all();
        $oInput['patient_id'] = decrypt($oInput['patient_id']);
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'comments'      => 'required|max:250',
            'review_star'   => 'required|max:5|min:0',
            'status'        => 'required|in:pending,approved,cancel',
            'doctor_id'     => 'required|exists:doctors,id',
            'patient_id'    => 'required|exists:patients,id',
            'appointment_id'=> 'required|exists:appointments,id',
        ]);

        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oAppointment = Appointment::where('id',$oInput['appointment_id'])->where('patient_id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oAppointment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
        
        if($oAppointment->is_reviewed == 1)
            return responseBuilder()->error(__('You already created review'), 404, false);
        
        $oReview = Review::create([
            'patient_id'    => $oInput['patient_id'],
            'appointment_id'=> $oInput['appointment_id'],
            'doctor_id'     => $oInput['doctor_id'],
            'review_star'   => $oInput['review_star'],
            'comments'      => $oInput['comments'],
            'status'        => $oInput['status'],
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);

        $oAppointment->update([
            "is_reviewed" => 1
        ]);

        $oReview= Review::with(['appointmentId','patientId','doctorId','responseBy'])->findOrFail($oReview->id);
        
        $oResponse = responseBuilder()->success(__('message.general.created',["mod"=>"Appointment Review"]), $oReview, false);
        
        $this->urlComponents(config("businesslogic")[28]['menu'][1], $oResponse, config("businesslogic")[28]['title']);
        
        return $oResponse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Review  $Review
     * @return \Illuminate\Http\Response
     */
    public function show($Review_id)
    {
        $oReview = Review::with(['appointmentId','responseBy'])->findOrFail($Review_id);
        
        if (!Gate::allows('review-show',$oReview) && !Gate::allows('patient-review-show',$oReview))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $patient      = Patient::where('id',$oReview->patient_id)->first();
        $doctor       = Doctor::where('id',$oReview->doctor_id)->first();
        $oPatientUser = User::where('patient_id',$oReview->patient_id)->first();
        $oDoctorUser  = User::where('doctor_id',$oReview->doctor_id)->whereNull('patient_id')->where('organization_id',$doctor->organization_id)->first();
            
        if($oPatientUser){
            $patient['image'] = (count($oPatientUser->profilePic)>0)? config("app")['url'].$oPatientUser->profilePic[0]->url:null;
        }
        if($oDoctorUser){
            $doctor['image'] = (count($oDoctorUser->profilePic)>0)? config("app")['url'].$oDoctorUser->profilePic[0]->url:null;
        }
        $oReview['patient_id'] = $patient;
        $oReview['doctor_id'] = $doctor;
        $oResponse = responseBuilder()->success(__('message.general.detail',["mod"=>"Appointment Review"]), $oReview, false);
        
        $this->urlComponents(config("businesslogic")[28]['menu'][2], $oResponse, config("businesslogic")[28]['title']);
        
        return $oResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Review  $Review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $Review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Review  $Review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oInput = $request->all();
        $oInput['patient_id'] = decrypt($oInput['patient_id']);
        $oInput['doctor_id'] = decrypt($oInput['doctor_id']);
        $oValidator = Validator::make($oInput,[
            'comments'      => 'required|max:250',
            'review_star'   => 'required|max:5|min:0',
            'status'        => 'required|in:pending,approved,cancel',
            'doctor_id'     => 'required|exists:doctors,id',
            'patient_id'    => 'required|exists:patients,id',
            'appointment_id'=> 'required|exists:appointments,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        $oAppointment = Appointment::where('id',$oInput['appointment_id'])->where('patient_id',$oInput['patient_id'])->where('doctor_id',$oInput['doctor_id'])->first();
        if(!isset($oAppointment))
            return responseBuilder()->error(__('message.general.notFind'), 404, false);
        
        $oReview = Review::with(['appointmentId','patientId','doctorId','responseBy'])->findOrFail($id);
        
        if (!Gate::allows('review-update',$oReview) && !Gate::allows('patient-review-update',$oReview))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oReview = $oReview->update([
            'patient_id'    => $oInput['patient_id'],
            'appointment_id'=> $oInput['appointment_id'],
            'doctor_id'     => $oInput['doctor_id'],
            'review_star'   => $oInput['review_star'],
            'comments'      => $oInput['comments'],
            'status'        => $oInput['status'],
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);

        $oAppointment = Appointment::where('id',$oInput['appointment_id'])->first();
        $oAppointment->update([
            "is_reviewed" => 1
        ]);
        $oReview = Review::with(['appointmentId','patientId','doctorId','responseBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Appointment Review"]), $oReview, false);
        
        $this->urlComponents(config("businesslogic")[28]['menu'][3], $oResponse, config("businesslogic")[28]['title']);
        
        return $oResponse;
    }

    // Soft Delete patients 

    public function destroy(Request $request)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'ids' => 'required|array',
            'ids.*' => 'exists:reviews,id',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }
        
        $aIds = $request->ids;

        $allReview = Review::findOrFail($aIds);
        
        foreach($allReview as $oRow)
            if (!Gate::allows('review-destroy',$oRow))
                return responseBuilder()->error(__('auth.not_authorized'), 403, false);
        
        if(is_array($aIds)){
            foreach($aIds as $id){
                $oReview = Review::find($id);
                if($oReview){
                    $oAppointment = Appointment::where('id',$oReview->appointment_id)->first();
                    $oAppointment->update([
                        "is_reviewed" => 0
                    ]);
                    $oReview->delete();
                }
            }
        }else{
            $oReview = Review::findOrFail($aIds);
            $oReview->delete();
        }
        $oResponse = responseBuilder()->success(__('message.general.delete',["mod"=>"Appointment Review"]));
        $this->urlComponents(config("businesslogic")[28]['menu'][4], $oResponse, config("businesslogic")[28]['title']);
        
        return $oResponse;
    }
    public function updateStatus(Request $request, $id)
    {
        $oInput = $request->all();
        $oValidator = Validator::make($oInput,[
            'status'          => 'required|in:pending,approved,cancel',
            'response_remarks'=> 'present|nullable|max:250',
        ]);
        if($oValidator->fails()){
            abort(400,$oValidator->errors()->first());
        }

        $oReview = Review::findOrFail($id);
        
        if (!Gate::allows('review-status-update',$oReview))
            return responseBuilder()->error(__('auth.not_authorized'), 403, false);

        $oReview = $oReview->update([
            'status'        => $oInput['status'],
            'response_remarks'        => $oInput['response_remarks'],
            'response_by'   => Auth::user()->id,
            'updated_at'    => Carbon::now()->toDateTimeString(),
        ]);
        $oReview = Review::with(['appointmentId','patientId','doctorId','responseBy'])->findOrFail($id);
        
        $oResponse = responseBuilder()->success(__('message.general.update',["mod"=>"Appointment Review"]), $oReview, false);
        
        $this->urlComponents(config("businesslogic")[28]['menu'][5], $oResponse, config("businesslogic")[28]['title']);
        
        return $oResponse;
    }
}
