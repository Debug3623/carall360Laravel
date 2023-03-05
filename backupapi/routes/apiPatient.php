<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'PatientApiControllers','middleware' => ['api', 'auth:api'], 'prefix' => '/patient'], function () {

    // patient DashBoard
    Route::get('/reviews', 'DashboardController@reviews');
    Route::get('/appointments', 'DashboardController@appointments');
    Route::get('/payment_analytics', 'DashboardController@payments');
    Route::get('/emrs', 'DashboardController@emr');
    Route::get('/prescription', 'DashboardController@prescription');

});
