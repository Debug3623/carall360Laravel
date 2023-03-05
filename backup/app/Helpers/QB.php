<?php
/**
 * Created by PhpStorm.
 * User: infocaliper
 * Date: 7/18/18
 * Time: 5:23 PM
 */

namespace App\Helpers;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class QB
{

    public static function where($input, $param, $qb) {

        if (self::applyFilter($input, $param)) {
            $qb->where($param, $input[$param]);
        }

        return $qb;
    }

    public static function whereLike($input, $param, $qb)
    {

        if (self::applyFilter($input, $param)) {
            $qb = $qb->where($param, "LIKE", "%{$input[$param]}%");
        }

        return $qb;
    }

    public static function whereBetween($input, $param, $qb)
    {
        if (self::applyFilter($input, $param, $qb)) {
            $range = ArrayHelper::getStartAndEnd($input[$param]);
            $qb = $qb->whereBetween($param, $range);
        }

        return $qb;
    }

    private static function applyFilter($input, $param)
    {
        if (isset($input[$param])) {
            if ($input[$param] != "all") {

                if ($input[$param] == '0') {
                    return true;
                }

                if (!empty($input[$param])) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function hasWhere($model,$qb, $field = null, $operator = null, $value = null){

        $qb = $qb->whereHas($model, function ($q) use ($field, $operator, $value) {
            if ($value || $value === '0') {
                $q->where($field, $operator, $value);
            }

        });

        return $qb;
    }

    public static function whereHasIn($model,$qb, $field = null, $value = null){

        $qb = $qb->whereHas($model, function ($q) use ($field, $value) {
            if ($value) {
                $q->whereIn($field, $value);
            }

        });

        return $qb;
    }
    public static function filters($oInput, $oQb)
    {
        $oAuth = Auth::user();
        
        if(isset($oInput['doctor_name'])){
            $oValidator = Validator::make($oInput,[
                'doctor_name'       => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('doctorId', function ($q) use($oInput) {
                $q->where("full_name", 'like', '%'.$oInput['doctor_name'].'%');
            });
        }
        if(isset($oInput['patient_name'])){
            $oValidator = Validator::make($oInput,[
                'patient_name' => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('patientId', function ($q) use($oInput) {
                $q->where("name", 'like', '%'.$oInput['patient_name'].'%');
            });
        }
        if(isset($oInput['center_name'])){
            $oValidator = Validator::make($oInput,[
                'center_name' => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('centerId', function ($q) use($oInput) {
                $q->where("name", 'like', '%'.$oInput['center_name'].'%');
            });
        }
        if(isset($oInput['treatment_name'])){
            $oValidator = Validator::make($oInput,[
                'treatment_name' => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('treatmentId', function ($q) use($oInput) {
                $q->where("treatment_name", 'like', '%'.$oInput['treatment_name'].'%');
            });
        }
        if(isset($oInput['treatment_name'])){
            $oValidator = Validator::make($oInput,[
                'treatment_name' => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('treatmentId', function ($q) use($oInput) {
                $q->where("treatment_name", 'like', '%'.$oInput['treatment_name'].'%');
            });
        }
        if(isset($oInput['medicine_name'])){
            $oValidator = Validator::make($oInput,[
                'medicine_name' => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('medicineId', function ($q) use($oInput) {
                $q->where("medicine_name", 'like', '%'.$oInput['medicine_name'].'%');
            });
        }
        if(isset($oInput['country_name'])){
            $oValidator = Validator::make($oInput,[
                'country_name' => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('countryCode', function ($q) use($oInput) {
                $q->where("name", 'like', '%'.$oInput['country_name'].'%');
            });
        }
        if(isset($oInput['city_name'])){
            $oValidator = Validator::make($oInput,[
                'city_name' => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('cityId', function ($q) use($oInput) {
                $q->where("name", 'like', '%'.$oInput['city_name'].'%');
            });
        }
        if(isset($oInput['organization_name'])){
            $oValidator = Validator::make($oInput,[
                'organization_name' => 'required|string|max:50|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('organization', function ($q) use($oInput) {
                $q->where("name", 'like', '%'.$oInput['organization_name'].'%');
            });
        }
        if(isset($oInput['plan_name'])){
            $oValidator = Validator::make($oInput,[
                'plan_name' => 'required|string|max:20|min:3',
            ]);
            if($oValidator->fails()){
                abort(400,$oValidator->errors()->first());
            }
            $oQb = $oQb->whereHas('planId', function ($q) use($oInput) {
                $q->where("category_name", 'like', '%'.$oInput['plan_name'].'%');
            });
        }
        return $oQb;
    }
}