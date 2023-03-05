<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Laravel\Passport\Passport;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Role'      => 'App\Policies\RolePolicy',

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Passport::routes();

        Passport::personalAccessTokensExpireIn(now()->addMinutes(config('businesslogic.default_token_expiry')));

        // Role Ploicy
        Gate::define('role-create', 'App\Policies\RolePolicy@create');
        Gate::define('role-store', 'App\Policies\RolePolicy@store');
        Gate::define('role-index', 'App\Policies\RolePolicy@index');
        Gate::define('role-show', 'App\Policies\RolePolicy@show');
        Gate::define('role-update', 'App\Policies\RolePolicy@update');
        Gate::define('role-destroy', 'App\Policies\RolePolicy@destroy');
        Gate::define('role-deleted', 'App\Policies\RolePolicy@deleted');
        Gate::define('role-restore', 'App\Policies\RolePolicy@restore');
        Gate::define('role-delete', 'App\Policies\RolePolicy@delete');
        Gate::define('role-create-permission', 'App\Policies\RolePolicy@createRolePermissions');

        // Permission Ploicy
        Gate::define('permission-create', 'App\Policies\PermissionPolicy@create');
        Gate::define('permission-store', 'App\Policies\PermissionPolicy@store');
        Gate::define('permission-index', 'App\Policies\PermissionPolicy@index');
        Gate::define('permission-show', 'App\Policies\PermissionPolicy@show');
        Gate::define('permission-update', 'App\Policies\PermissionPolicy@update');
        Gate::define('permission-destroy', 'App\Policies\PermissionPolicy@destroy');
        Gate::define('permission-deleted', 'App\Policies\PermissionPolicy@deleted');
        Gate::define('permission-restore', 'App\Policies\PermissionPolicy@restore');
        Gate::define('permission-delete', 'App\Policies\PermissionPolicy@delete');
        Gate::define('parent-permission', 'App\Policies\PermissionPolicy@parentPermission');

        // Menu Ploicy
        Gate::define('menu-create', 'App\Policies\MenuPolicy@create');
        Gate::define('menu-store', 'App\Policies\MenuPolicy@store');
        Gate::define('menu-index', 'App\Policies\MenuPolicy@index');
        Gate::define('menu-show', 'App\Policies\MenuPolicy@show');
        Gate::define('menu-update', 'App\Policies\MenuPolicy@update');
        Gate::define('menu-destroy', 'App\Policies\MenuPolicy@destroy');
        Gate::define('menu-deleted', 'App\Policies\MenuPolicy@deleted');
        Gate::define('menu-restore', 'App\Policies\MenuPolicy@restore');
        Gate::define('menu-delete', 'App\Policies\MenuPolicy@delete');

        // Organization Ploicy
        Gate::define('organization-create', 'App\Policies\OrganizationPolicy@create');
        Gate::define('organization-store', 'App\Policies\OrganizationPolicy@store');
        Gate::define('organization-index', 'App\Policies\OrganizationPolicy@index');
        Gate::define('organization-show', 'App\Policies\OrganizationPolicy@show');
        Gate::define('organization-update', 'App\Policies\OrganizationPolicy@update');
        Gate::define('organization-destroy', 'App\Policies\OrganizationPolicy@destroy');
        Gate::define('organization-deleted', 'App\Policies\OrganizationPolicy@deleted');
        Gate::define('organization-restore', 'App\Policies\OrganizationPolicy@restore');
        Gate::define('organization-delete', 'App\Policies\OrganizationPolicy@delete');
        Gate::define('create-organization-permissions', 'App\Policies\OrganizationPolicy@createOrganizationPermissions');

        // Users Ploicy
        Gate::define('user-create', 'App\Policies\UserPolicy@create');
        Gate::define('user-store', 'App\Policies\UserPolicy@store');
        Gate::define('user-index', 'App\Policies\UserPolicy@index');
        Gate::define('user-show', 'App\Policies\UserPolicy@show');
        Gate::define('user-update', 'App\Policies\UserPolicy@update');
        Gate::define('user-destroy', 'App\Policies\UserPolicy@destroy');
        Gate::define('user-deleted', 'App\Policies\UserPolicy@deleted');
        Gate::define('user-restore', 'App\Policies\UserPolicy@restore');
        Gate::define('user-delete', 'App\Policies\UserPolicy@delete');
        Gate::define('user-assign-role', 'App\Policies\UserPolicy@createUserRole');
        // Doctor Ploicy
        Gate::define('doctor-create', 'App\Policies\DoctorPolicy@create');
        Gate::define('doctor-store', 'App\Policies\DoctorPolicy@store');
        Gate::define('doctor-index', 'App\Policies\DoctorPolicy@index');
        Gate::define('doctor-show', 'App\Policies\DoctorPolicy@show');
        Gate::define('doctor-update', 'App\Policies\DoctorPolicy@update');
        Gate::define('doctor-profile-view', 'App\Policies\DoctorPolicy@DoctorProfileView');
        Gate::define('doctor-profile-update', 'App\Policies\DoctorPolicy@DoctorProfileUpdate');
        Gate::define('doctor-destroy', 'App\Policies\DoctorPolicy@destroy');
        Gate::define('doctor-deleted', 'App\Policies\DoctorPolicy@deleted');
        Gate::define('doctor-restore', 'App\Policies\DoctorPolicy@restore');
        Gate::define('doctor-delete', 'App\Policies\DoctorPolicy@delete');
        // Doctor Awards Ploicy
        Gate::define('doctor-award-create', 'App\Policies\DoctorAwardPolicy@create');
        Gate::define('doctor-award-store', 'App\Policies\DoctorAwardPolicy@store');
        Gate::define('doctor-award-index', 'App\Policies\DoctorAwardPolicy@index');
        Gate::define('doctor-award-show', 'App\Policies\DoctorAwardPolicy@show');
        Gate::define('doctor-award-update', 'App\Policies\DoctorAwardPolicy@update');
        Gate::define('doctor-award-destroy', 'App\Policies\DoctorAwardPolicy@destroy');
        Gate::define('doctor-award-deleted', 'App\Policies\DoctorAwardPolicy@deleted');
        Gate::define('doctor-award-restore', 'App\Policies\DoctorAwardPolicy@restore');
        Gate::define('doctor-award-delete', 'App\Policies\DoctorAwardPolicy@delete');
        // Doctor certification Ploicy
        Gate::define('doctor-certification-create', 'App\Policies\DoctorCertificationPolicy@create');
        Gate::define('doctor-certification-store', 'App\Policies\DoctorCertificationPolicy@store');
        Gate::define('doctor-certification-index', 'App\Policies\DoctorCertificationPolicy@index');
        Gate::define('doctor-certification-show', 'App\Policies\DoctorCertificationPolicy@show');
        Gate::define('doctor-certification-update', 'App\Policies\DoctorCertificationPolicy@update');
        Gate::define('doctor-certification-destroy', 'App\Policies\DoctorCertificationPolicy@destroy');
        Gate::define('doctor-certification-deleted', 'App\Policies\DoctorCertificationPolicy@deleted');
        Gate::define('doctor-certification-restore', 'App\Policies\DoctorCertificationPolicy@restore');
        Gate::define('doctor-certification-delete', 'App\Policies\DoctorCertificationPolicy@delete');
        // Doctor qualification Ploicy
        Gate::define('doctor-qualification-create', 'App\Policies\DoctorQualificationPolicy@create');
        Gate::define('doctor-qualification-store', 'App\Policies\DoctorQualificationPolicy@store');
        Gate::define('doctor-qualification-index', 'App\Policies\DoctorQualificationPolicy@index');
        Gate::define('doctor-qualification-show', 'App\Policies\DoctorQualificationPolicy@show');
        Gate::define('doctor-qualification-update', 'App\Policies\DoctorQualificationPolicy@update');
        Gate::define('doctor-qualification-destroy', 'App\Policies\DoctorQualificationPolicy@destroy');
        Gate::define('doctor-qualification-deleted', 'App\Policies\DoctorQualificationPolicy@deleted');
        Gate::define('doctor-qualification-restore', 'App\Policies\DoctorQualificationPolicy@restore');
        Gate::define('doctor-qualification-delete', 'App\Policies\DoctorQualificationPolicy@delete');
        // Doctor Experience Ploicy
        Gate::define('doctor-experience-create', 'App\Policies\DoctorExperiencePolicy@create');
        Gate::define('doctor-experience-store', 'App\Policies\DoctorExperiencePolicy@store');
        Gate::define('doctor-experience-index', 'App\Policies\DoctorExperiencePolicy@index');
        Gate::define('doctor-experience-show', 'App\Policies\DoctorExperiencePolicy@show');
        Gate::define('doctor-experience-update', 'App\Policies\DoctorExperiencePolicy@update');
        Gate::define('doctor-experience-destroy', 'App\Policies\DoctorExperiencePolicy@destroy');
        Gate::define('doctor-experience-deleted', 'App\Policies\DoctorExperiencePolicy@deleted');
        Gate::define('doctor-experience-restore', 'App\Policies\DoctorExperiencePolicy@restore');
        Gate::define('doctor-experience-delete', 'App\Policies\DoctorExperiencePolicy@delete');
        // Doctor Assistant Ploicy
        Gate::define('doctor-assistant-create', 'App\Policies\DoctorAssistantPolicy@create');
        Gate::define('doctor-assistant-store', 'App\Policies\DoctorAssistantPolicy@store');
        Gate::define('doctor-assistant-index', 'App\Policies\DoctorAssistantPolicy@index');
        Gate::define('doctor-assistant-show', 'App\Policies\DoctorAssistantPolicy@show');
        Gate::define('doctor-assistant-update', 'App\Policies\DoctorAssistantPolicy@update');
        Gate::define('doctor-assistant-destroy', 'App\Policies\DoctorAssistantPolicy@destroy');
        Gate::define('doctor-assistant-deleted', 'App\Policies\DoctorAssistantPolicy@deleted');
        Gate::define('doctor-assistant-restore', 'App\Policies\DoctorAssistantPolicy@restore');
        Gate::define('doctor-assistant-delete', 'App\Policies\DoctorAssistantPolicy@delete');
        // Doctor Treatments
        Gate::define('all-treatments', 'App\Policies\DoctorTreatmentPolicy@allTreatments');
        Gate::define('doctor-treatment-create', 'App\Policies\DoctorTreatmentPolicy@create');
        Gate::define('doctor-treatment-store', 'App\Policies\DoctorTreatmentPolicy@store');
        Gate::define('doctor-treatment-index', 'App\Policies\DoctorTreatmentPolicy@index');
        Gate::define('doctor-treatment-show', 'App\Policies\DoctorTreatmentPolicy@show');
        Gate::define('doctor-treatment-update', 'App\Policies\DoctorTreatmentPolicy@update');
        Gate::define('doctor-treatment-destroy', 'App\Policies\DoctorTreatmentPolicy@destroy');
        Gate::define('doctor-treatment-deleted', 'App\Policies\DoctorTreatmentPolicy@deleted');
        Gate::define('doctor-treatment-restore', 'App\Policies\DoctorTreatmentPolicy@restore');
        Gate::define('doctor-treatment-delete', 'App\Policies\DoctorTreatmentPolicy@delete');
        // Treatments
        Gate::define('treatment-create', 'App\Policies\TreatmentPolicy@create');
        Gate::define('treatment-store', 'App\Policies\TreatmentPolicy@store');
        Gate::define('treatment-index', 'App\Policies\TreatmentPolicy@index');
        Gate::define('parent-treatment-index', 'App\Policies\TreatmentPolicy@parentTreatments');
        Gate::define('treatment-show', 'App\Policies\TreatmentPolicy@show');
        Gate::define('treatment-update', 'App\Policies\TreatmentPolicy@update');
        Gate::define('treatment-destroy', 'App\Policies\TreatmentPolicy@destroy');
        Gate::define('treatment-deleted', 'App\Policies\TreatmentPolicy@deleted');
        Gate::define('treatment-restore', 'App\Policies\TreatmentPolicy@restore');
        Gate::define('treatment-delete', 'App\Policies\TreatmentPolicy@delete');
        // Diagnostics
        Gate::define('diagnostic-create', 'App\Policies\DiagnosticPolicy@create');
        Gate::define('diagnostic-store', 'App\Policies\DiagnosticPolicy@store');
        Gate::define('diagnostic-index', 'App\Policies\DiagnosticPolicy@index');
        Gate::define('diagnostic-show', 'App\Policies\DiagnosticPolicy@show');
        Gate::define('diagnostic-update', 'App\Policies\DiagnosticPolicy@update');
        Gate::define('diagnostic-destroy', 'App\Policies\DiagnosticPolicy@destroy');
        Gate::define('diagnostic-deleted', 'App\Policies\DiagnosticPolicy@deleted');
        Gate::define('diagnostic-restore', 'App\Policies\DiagnosticPolicy@restore');
        Gate::define('diagnostic-delete', 'App\Policies\DiagnosticPolicy@delete');
        // Doctor Center
        Gate::define('doctor-center-create', 'App\Policies\DoctorCenterPolicy@create');
        Gate::define('doctor-center-store', 'App\Policies\DoctorCenterPolicy@store');
        Gate::define('doctor-center-index', 'App\Policies\DoctorCenterPolicy@index');
        Gate::define('doctor-center-show', 'App\Policies\DoctorCenterPolicy@show');
        Gate::define('doctor-center-update', 'App\Policies\DoctorCenterPolicy@update');
        Gate::define('doctor-center-destroy', 'App\Policies\DoctorCenterPolicy@destroy');
        Gate::define('doctor-center-deleted', 'App\Policies\DoctorCenterPolicy@deleted');
        Gate::define('doctor-center-restore', 'App\Policies\DoctorCenterPolicy@restore');
        Gate::define('doctor-center-delete', 'App\Policies\DoctorCenterPolicy@delete');
        // Doctor Medicine
        Gate::define('all-medicines', 'App\Policies\DoctorMedicinePolicy@allMedicines');
        Gate::define('doctor-medicine-create', 'App\Policies\DoctorMedicinePolicy@create');
        Gate::define('doctor-medicine-store', 'App\Policies\DoctorMedicinePolicy@store');
        Gate::define('doctor-medicine-index', 'App\Policies\DoctorMedicinePolicy@index');
        Gate::define('doctor-medicine-show', 'App\Policies\DoctorMedicinePolicy@show');
        Gate::define('doctor-medicine-update', 'App\Policies\DoctorMedicinePolicy@update');
        Gate::define('doctor-medicine-destroy', 'App\Policies\DoctorMedicinePolicy@destroy');
        Gate::define('doctor-medicine-deleted', 'App\Policies\DoctorMedicinePolicy@deleted');
        Gate::define('doctor-medicine-restore', 'App\Policies\DoctorMedicinePolicy@restore');
        Gate::define('doctor-medicine-delete', 'App\Policies\DoctorMedicinePolicy@delete');
        // Medicines
        Gate::define('medicine-create', 'App\Policies\MedicinePolicy@create');
        Gate::define('medicine-store', 'App\Policies\MedicinePolicy@store');
        Gate::define('medicine-index', 'App\Policies\MedicinePolicy@index');
        Gate::define('medicine-show', 'App\Policies\MedicinePolicy@show');
        Gate::define('medicine-update', 'App\Policies\MedicinePolicy@update');
        Gate::define('medicine-destroy', 'App\Policies\MedicinePolicy@destroy');
        Gate::define('medicine-deleted', 'App\Policies\MedicinePolicy@deleted');
        Gate::define('medicine-restore', 'App\Policies\MedicinePolicy@restore');
        Gate::define('medicine-delete', 'App\Policies\MedicinePolicy@delete');
        // Web Settings
        Gate::define('web-setting-create', 'App\Policies\WebSettingPolicy@create');
        Gate::define('web-setting-store', 'App\Policies\WebSettingPolicy@store');
        Gate::define('web-setting-index', 'App\Policies\WebSettingPolicy@index');
        Gate::define('web-setting-show', 'App\Policies\WebSettingPolicy@show');
        Gate::define('web-setting-update', 'App\Policies\WebSettingPolicy@update');
        Gate::define('web-setting-destroy', 'App\Policies\WebSettingPolicy@destroy');
        Gate::define('web-setting-deleted', 'App\Policies\WebSettingPolicy@deleted');
        Gate::define('web-setting-restore', 'App\Policies\WebSettingPolicy@restore');
        Gate::define('web-setting-delete', 'App\Policies\WebSettingPolicy@delete');

        // Doctor Schedule
        Gate::define('doctor-schedule-create', 'App\Policies\DoctorSchedulePolicy@create');
        Gate::define('doctor-schedule-store',  'App\Policies\DoctorSchedulePolicy@store');
        Gate::define('doctor-schedule-index',  'App\Policies\DoctorSchedulePolicy@index');
        Gate::define('doctor-schedule-show',   'App\Policies\DoctorSchedulePolicy@show');
        Gate::define('doctor-schedule-update', 'App\Policies\DoctorSchedulePolicy@update');
        Gate::define('doctor-schedule-destroy','App\Policies\DoctorSchedulePolicy@destroy');
        Gate::define('doctor-schedule-deleted','App\Policies\DoctorSchedulePolicy@deleted');
        Gate::define('doctor-schedule-restore','App\Policies\DoctorSchedulePolicy@restore');
        Gate::define('doctor-schedule-delete', 'App\Policies\DoctorSchedulePolicy@delete');
        Gate::define('doctor-vocation-update', 'App\Policies\DoctorSchedulePolicy@updateVocationMood');
        // Schedule Day Slots
        Gate::define('schedule-slot-create', 'App\Policies\DoctorScheduleDayPolicy@create');

        Gate::define('schedule-slot-store',  'App\Policies\DoctorScheduleDayPolicy@store');
        Gate::define('schedule-slot-index',  'App\Policies\DoctorScheduleDayPolicy@index');
        Gate::define('schedule-slot-show',   'App\Policies\DoctorScheduleDayPolicy@show');
        Gate::define('schedule-slot-update', 'App\Policies\DoctorScheduleDayPolicy@update');
        Gate::define('schedule-slot-destroy','App\Policies\DoctorScheduleDayPolicy@destroy');

        // Patient
        Gate::define('patient-create', 'App\Policies\PatientPolicy@create');
        Gate::define('patient-store', 'App\Policies\PatientPolicy@store');
        Gate::define('patient-index', 'App\Policies\PatientPolicy@index');
        Gate::define('patient-show', 'App\Policies\PatientPolicy@show');
        Gate::define('patient-profile-view', 'App\Policies\PatientPolicy@PatientProfileView');
        Gate::define('patient-profile-update', 'App\Policies\PatientPolicy@PatientProfileUpdate');
        Gate::define('patient-update', 'App\Policies\PatientPolicy@update');
        Gate::define('patient-destroy', 'App\Policies\PatientPolicy@destroy');
        Gate::define('patient-deleted', 'App\Policies\PatientPolicy@deleted');
        Gate::define('deleted-patient', 'App\Policies\PatientPolicy@deleted');
        Gate::define('patient-restore', 'App\Policies\PatientPolicy@restore');
        Gate::define('patient-delete', 'App\Policies\PatientPolicy@delete');
        // Patient Risk Factors
        Gate::define('riskfactor-create', 'App\Policies\RiskFactorPolicy@create');
        Gate::define('riskfactor-store', 'App\Policies\RiskFactorPolicy@store');
        Gate::define('riskfactor-index', 'App\Policies\RiskFactorPolicy@index');
        Gate::define('riskfactor-show', 'App\Policies\RiskFactorPolicy@show');
        Gate::define('riskfactor-update', 'App\Policies\RiskFactorPolicy@update');
        Gate::define('riskfactor-destroy', 'App\Policies\RiskFactorPolicy@destroy');
        Gate::define('riskfactor-deleted', 'App\Policies\RiskFactorPolicy@deleted');
        Gate::define('riskfactor-restore', 'App\Policies\RiskFactorPolicy@restore');
        Gate::define('riskfactor-delete', 'App\Policies\RiskFactorPolicy@delete');
        //Patient EMR Management
        Gate::define('emr-add',        'App\Policies\MedicalRecordPolicy@create');
        Gate::define('emr-file-render','App\Policies\MedicalRecordPolicy@view');
        Gate::define('emr-list',       'App\Policies\MedicalRecordPolicy@list');
        Gate::define('emr-update',     'App\Policies\MedicalRecordPolicy@update');
        Gate::define('emr-show',       'App\Policies\MedicalRecordPolicy@show');
        Gate::define('emr-delete',     'App\Policies\MedicalRecordPolicy@delete');
        Gate::define('emr-destroy',    'App\Policies\MedicalRecordPolicy@destroy');
        Gate::define('emr-deleted',     'App\Policies\MedicalRecordPolicy@deleted');
        Gate::define('emr-restore',     'App\Policies\MedicalRecordPolicy@restore');

        // Bankaccount Ploicy
        Gate::define('bankaccount-create', 'App\Policies\BankAccountPolicy@create');
        Gate::define('bankaccount-store', 'App\Policies\BankAccountPolicy@store');
        Gate::define('bankaccount-index', 'App\Policies\BankAccountPolicy@index');
        Gate::define('bankaccount-show', 'App\Policies\BankAccountPolicy@show');
        Gate::define('bankaccount-update', 'App\Policies\BankAccountPolicy@update');
        Gate::define('bankaccount-destroy', 'App\Policies\BankAccountPolicy@destroy');
        Gate::define('bankaccount-deleted', 'App\Policies\BankAccountPolicy@deleted');
        Gate::define('bankaccount-restore', 'App\Policies\BankAccountPolicy@restore');
        Gate::define('bankaccount-delete', 'App\Policies\BankAccountPolicy@delete');
        // Appointments
        Gate::define('appointment-create', 'App\Policies\AppointmentPolicy@create');
        Gate::define('appointment-store', 'App\Policies\AppointmentPolicy@store');
        Gate::define('appointment-index', 'App\Policies\AppointmentPolicy@index');
        Gate::define('appointment-show', 'App\Policies\AppointmentPolicy@show');
        Gate::define('appointment-update', 'App\Policies\AppointmentPolicy@update');
        Gate::define('appointment-destroy', 'App\Policies\AppointmentPolicy@destroy');
        Gate::define('appointment-deleted', 'App\Policies\AppointmentPolicy@deleted');
        Gate::define('appointment-restore', 'App\Policies\AppointmentPolicy@restore');
        Gate::define('appointment-delete', 'App\Policies\AppointmentPolicy@delete');
        // Review
        Gate::define('review-create', 'App\Policies\ReviewPolicy@create');
        Gate::define('review-store', 'App\Policies\ReviewPolicy@store');
        Gate::define('review-index', 'App\Policies\ReviewPolicy@index');
        Gate::define('review-show', 'App\Policies\ReviewPolicy@show');
        Gate::define('review-update', 'App\Policies\ReviewPolicy@update');
        Gate::define('review-destroy', 'App\Policies\ReviewPolicy@destroy');
        Gate::define('review-status-update', 'App\Policies\ReviewPolicy@statusUpdate');
        // Patient Review
        Gate::define('patient-review-create', 'App\Policies\ReviewPolicy@createPatient');
        Gate::define('patient-review-store', 'App\Policies\ReviewPolicy@storePatient');
        Gate::define('patient-review-index', 'App\Policies\ReviewPolicy@indexPatient');
        Gate::define('patient-review-show', 'App\Policies\ReviewPolicy@showPatient');
        Gate::define('patient-review-update', 'App\Policies\ReviewPolicy@updatePatient');
        // Prescription
        Gate::define('prescription-create', 'App\Policies\PrescriptionPolicy@create');
        Gate::define('prescription-store', 'App\Policies\PrescriptionPolicy@store');
        Gate::define('prescription-index', 'App\Policies\PrescriptionPolicy@index');
        Gate::define('prescription-show', 'App\Policies\PrescriptionPolicy@show');
        Gate::define('prescription-update', 'App\Policies\PrescriptionPolicy@update');
        Gate::define('prescription-destroy', 'App\Policies\PrescriptionPolicy@destroy');
        Gate::define('prescription-deleted', 'App\Policies\PrescriptionPolicy@deleted');
        Gate::define('prescription-restore', 'App\Policies\PrescriptionPolicy@restore');
        Gate::define('prescription-delete', 'App\Policies\PrescriptionPolicy@delete');
        // Diagnostic Prescription
        Gate::define('diagnostic-prescription-create', 'App\Policies\DiagnosticPrescriptionPolicy@create');
        Gate::define('diagnostic-prescription-store', 'App\Policies\DiagnosticPrescriptionPolicy@store');
        Gate::define('diagnostic-prescription-index', 'App\Policies\DiagnosticPrescriptionPolicy@index');
        Gate::define('diagnostic-prescription-show', 'App\Policies\DiagnosticPrescriptionPolicy@show');
        Gate::define('diagnostic-prescription-update', 'App\Policies\DiagnosticPrescriptionPolicy@update');
        Gate::define('diagnostic-prescription-destroy', 'App\Policies\DiagnosticPrescriptionPolicy@destroy');
        // Plan Categories
        Gate::define('plan-category-create', 'App\Policies\PlanCategoryPolicy@create');
        Gate::define('plan-category-store', 'App\Policies\PlanCategoryPolicy@store');
        Gate::define('plan-category-index', 'App\Policies\PlanCategoryPolicy@index');
        Gate::define('plan-category-show', 'App\Policies\PlanCategoryPolicy@show');
        Gate::define('plan-category-update', 'App\Policies\PlanCategoryPolicy@update');
        Gate::define('plan-category-destroy', 'App\Policies\PlanCategoryPolicy@destroy');
        Gate::define('plan-category-deleted', 'App\Policies\PlanCategoryPolicy@deleted');
        Gate::define('plan-category-restore', 'App\Policies\PlanCategoryPolicy@restore');
        Gate::define('plan-category-delete', 'App\Policies\PlanCategoryPolicy@delete');
        // Doctor Partnership
        Gate::define('partnership-create', 'App\Policies\PartnershipPolicy@create');
        Gate::define('partnership-store', 'App\Policies\PartnershipPolicy@store');
        Gate::define('partnership-index', 'App\Policies\PartnershipPolicy@index');
        Gate::define('partnership-show', 'App\Policies\PartnershipPolicy@show');
        Gate::define('partnership-update', 'App\Policies\PartnershipPolicy@update');
        Gate::define('partnership-destroy', 'App\Policies\PartnershipPolicy@destroy');
        Gate::define('partnership-deleted', 'App\Policies\PartnershipPolicy@deleted');
        Gate::define('partnership-restore', 'App\Policies\PartnershipPolicy@restore');
        Gate::define('partnership-delete', 'App\Policies\PartnershipPolicy@delete');
        // Appointment Payment
        Gate::define('appointment-payment-render', 'App\Policies\AppointmentPaymentPolicy@render');
        Gate::define('appointment-payment-index', 'App\Policies\AppointmentPaymentPolicy@index');
        Gate::define('appointment-payment-show', 'App\Policies\AppointmentPaymentPolicy@show');
        Gate::define('appointment-payment-update', 'App\Policies\AppointmentPaymentPolicy@update');
        
        // Doctor Specialty
        Gate::define('doctor-specialty-create', 'App\Policies\DoctorSpecialtyPolicy@create');
        Gate::define('doctor-specialty-store', 'App\Policies\DoctorSpecialtyPolicy@store');
        Gate::define('doctor-specialty-index', 'App\Policies\DoctorSpecialtyPolicy@index');
        Gate::define('doctor-specialty-show', 'App\Policies\DoctorSpecialtyPolicy@show');
        Gate::define('doctor-specialty-update', 'App\Policies\DoctorSpecialtyPolicy@update');
        Gate::define('doctor-specialty-destroy', 'App\Policies\DoctorSpecialtyPolicy@destroy');
        Gate::define('doctor-specialty-deleted', 'App\Policies\DoctorSpecialtyPolicy@deleted');
        Gate::define('doctor-specialty-restore', 'App\Policies\DoctorSpecialtyPolicy@restore');
        Gate::define('doctor-specialty-delete', 'App\Policies\DoctorSpecialtyPolicy@delete');

        // Payment Summery
        Gate::define('payment-summary-create', 'App\Policies\PaymentSummaryPolicy@create');
        Gate::define('payment-summary-store', 'App\Policies\PaymentSummaryPolicy@store');
        Gate::define('payment-summary-index', 'App\Policies\PaymentSummaryPolicy@index');
        Gate::define('revenue', 'App\Policies\PaymentSummaryPolicy@index');
        Gate::define('payment-summary-show', 'App\Policies\PaymentSummaryPolicy@show');
        Gate::define('payment-summary-update', 'App\Policies\PaymentSummaryPolicy@update');
        Gate::define('payment-summary-destroy', 'App\Policies\PaymentSummaryPolicy@destroy');
        Gate::define('payment-summary-deleted', 'App\Policies\PaymentSummaryPolicy@deleted');
        Gate::define('payment-summary-restore', 'App\Policies\PaymentSummaryPolicy@restore');
        Gate::define('payment-summary-delete', 'App\Policies\PaymentSummaryPolicy@delete');

        // Appointment Refund
        Gate::define('appointment-refund-index', 'App\Policies\AppointmentRefundPolicy@index');
        Gate::define('appointment-refund-update', 'App\Policies\AppointmentRefundPolicy@update');
    }
}