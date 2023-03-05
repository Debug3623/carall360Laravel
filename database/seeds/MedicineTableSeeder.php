<?php

use App\Medicine;
use Faker\Factory;
use Illuminate\Database\Seeder;

class MedicineTableSeeder extends Seeder
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

           $data = Medicine::create([
                'name' => $oFaker->name,
                'type' => $aType[rand(0, sizeof($aType) - 1)],
                'description' => $oFaker->text(100),
           ]);
        }
    }
}
