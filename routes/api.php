<?php

use Illuminate\Http\Request;


include base_path('routes/apiAdmin.php');

include base_path('routes/apiDoctor.php');

include base_path('routes/apiPatient.php');




Route::match(['get','post'], '/login/{doctor_id?}', 'UserController@login')->name('login');

 Route::get('all_doctors',  'PatientRegisterController@allDoctors');
 Route::get('all_patients',  'PatientController@allPatients');
 Route::resource('patients',  'PatientRegisterController');
 Route::resource('doctor_patinets',  'PatientDoctorController');


Route::match(['get', 'post'], '/forget/{method}/{doctor_id?}', 'UserController@forgetPassword');

Route::match(['get', 'post'], '/restpassword/{identifier}/{code}', 'UserController@resetPassword')->middleware('throttle:3,5');

//can be called for both login users as well as guest users
Route::get('/verify-my-email/{identifier}/{code}', 'UserController@doVerifyEmail');
Route::post('/verify-my-phone/{identifier?}', 'UserController@doVerifyPhone');
Route::post('/sms_gateway', 'PatientController@smsGateway');


Route::post('/username-verify/{doctor_id}', 'VendorController@isAlreadyUser');
Route::post('/patient-signup/{doctor_id}', 'PatientController@signup');
Route::post('/register', 'PatientController@register');


Route::get('/all_doctor',  'DoctorProfileController@allDoctor');

//login via OTP code
Route::post('/get-otp', 'UserController@generateOTPCode');


Route::get('/phone-verify-request-link/{sUserIdEnc?}', 'UserController@phoneVerifyRequest')->middleware('throttle:20,10');
Route::get('/email-verify-request-link/{sUserIdEnc?}', 'UserController@emailVerifyRequest')->middleware('throttle:20,10');


//jazzcash return back URL and listner -- not under login credentials
Route::post('/jazzcash_return_url', 'AppointmentController@jazzPostBackUrl');
Route::post('/jazzcash_listner_FUlE9PSIsInZhbHVlIjoidGxMmU2MjQ2MDEzNmIzY', 'AppointmentController@jc_listner');


//phone and email verification api routes
Route::group(['middleware' => ['api', 'auth:api']], function () {
    Route::get('/auth/phone-verify-request-link', 'UserController@phoneVerifyRequest')->middleware('throttle:20,10');
    Route::get('/auth/email-verify-request-link', 'UserController@emailVerifyRequest')->middleware('throttle:20,10');
    Route::match(['get','post'], '/logout', 'UserController@logout');

   Route::post('/verify-phone', 'UserController@doVerifyPhone');

});
//Route::post('/verify-my-phone', 'UserController@doVerifyPhone');

Route::group(['middleware' => ['api', 'auth:api', 'phone.verify', 'email.verify']], function () {

	Route::match(['get','post'], '/my-profile', 'UserController@my_profile');

	Route::match(['get','post'], '/my-role', 'UserController@my_role');

	Route::match(['get','post'], '/my-permission', 'UserController@getMyAssignedPermissions');

    //appointment payment flows
    Route::post('/payment/{method}/{appointment_id}', 'AppointmentController@init_payment');

    Route::post('/payment-status/{appointment_id}/{transaction_id}', 'AppointmentController@getTransactionStatus');   


    //chat api
    Route::match(['get','post'], '/chat/all_users', 'ChatController@allUsers');

    Route::match(['get','post'], '/chat/init/{patient_id?}', 'ChatController@initChat');

    Route::match(['get','post'], '/chat/count/{chat_id}/{internal?}', 'ChatController@getChatMessageCount');

    Route::match(['get','post'], '/chat/load/{chat_id}/{count?}/{offset?}', 'ChatController@loadChatHistory');

    Route::match(['get','post'], '/chat/conversation/{chat_id}', 'ChatController@doMessage');

    Route::match(['get','post'], '/chat/listner/{chat_id}/{last_message_id?}', 'ChatController@listener');
    

});

// City Routes
Route::resource('cities',  'CityController');
// Country Routes
Route::resource('countries',  'CountryController');


Route::match(['get','post'], '/jazzcash', 'PaymentGatewayController@jcPostURL');
Route::get('/jc_card', 'PaymentGatewayController@jc_card');
Route::match(['get','post'],'/jc_mwallet', 'PaymentGatewayController@jcMwallet');
Route::post('/jc_otc', 'PaymentGatewayController@jcOtc');
Route::match(['get','post'], '/jazzcash', 'PaymentGatewayController@jc_listner');
Route::post('/js_status_inquiry', 'PaymentGatewayController@jcStatusInquiry');

Route::post('/ep_mapayment', 'PaymentGatewayController@epMAPayment');
Route::post('/ep_otcpayment', 'PaymentGatewayController@epOTCPayment');
Route::post('/ep_status_inquiry', 'PaymentGatewayController@epStatusInquiry');


Route::group(['middleware' => ['api', 'auth:api'], 'prefix' => 'auth'], function () {


    // Doctors
    Route::resource('doctors',  'DoctorProfileController');
    Route::get('all_doctors',  'DoctorProfileController@allDoctors');
    Route::post('delete_doctors',  'DoctorProfileController@destroy');
    Route::post('doctors/{id}',  'DoctorProfileController@update');
    Route::get('deleted_doctors',  'DoctorProfileController@deleted');
    Route::post('restore_doctors',  'DoctorProfileController@restore');
    Route::delete('permanent_delete_doctors/{id}',  'DoctorProfileController@delete');
    
    // Doctor Assistant
    Route::get('doctor_assistant/{doctor_id}','DoctorAssistantController@index');
    Route::resource('doctor_assistants',  'DoctorAssistantController');
    Route::post('delete_doctor_assistants',  'DoctorAssistantController@destroy');
    Route::post('doctor_assistants/{id}',  'DoctorAssistantController@update');
    Route::get('deleted_doctor_assistants/{doctor_id}',  'DoctorAssistantController@deleted');
    Route::post('restore_doctor_assistants',  'DoctorAssistantController@restore');
    Route::delete('permanent_delete_doctor_assistants/{id}',  'DoctorAssistantController@delete');

    // Doctor Awards
    Route::get('doctor_awards/{doctor_id}','DoctorAwardController@index');
    Route::resource('doctor_award',  'DoctorAwardController');
    Route::post('delete_doctor_award',  'DoctorAwardController@destroy');
    Route::post('doctor_award/{id}',  'DoctorAwardController@update');
    Route::get('deleted_doctor_award/{doctor_id}',  'DoctorAwardController@deleted');
    Route::post('restore_doctor_award',  'DoctorAwardController@restore');
    Route::delete('permanent_delete_doctor_award/{id}',  'DoctorAwardController@delete');
    
    // Doctor certification
    Route::get('doctor_certifications/{doctor_id}','DoctorCertificationController@index');
    Route::resource('doctor_certification',  'DoctorCertificationController');
    Route::post('delete_doctor_certification',  'DoctorCertificationController@destroy');
    Route::post('doctor_certification/{id}',  'DoctorCertificationController@update');
    Route::get('deleted_doctor_certification/{doctor_id}',  'DoctorCertificationController@deleted');
    Route::post('restore_doctor_certification',  'DoctorCertificationController@restore');
    Route::delete('permanent_delete_doctor_certification/{id}',  'DoctorCertificationController@delete');

    // Doctor qualification
    Route::get('doctor_qualifications/{doctor_id}','DoctorQualificationController@index');
    Route::resource('doctor_qualification',  'DoctorQualificationController');
    Route::post('delete_doctor_qualification',  'DoctorQualificationController@destroy');
    Route::post('doctor_qualification/{id}',  'DoctorQualificationController@update');
    Route::get('deleted_doctor_qualification/{doctor_id}',  'DoctorQualificationController@deleted');
    Route::post('restore_doctor_qualification',  'DoctorQualificationController@restore');
    Route::delete('permanent_delete_doctor_qualification/{id}',  'DoctorQualificationController@delete');

    // Doctor Experiences
    Route::get('doctor_experiences/{doctor_id}','DoctorExperienceController@index');
    Route::resource('doctor_experience',  'DoctorExperienceController');
    Route::post('delete_doctor_experience',  'DoctorExperienceController@destroy');
    Route::post('doctor_experience/{id}',  'DoctorExperienceController@update');
    Route::get('deleted_doctor_experience/{doctor_id}',  'DoctorExperienceController@deleted');
    Route::post('restore_doctor_experience',  'DoctorExperienceController@restore');
    Route::delete('permanent_delete_doctor_experience/{id}',  'DoctorExperienceController@delete');

    // Doctor Treatments
    Route::get('doctor_treatments/{doctor_id}','DoctorTreatmentController@index');
    Route::get('all_treatments','DoctorTreatmentController@allTreatments');
    Route::get('all_doctor_treatments/{doctor_id}','DoctorTreatmentController@allDoctorTreatments');
    Route::resource('doctor_treatment',  'DoctorTreatmentController');
    Route::post('delete_doctor_treatment',  'DoctorTreatmentController@destroy');
    Route::post('doctor_treatment/{id}',  'DoctorTreatmentController@update');
    Route::get('deleted_doctor_treatment/{doctor_id}',  'DoctorTreatmentController@deleted');
    Route::post('restore_doctor_treatment',  'DoctorTreatmentController@restore');
    Route::delete('permanent_delete_doctor_treatment/{id}',  'DoctorTreatmentController@delete');

    // Doctor Medicines
    Route::get('doctor_medicines/{doctor_id}','DoctorMedicineController@index');
    Route::get('all_medicines','DoctorMedicineController@allMedicines');
    Route::get('all_doctor_medicines/{doctor_id}','DoctorMedicineController@allDoctorMedicines');
    Route::resource('doctor_medicine',  'DoctorMedicineController');
    Route::post('delete_doctor_medicine',  'DoctorMedicineController@destroy');
    Route::post('doctor_medicine/{id}',  'DoctorMedicineController@update');
    Route::get('deleted_doctor_medicine/{doctor_id}',  'DoctorMedicineController@deleted');
    Route::post('restore_doctor_medicine',  'DoctorMedicineController@restore');
    Route::delete('permanent_delete_doctor_medicine/{id}',  'DoctorMedicineController@delete');

    // Doctor Centers
    Route::get('doctor_centers/{doctor_id}','DoctorCenterController@index');
    Route::resource('doctor_center',  'DoctorCenterController');
    Route::post('delete_doctor_center',  'DoctorCenterController@destroy');
    Route::post('doctor_center/{id}',  'DoctorCenterController@update');
    Route::get('deleted_doctor_center/{doctor_id}',  'DoctorCenterController@deleted');
    Route::post('restore_doctor_center',  'DoctorCenterController@restore');
    Route::delete('permanent_delete_doctor_center/{id}',  'DoctorCenterController@delete');
    
    // Doctor Web Settings
    Route::get('doctor_web_settings/{doctor_id}','WebSettingController@index');
    Route::resource('doctor_web_setting',  'WebSettingController');
    Route::post('delete_doctor_web_setting',  'WebSettingController@destroy');
    Route::post('doctor_web_setting/{id}',  'WebSettingController@update');
    Route::get('deleted_doctor_web_setting/{doctor_id}',  'WebSettingController@deleted');
    Route::post('restore_doctor_web_setting',  'WebSettingController@restore');
    Route::delete('permanent_delete_doctor_web_setting/{id}',  'WebSettingController@delete');
    Route::get('social_links', 'WebSettingController@listOfSocialLinks');
    
    // Doctor Schedule
    Route::get('time_slot','DoctorScheduleController@timeSlot');
    Route::get('doctor_schedules/{doctor_id}','DoctorScheduleController@index');
    Route::resource('doctor_schedule',  'DoctorScheduleController');
    Route::post('delete_doctor_schedule',  'DoctorScheduleController@destroy');
    Route::post('doctor_schedule/{id}',  'DoctorScheduleController@update');
    Route::get('deleted_doctor_schedule/{doctor_id}',  'DoctorScheduleController@deleted');
    Route::post('restore_doctor_schedule',  'DoctorScheduleController@restore');
    Route::delete('permanent_delete_doctor_schedule/{id}',  'DoctorScheduleController@delete');
    Route::post('doctor_vocation_mood/{id}',  'DoctorScheduleController@updateVocationMood');
    
    // Doctor Schedule Day Slots
    Route::get('doctor_schedule_days/{schedule_id}','DoctorScheduleDayController@index');
    Route::resource('doctor_schedule_day',  'DoctorScheduleDayController');
    Route::post('doctor_schedule_day/{id}',  'DoctorScheduleDayController@update');
    Route::post('delete_doctor_schedule_day',  'DoctorScheduleDayController@destroy');


    // Patients
    
    //Doctor patients assign
    
    Route::post('doctor_patinets',  'PatientController@patientDoctor');
    Route::resource('patients',  'PatientController');
    Route::resource('callCenter',  'CallCenterController');
    Route::get('all_patients',  'PatientController@allPatients');
    Route::get('all_callCanter',  'CallCenterController@allCallCenter');
     Route::post('callCenter/{id}',  'CallCenterController@update');
    Route::post('delete_patients',  'PatientController@destroy');
    Route::post('patient/{id}',  'PatientController@update');
    Route::get('deleted_patients',  'PatientController@deleted');
    Route::get('doctor_patients',  'PatientController@doctorPatient');
    Route::post('restore_patients',  'PatientController@restore');
    Route::delete('permanent_delete_patient/{id}',  'PatientController@delete');
    // Patients Risk Factors
    Route::get('riskfactors/{patient_id}',  'PatientRiskFactorController@index');
    Route::resource('riskfactor',  'PatientRiskFactorController');
    Route::post('delete_riskfactor',  'PatientRiskFactorController@destroy');
    Route::post('riskfactor/{id}',  'PatientRiskFactorController@update');
    Route::get('deleted_riskfactor/{patient_id}',  'PatientRiskFactorController@deleted');
    Route::post('restore_riskfactor',  'PatientRiskFactorController@restore');
    Route::delete('permanent_delete_riskfactor/{id}',  'PatientRiskFactorController@delete');
    Route::get('patient_riskfactor/{id}',  'PatientRiskFactorController@patientRiskFactor');

    // Appointments
    Route::post('appointment_patient',  'AppointmentController@appointmentPatients');
    Route::resource('appointments',  'AppointmentController');
    Route::post('delete_appointments',  'AppointmentController@destroy');
    Route::post('appointment/{id}',  'AppointmentController@update');
    Route::post('appointment_status/{id}',  'AppointmentController@updateStatus');
    Route::post('appointment_remarks/{id}',  'AppointmentController@doctorRemarks');
    Route::get('deleted_appointments',  'AppointmentController@deleted');
    Route::get('parent_appointments',  'AppointmentController@parentAppointment');
    Route::post('restore_appointments',  'AppointmentController@restore');
    Route::delete('permanent_delete_appointment/{id}',  'AppointmentController@delete');
    Route::post('video_consultation_token',  'AppointmentController@videoConsultation');

    // Reviews
    Route::resource('reviews',  'ReviewsController');
    Route::post('delete_reviews',  'ReviewsController@destroy');
    Route::post('review/{id}',  'ReviewsController@update');
    Route::post('review_status/{id}',  'ReviewsController@updateStatus');
    // Medical record
	Route::get('medical_records', 'MedicalRecordController@index');
    Route::post('medical_record', 'MedicalRecordController@create');
	Route::get('medical_records/{url}', 'MedicalRecordController@render');
	Route::get('medical_records_show/{id}', 'MedicalRecordController@show');
    Route::post('medical_record/{id}', 'MedicalRecordController@update');
    Route::post('delete_medical_record',  'MedicalRecordController@destroy');
    Route::get('deleted_medical_record',  'MedicalRecordController@deleted');
    Route::post('restore_medical_record',  'MedicalRecordController@restore');
    Route::delete('permanent_delete_medical_record/{id}', 'MedicalRecordController@delete');
    // Prescriptions
    Route::resource('prescriptions',  'PrescriptionController');
    Route::get('picture_index_prescriptions',  'PrescriptionController@pictureIndex');
    Route::get('all_prescriptions',  'PrescriptionController@allPrescription');
    Route::post('delete_prescriptions',  'PrescriptionController@destroy');
    Route::post('prescription/{id}',  'PrescriptionController@update');
    Route::get('deleted_prescriptions',  'PrescriptionController@deleted');
    Route::post('restore_prescriptions',  'PrescriptionController@restore');
    Route::delete('permanent_delete_prescription/{id}',  'PrescriptionController@delete');
    Route::post('upload_prescription',  'PrescriptionController@uploadPrescription');
    Route::get('show_uploaded_prescription/{url}',  'PrescriptionController@showUploadedPrescription');
    // Diagnostic Prescriptions
    
    Route::resource('diagnostic_prescriptions',  'DiagnosticPrescriptionController');
    Route::post('delete_diagnostic_prescriptions',  'DiagnosticPrescriptionController@destroy');
    Route::post('diagnostic_prescription/{id}',  'DiagnosticPrescriptionController@update');
    Route::get('all_diagnostics',  'DiagnosticPrescriptionController@allDiagnostics');
    Route::get('all_diagnostics_prescription',  'DiagnosticPrescriptionController@allDiagnosticsPrescription');
    
    // Doctor, Admin and Patient Can change there password
    Route::post('reset_password',  'UserController@changePassword');
});
//Route::get('landing/{url}',  'DoctorProfileController@landingPage');
Route::group(['middleware' => ['api'], 'prefix' => 'landing'], function () {
    
    Route::get('/{url}',  'DoctorProfileController@landingPage');
    Route::post('/timeSlots',  'DoctorScheduleController@doctorTimeSlots');
    Route::get('/doctor_centers/{doctor_id}','DoctorCenterController@doctorCenter');
    
});
Route::get('access_token', 'API\GenerateAccessTokenController@generate_token');
Route::get('joinRoom', 'API\GenerateAccessTokenController@joinRoom');
Route::get('roomList', 'API\GenerateAccessTokenController@index');
Route::post('createRoom', 'API\GenerateAccessTokenController@createRoom');

Route::post('contact_form', 'WebSettingController@contactForm');