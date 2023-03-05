<?php

use App\Center;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory;
use Illuminate\Support\Str;

class CenterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oFaker = Factory::create();
        
        $fBaseLat = 31.5031794;
        $fBaseLng = 74.3308091;
        
        for( $i = 1; $i<=100; $i++ ) {
           
           $user = Center::create([
                'name' => $oFaker->name,
                'doctor_id' => rand(1,10),
                'address' => $oFaker->address,
                'lat' => $fBaseLat,
                'lng' => $fBaseLng,
                'city_id' => rand(1,10),
                'country_code' => 'PAK',
                'created_by' => 1,
           ]);

            $fBaseLat = $fBaseLat + (rand(0,5)/1000);
            $fBaseLng = $fBaseLng - (rand(0,5)/1000);
        }
    }
}
