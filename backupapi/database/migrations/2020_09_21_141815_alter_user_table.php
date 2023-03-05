<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {

            $table->dropColumn('api_token');
            $table->dropColumn('api_token_expiry');

            $table->unsignedTinyInteger('is_active')->after('remember_token')->default(1);

            //$table->unsignedTinyInteger('invalid_login_attempts')->after('is_active')->default(0);
            

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
