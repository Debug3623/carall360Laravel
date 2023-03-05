<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doctor_id');
            $table->string('name',100);
            $table->string('address',250)->nullable();
            $table->float('lat',8,2)->nullable();
            $table->float('lng',8,2)->nullable();
            $table->unsignedBigInteger('city_id');
            $table->string('country_code',3);
            $table->unsignedTinyInteger('is_active')->default(0);
            $table->unsignedTinyInteger('is_primary')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
        Schema::table('centers', function($table){
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('country_code')->references('code')->on('countries');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centers');
    }
}
