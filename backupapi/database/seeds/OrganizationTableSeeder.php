<?php

use App\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory;

class OrganizationTableSeeder extends Seeder
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

           $phone = "03".$oFaker->randomNumber(9);

           $data = Organization::create([
                'name' => $oFaker->name,
                'phone' => $phone,
                'email' => $oFaker->email,
                'country_code' => "PAK",
                'city_id' => rand(1,30),
                'doctor_max_discount' => rand(1,30),
                'created_by' => 1,
           ]);
        }
    }
}
