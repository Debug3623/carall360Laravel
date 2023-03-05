<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;

use App\Observers\PatientObserver;
use App\Observers\DoctorObserver;
use App\Observers\MedicalRecordObserver;

use App\Patient;
use App\Doctor;
use App\MedicalRecord;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Patient::observe(PatientObserver::class);
        Doctor::observe(DoctorObserver::class);
        MedicalRecord::observe(MedicalRecordObserver::class); 
    }
}
