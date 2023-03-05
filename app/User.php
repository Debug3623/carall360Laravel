<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

use Illuminate\Support\Str;

use Carbon\Carbon;
use Auth;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    public function getPatientIdAttribute($value){
        return $value;
        //return encrypt($value);
    }

    public function getPtAttribute(){
        //dump($this->patient_id);
        //dump(decrypt($this->patient_id));
        return $this->patient_id;
    }
    */



    public function doctor(){
        return $this->hasOne('App\Doctor', 'pk', 'doctor_id');
    }

    public function patient(){
        ///return $this->belongsTo('App\Patient', 'patient_id', 'pk');
        return $this->hasOne('App\Patient', 'pk', 'patient_id');
    }

    public function media(){
        return $this->hasMany('App\Media');
    }

    public function profilePic(){
        return $this->hasMany('App\Media')->where('type', 'profile')->latest();
    }

    public function bannerPic(){
        return $this->hasMany('App\Media')->where('type', 'banner')->latest();
    }

    public function createdby(){
        return $this->hasOne('App\User', 'id', 'created_by')->select('id','name','phone');
    }
    public function deletedBy(){
        return $this->hasOne('App\User', 'id', 'deleted_by')->select('id','name','phone');
    }
    public function updatedby(){
        return $this->hasOne('App\User', 'id', 'updated_by')->select('id','name','phone');
    }
    public function restoredBy(){
        return $this->belongsTo('App\User','restored_by','id')->select('id','name','phone');
    }
    public function organization(){
        return $this->hasOne('App\Organization', 'id', 'organization_id');
    }
    
    public function roles(){
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }

    public function hasEmailAddress(){
        if(!is_null($this->email))
            return true;
        return false;
    }


    /**
     * To get which type of actor is login to system, this function return the
     * actor type as follows;
     *  DOCTOR - Doctor
     *  PATIENT - Patient
     *  STAFF - Backend Staff (for admin panel including the organization admins)
     */
    public function getActor(){

        if($this->isDoctor())
            return 'DOCTOR';
        if($this->isPatient())
            return 'PATIENT';
        return 'STAFF';

    }


    /**
     * If looged in user is doctor then return true, else false
     */
    public function isDoctor(){

        $aMyRoles = $this->roles->toArray();
        foreach($aMyRoles as $oRow)
            if(strtoupper($oRow['name']) == 'DOCTOR')
                return true;

        return false;
    }

    /**
     * If logged in user is patient then then return true else wlways false
     */
    public function isPatient(){

        $aMyRoles = $this->roles->toArray();
        foreach($aMyRoles as $oRow)
            if(strtoupper($oRow['name']) == 'PATIENT')
                return true;

        return false;
    }

    /**
     * For all the staff (back office including admin)
     * This is for group of roles EXCLUDING doctor and patient
     */
    public function isStaff(){
        if($this->isPatient())
            return false;
        if($this->isDoctor())
            return false;
        return true;
    }

    /**
     * This role for client organization users
     */
    public function isClient(){
        $aMyRoles = $this->roles->toArray();
        foreach($aMyRoles as $oRow)
            if(strtoupper($oRow['name']) == 'ORGANIZATION')
                return true;

        return false;
    }

    /**
     * This function returns the profile associated with the actor being logged in
     * Function used the relationship associated
     */
    public function getProfile($sActor){

        
        if($sActor == 'DOCTOR'){
            $doctor = $this->doctor;
            $doctor['name'] = $doctor->title.' '.$doctor->full_name;
            $oUser = auth()->user();
            if($oUser){
                $doctor['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
                $doctor['banner'] = (count($oUser->bannerPic)>0)? config("app")['url'].$oUser->bannerPic[0]->url:null;
            }else{
                $doctor['image'] = null;
                $doctor['banner'] = null;
            }
            $doctor['uid'] = auth()->user()->id;
            return $doctor;
        }
        if($sActor == 'PATIENT'){
            $patient =  $this->patient;
            $oDoctor = Doctor::where('id',$patient->doctor_id)->select('id','full_name','title','phone','url')->first();
            $oDoctorUser = User::where('doctor_id',$patient->doctor_id)->whereNull('patient_id')->whereNotNull('organization_id')->first();
            if($oDoctorUser){
                $oDoctor['image'] = (count($oDoctorUser->profilePic) >0 )? config("app")['url'].$oDoctorUser->profilePic[0]->url: null;
            }else{
                $oDoctor['image'] = null;
                
            }
            $oDoctor['user'] = $oDoctorUser;
            $patient['doctor'] = $oDoctor;
            $patient['uid'] = auth()->user()->id;
            $oUser = auth()->user();
            if($oUser){
                $patient['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
            }else{
                $patient['image'] = null;
            }
            return $patient;
        }

        $oUser = auth()->user();
        
        $oUser['image'] = (count($oUser->profilePic)>0)? config("app")['url'].$oUser->profilePic[0]->url:null;
        return $oUser;
    }

    
    /**
     * Returns true if logged in user is active 
     * else false otherwise
     */
    public function isUserActive(){

        if($this->is_active == 1)
            return true;
        return false;
    }


    /**
     * If user is soft deleted, than return true else false
     */
    public function isSoftDeleted(){
        if(!is_null($this->deleted_at))
            return true;
        return false;
    }


    /**
     * Have full control and access on system - works as Power User
     */
    public function isSuperAdmin(){

        $aMyRoles = $this->roles->toArray();
        //dd($aMyRoles);
        foreach($aMyRoles as $oRow)
            if(strtoupper($oRow['name']) == 'SUPER_ADMIN')
                return true;

        return false;
    }

    /**
     * If logged in user is Admin of system
     */
    public function isAdmin(){

        $aMyRoles = $this->roles->toArray();
        foreach($aMyRoles as $oRow)
            if(strtoupper($oRow['name']) == 'ADMIN')
                return true;

        return false;
    }


    /**
     * returns true if logged in user is call agent of system
     */
    public function isCallAgent(){

        $aMyRoles = $this->roles->toArray();
        foreach($aMyRoles as $oRow)
            if(strtoupper($oRow['name']) == 'CALL_AGENT')
                return true;

        return false;
    }


    /**
     * return true if logged in user is account manager of system
     */
    public function isAccountManager(){

        $aMyRoles = $this->roles->toArray();
        foreach($aMyRoles as $oRow)
            if(strtoupper($oRow['name']) == 'ACCOUNT_MANAGER')
                return true;

        return false;
    }

    /**
     * If AccountExecutive user logged in then returns true
     */
    public function isAccountExecutive(){

        $aMyRoles = $this->roles->toArray();
        foreach($aMyRoles as $oRow)
            if(strtoupper($oRow['name']) == 'ACCOUNT_EXECUTIVE')
                return true;

        return false;
    }

    /**
     * Return true if finance manager is logged in 
     */
    public function isFinanceManager(){

        $aMyRoles = $this->roles->toArray();
        foreach($aMyRoles as $oRow)
            if(strtoupper($oRow['name']) == 'FINANCE_MANAGER')
                return true;

        return false;
    }

    public function patientUser(){
    	return $this->hasMany('App\User', 'doctor_id')->select('id','name','is_active');
    }
}

