<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(CountryTableSeeder::class);
        // $this->call(CityTableSeeder::class);
        // $this->call(RolesAndPermissionsTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
        $this->call(DiagnosticTableSeeder::class);
        // $this->call(TreatmentTableSeeder::class);
        // $this->call(OrganizationTableSeeder::class);
        // $this->call(DoctorTableSeeder::class);
        // $this->call(CenterTableSeeder::class);
        // $this->call(PatientTableSeeder::class);
        // $this->call(TimeSlotTableSeeder::class);
        // $this->call(DoctorTreatmentTableSeeder::class);
        // $this->call(MedicineTableSeeder::class);
        // $this->call(DoctorMedicineTableSeeder::class);

    }
}
