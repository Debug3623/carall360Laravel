<?php

namespace App\Http\Middleware;

use Closure;

class PhoneVerify
{

    use \App\Traits\WebServicesDoc;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $oUser = auth()->user();
        //dd($oUser);
        if($oUser->phone_verified != 1){
            //phone number not verified - then restrict to phone verification window only
            $oResponse = responseBuilder()->error(__('auth.unverified_phone'), 200, false);
            $this->urlRec(3, 1, $oResponse);

            return $oResponse;
        }

        return $next($request);
    }
}
