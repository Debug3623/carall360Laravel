<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pk')->nullable();
            $table->unsignedBigInteger('doctor_id');
            $table->string('name',50);
            $table->string('ref_number',50);
            $table->string('email',50)->nullable();
            $table->string('cnic',13)->nullable();
            $table->string('phone',20);
            $table->string('address',250)->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female','transgender'])->default('male');
            $table->enum('marital_status', ['married', 'unmarried'])->default('unmarried');
            $table->enum('blood_group', ['a+', 'a-','b+','b-','ab+','ab-','o+','o-'])->default('O+');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('country_code',3)->default('PAK');
            $table->text('remarks',250)->nullable();
            $table->float('height',2,2)->nullable();
            $table->float('weight',3,2)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
        Schema::table('patients', function($table){
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
        Schema::dropIfExists('patients');
    }
}
