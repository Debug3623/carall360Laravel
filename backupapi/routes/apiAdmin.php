<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('landing/all_bank_account',  'AdminApiControllers\BankAccountController@landingPageAccount');
Route::post('admin/appointment_payment_evidence',  'AdminApiControllers\AppointmentPaymentController@create');
Route::group(['namespace'=>'AdminApiControllers','middleware' => ['api', 'auth:api'], 'prefix' => 'admin'], function () {
   
    // Client dashboard
    Route::get('client_dashboard','ClientDashboardController@index');
    Route::get('main_search','ClientDashboardController@searchIndex');
    Route::get('onboardDoctor_dashboard','ClientDashboardController@onboardDoctor');
    Route::get('revenue_dashboard','ClientDashboardController@revenue');
    Route::get('appointment_status_dashboard','ClientDashboardController@appointmentStatus');
    Route::get('medicine_analytics_dashboard','ClientDashboardController@medicineAnalytics');
    Route::get('lab_test_dashboard','ClientDashboardController@labTest');
    Route::get('treatment_analytics_dashboard','ClientDashboardController@treatmentAnalytics');
    
    // Roles Routes

    Route::resource('roles',  'RoleController');
    Route::post('delete_roles',  'RoleController@destroy');
    Route::post('roles/{id}',  'RoleController@update');
    Route::get('deleted_roles',  'RoleController@deleted');
    Route::post('restore_roles',  'RoleController@restore');
    Route::delete('permanent_delete_roles/{id}',  'RoleController@delete');
    Route::post('create_role_permissions',  'RoleController@createRolePermissions');
    Route::get('selected_roles',  'RoleController@selectedRole');

    //Permission Routes

    Route::resource('permissions',  'PermissionController');
    Route::post('delete_permissions',  'PermissionController@destroy');
    Route::post('permissions/{id}',  'PermissionController@update');
    Route::get('deleted_permissions',  'PermissionController@deleted');
    Route::post('restore_permissions',  'PermissionController@restore');
    Route::delete('permanent_delete_permissions/{id}',  'PermissionController@delete');
    Route::get('parent_permissions',  'PermissionController@parentPermission');

    //Menu Routes

    Route::resource('menu',  'MenuController');
    Route::post('delete_menu',  'MenuController@destroy');
    Route::post('menu/{id}',  'MenuController@update');
    Route::get('deleted_menu',  'MenuController@deleted');
    Route::post('restore_menu',  'MenuController@restore');
    Route::delete('permanent_delete_menu/{id}',  'MenuController@delete');

    // organization Routes

    Route::resource('organization',  'OrganizationController');
    Route::post('delete_organization',  'OrganizationController@destroy');
    Route::post('organization/{id}',  'OrganizationController@update');
    Route::get('deleted_organization',  'OrganizationController@deleted');
    Route::post('restore_organization',  'OrganizationController@restore');
    Route::delete('permanent_delete_organization/{id}',  'OrganizationController@delete');
    Route::post('create_organization_permissions',  'OrganizationController@createOrganizationPermissions');

    // Users Routes

    Route::resource('users',  'UserController');
    Route::post('delete_users',  'UserController@destroy');
    Route::post('users/{id}',  'UserController@update');
    Route::get('deleted_users',  'UserController@deleted');
    Route::post('restore_users',  'UserController@restore');
    Route::delete('permanent_delete_users/{id}',  'UserController@delete');
    Route::post('create_user_role',  'UserController@createUserRole');

    // Treatments
    Route::resource('treatment',  'TreatmentController');
    Route::post('delete_treatment',  'TreatmentController@destroy');
    Route::post('treatment/{id}',  'TreatmentController@update');
    Route::get('deleted_treatment',  'TreatmentController@deleted');
    Route::get('parent_treatments',  'TreatmentController@parentTreatments');
    Route::post('restore_treatment',  'TreatmentController@restore');
    Route::delete('permanent_delete_treatment/{id}',  'TreatmentController@delete');

    // diagnostics
    Route::resource('diagnostic',  'DiagnosticController');
    Route::post('delete_diagnostic',  'DiagnosticController@destroy');
    Route::post('diagnostic/{id}',  'DiagnosticController@update');
    Route::get('deleted_diagnostic',  'DiagnosticController@deleted');
    Route::post('restore_diagnostic',  'DiagnosticController@restore');
    Route::delete('permanent_delete_diagnostic/{id}',  'DiagnosticController@delete');

    // medicines
    Route::resource('medicine',  'MedicineController');
    Route::post('delete_medicine',  'MedicineController@destroy');
    Route::post('medicine/{id}',  'MedicineController@update');
    Route::get('deleted_medicine',  'MedicineController@deleted');
    Route::post('restore_medicine',  'MedicineController@restore');
    Route::delete('permanent_delete_medicine/{id}',  'MedicineController@delete');

    // Bank Account
    Route::resource('bankaccounts',  'BankAccountController');
    Route::post('delete_bankaccount',  'BankAccountController@destroy');
    Route::post('bankaccount/{id}',  'BankAccountController@update');
    Route::get('deleted_bankaccount',  'BankAccountController@deleted');
    Route::post('restore_bankaccount',  'BankAccountController@restore');
    Route::delete('permanent_delete_bankaccount/{id}',  'BankAccountController@delete');
    
    // Plan Categories
    Route::resource('plan_categories',  'PlanCategoryController');
    Route::post('delete_plan_category',  'PlanCategoryController@destroy');
    Route::post('plan_category/{id}',  'PlanCategoryController@update');
    Route::get('deleted_plan_category',  'PlanCategoryController@deleted');
    Route::get('all_plan_category',  'PlanCategoryController@allPlanCategories');
    Route::post('restore_plan_category',  'PlanCategoryController@restore');
    Route::delete('permanent_delete_plan_category/{id}',  'PlanCategoryController@delete');
    // PartnerShip
    Route::resource('partnerships',  'PartnershipController');
    Route::post('delete_partnership',  'PartnershipController@destroy');
    Route::post('partnership/{id}',  'PartnershipController@update');
    Route::post('renew_partnership',  'PartnershipController@renewPartnerShip');
    Route::get('deleted_partnership',  'PartnershipController@deleted');
    Route::post('restore_partnership',  'PartnershipController@restore');
    Route::delete('permanent_delete_partnership/{id}',  'PartnershipController@delete');
    Route::get('doctor_partnership/{doctor_id}',  'PartnershipController@doctorPartnerShip');

    // Appointment Payment
    Route::get('appointment_payment',  'AppointmentPaymentController@index');
    Route::get('appointment_payment/{id}',  'AppointmentPaymentController@show');
    Route::post('update_appointment_payment/{id}',  'AppointmentPaymentController@update');
    Route::get('appointment_payment_file/{url}',  'AppointmentPaymentController@render'); 
    Route::get('refund_appointments',  'AppointmentPaymentController@refundAppointments');
    Route::post('refund_appointment_payment/{id}',  'AppointmentPaymentController@refundPayment');
    
    // Doctor Specialty
    Route::resource('doctor_specialty',  'DoctorSpecialtyController');
    Route::post('delete_doctor_specialty',  'DoctorSpecialtyController@destroy');
    Route::post('doctor_specialty/{id}',  'DoctorSpecialtyController@update');
    Route::get('deleted_doctor_specialty',  'DoctorSpecialtyController@deleted');
    Route::post('restore_doctor_specialty',  'DoctorSpecialtyController@restore');
    Route::delete('permanent_delete_doctor_specialty/{id}',  'DoctorSpecialtyController@delete');
    Route::get('all_doctor_specialty',  'DoctorSpecialtyController@allDoctorSpecialty');

    // Doctor Payments
    Route::resource('doctor_payments',  'PaymentSummaryController');
    Route::post('doctor_payments/{id}',  'PaymentSummaryController@update');
    Route::get('doctor_revenue',  'PaymentSummaryController@index');
    Route::get('doctor_payments_image/{url}', 'PaymentSummaryController@render');
    Route::get('doctor_payments_detail/{id}',  'PaymentSummaryController@paymentSummeryDetail');
});

