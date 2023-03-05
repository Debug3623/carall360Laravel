<?php

use App\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory;

class DoctorTableSeeder extends Seeder
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
        $aTitle = ['Dr', 'Prof','Asst'];

        for( $i = 1; $i<=100; $i++ ) {

           $phone = "03".$oFaker->randomNumber(9);

           $user = Doctor::create([
                'full_name' => $oFaker->name,
                'phone' => $phone,
                'email' => $oFaker->email,
                'gender' => $aGender[rand(0, sizeof($aGender) - 1)],
                'title' => $aTitle[rand(0, sizeof($aTitle) - 1)],
                'speciality' => $oFaker->word,
                'pmdc' => $oFaker->bothify('PMDC- ##??'),
                'url' =>$oFaker->unique()->url,
                'about' =>$oFaker->text(100),
                'practice_start_year' =>$oFaker->date($format = 'Y-m-d', $max = 'now'),
                'organization_id' =>rand(1,30),
                'created_by' => 1,
           ]);
        }
    }
}
