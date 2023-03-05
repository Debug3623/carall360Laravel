<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'DoctorApiControllers','middleware' => ['api', 'auth:api'], 'prefix' =>'/doctor'], function () {

	// Doctor DashBoard
	Route::get('/patients', 'DashboardController@patients');
	Route::get('/reviews', 'DashboardController@reviews');
	Route::get('/appointments', 'DashboardController@appointments');
	Route::get('/appointment_analytics', 'DashboardController@appointmentAnalytics');
	Route::get('/payment_analytics', 'DashboardController@paymentAnalytics');
	Route::get('/appointment_type', 'DashboardController@appointmentType');
	
	Route::post('/patient-data/{patient_id?}', 'PatientManagementController@create');

	Route::get('/show-data/{file}', 'PatientManagementController@render');

	Route::get('/list-files/{patient_id?}', 'PatientManagementController@index');

	Route::get('/show/{id}', 'PatientManagementController@show');

	Route::post('/update/{id}', 'PatientManagementController@update');

	Route::get('/delete/{id}', 'PatientManagementController@delete');

});

