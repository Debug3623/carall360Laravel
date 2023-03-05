<?php

use App\Patient;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PatientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oFaker = Factory::create();
        
        $aGender = ['male', 'female', 'transgender'];
        $aMarital_status = ['married', 'unmarried'];
        $aBlood_group = ['a+', 'a-','b+','b-','ab+','ab-','o+','o-'];

        for( $i = 1; $i<=100; $i++ ) {

           $phone = "03".$oFaker->randomNumber(9);

           $user = Patient::create([
                'doctor_id' => rand(1,10),
                'name' => $oFaker->name,
                'phone' => $phone,
                'email' => $oFaker->email,
                'ref_number' => $oFaker->numberBetween($min = 1000, $max = 9000),
                'address' => $oFaker->address,
                'dob' => $oFaker->date($format = 'Y-m-d', $max = 'now'),
                'gender' => $aGender[rand(0, sizeof($aGender) - 1)],
                'marital_status' => $aMarital_status[rand(0, sizeof($aMarital_status) - 1)],
                'blood_group' => $aBlood_group[rand(0, sizeof($aBlood_group) - 1)],
                'city_id' => rand(1,30),
                'country_code' => "PAK",
                'remarks' =>$oFaker->text(100),
                'created_by' => 1,
           ]);
        }
    }
}
