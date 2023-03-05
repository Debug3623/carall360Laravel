<?php

namespace App\Http\Middleware;

use Closure;

class EmailVerify
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
        
        /*
        if(!empty($oUser->email) && $oUser->email_verified != 1){
            //email address not verified - then restrict to email verification window only
            $oResponse = responseBuilder()->error(__('auth.unverified_email'), 200, false);
            $this->urlRec(3, 2, $oResponse);

            return $oResponse;
        }
        */
        return $next($request);
    }
}
