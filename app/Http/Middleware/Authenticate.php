<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

use App\User;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     
    public function handle($request, Closure $next, ...$guards)
    {

        $sHeaderToken = $request->bearerToken();

        $bIsValidToken = User::isTokenAuth($sHeaderToken);

        if(!$bIsValidToken)
            return response('Expired', 401);


        //$this->authenticate($request, $guards);
        return $next($request);
    }

    */
}
