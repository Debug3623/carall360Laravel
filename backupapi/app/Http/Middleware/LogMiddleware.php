<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use Closure;

class LogMiddleware
{

    private $oLogger;

    public function __construct(Log $oLogger) {
        $this->oLogger = $oLogger;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($oRequest, Closure $next)
    {
        $oResponse = $next($oRequest);
        //dump($oRequest->all());
        //dump($oRequest->url());
        //dump($oRequest->ip());

        $sIpAddress = $oRequest->ip();
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $sIpAddress = $sIpAddress."/".$_SERVER['HTTP_X_FORWARDED_FOR'];

	$referer = request()->headers->get('referer');


        if(\Auth::check())
            $sStringToLog = auth()->user()->name."(".auth()->user()->id.")"." / ".$oRequest->url()." [".$sIpAddress."]*$referer\n";
        else
            $sStringToLog = "GUEST / ".$oRequest->url()." [".$sIpAddress."]*$referer\n";


        LOG::info($sStringToLog.print_r($oRequest->all(), true));
        //dd($this->oLogger);

        //LOG::info(print_r($oResponse, true));
        if(env('LOG_RESPONSE') == 1)
            LOG::info(print_r($oResponse->original, true));

        return $oResponse;
    }
}
