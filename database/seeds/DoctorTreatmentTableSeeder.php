<?php

use App\DoctorTreatment;
use Faker\Factory;
use Illuminate\Database\Seeder;

class DoctorTreatmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oFaker = Factory::create();

        for( $i = 1; $i<=100; $i++ ) {

           $data = DoctorTreatment::create([
                'doctor_id' => rand(1,10),
                'treatment_name' => $oFaker->name,
                'treatment_id' => rand(1,10),
                'description' => $oFaker->text(100),
           ]);
        }
    }
}
