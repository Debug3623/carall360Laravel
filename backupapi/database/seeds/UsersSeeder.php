<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory;
use Illuminate\Support\Str;
class UsersTableSeeder extends Seeder
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
           if($i == 1){
               $phone = '03222555601';
           }

           $user = User::create([
                'name' => $oFaker->name,
                'phone' => $phone,
                'email' => $oFaker->email,
                'username' => $phone,
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'remember_token' => Str::random(10)
           ]);
           if($i == 1){
            DB::table('role_user')->insert([
                'role_id' => 1,
                'user_id' => 1,
            ]);
        }
        }
    }
}
