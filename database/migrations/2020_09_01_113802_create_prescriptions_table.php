<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('medicine_id')->nullable();
            $table->enum('type',['tablet','capsule','syrup','drops','inhaler','injection','topical','patch','ointment','spray','sach','shampoo','vaccine','guaze','anticeptic','balm','vial','cream'])->nullable();
            $table->string('dose_quantity',10)->nullable();
            $table->unsignedTinyInteger('is_morning')->default(0);
            $table->unsignedTinyInteger('is_afternoon')->default(0);
            $table->unsignedTinyInteger('is_evening')->default(0);
            $table->unsignedTinyInteger('is_daily')->default(0);
            $table->unsignedTinyInteger('is_week')->default(0);
            $table->unsignedTinyInteger('is_once')->default(0);
            $table->string('dose_remarks',100)->nullable();
            $table->mediumInteger('days_for')->nullable();
            $table->enum('file_type',['doc','docx','pdf','jpeg','jpg','png', 'mp3', 'mp4'])->nullable();
            $table->string('url',250)->nullable();
            $table->string('file_name',250)->nullable();
            $table->string('mime_type',100)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('prescriptions', function($table){
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->foreign('medicine_id')->references('id')->on('doctor_medicines');
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
        Schema::dropIfExists('prescriptions');
    }
}
