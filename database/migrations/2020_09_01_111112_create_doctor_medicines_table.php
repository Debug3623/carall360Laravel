<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_medicines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doctor_id');
            $table->string('medicine_name',50);
            $table->unsignedBigInteger('medicine_id')->nullable();
            $table->enum('type',['tablet','capsule','syrup','drops','inhaler','injection','topical','patch','ointment','spray','sach','shampoo','vaccine','guaze','anticeptic','balm','vial','cream'])->nullable();
            $table->unsignedTinyInteger('is_active')->default(0);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
        Schema::table('doctor_medicines', function($table){
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('medicine_id')->references('id')->on('medicines');
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
        Schema::dropIfExists('doctor_medicines');
    }
}
