<?php

use App\DoctorMedicine;
use Faker\Factory;
use Illuminate\Database\Seeder;

class DoctorMedicineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oFaker = Factory::create();
        $aType = ['tablet','capsule','syrup','drops','inhaler','injection','topical','patch'];

        for( $i = 1; $i<=100; $i++ ) {

           $data = DoctorMedicine::create([
                'doctor_id' => rand(1,10),
                'medicine_name' => $oFaker->name,
                'medicine_id' => rand(1,10),
                'type' => $aType[rand(0, sizeof($aType) - 1)],
                'description' => $oFaker->text(100),
           ]);
        }
    }
}
