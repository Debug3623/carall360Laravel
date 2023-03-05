<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('center_id')->nullable();
            $table->string('heading',50);
            $table->string('color',50)->nullable();
            $table->enum('type',['online','physical']);
            $table->float('fee',7,2);
            $table->float('discount_fee',7,2);
            $table->integer('minimum_booking_hours');
            $table->integer('duration')->default(45);
            $table->string('time_from',10)->default("09:00");
            $table->string('time_to',10)->default("18:00");
            $table->date('date_from');
            $table->date('date_to')->nullable();
            $table->date('vocation_date_from')->nullable();
            $table->date('vocation_date_to')->nullable();
            $table->unsignedTinyInteger('is_active')->default(0);
            $table->unsignedTinyInteger('is_primary')->default(0);
            $table->unsignedTinyInteger('is_vocation')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
        Schema::table('doctor_schedules', function($table){
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('center_id')->references('id')->on('centers');
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
        Schema::dropIfExists('doctor_schedules');
    }
}
