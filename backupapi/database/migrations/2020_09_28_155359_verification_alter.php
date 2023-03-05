<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VerificationAlter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verification_codes', function ($table) {
            
            $table->unsignedTinyInteger('is_used')->after('wrong_attempts')->default(0);
            

        });


        Schema::table('users', function ($table) {
            
            $table->unsignedTinyInteger('force_password_change')->after('is_active')->default(0);
            $table->string('password_store', 100)->after('password')->nullable();
            

        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
