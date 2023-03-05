<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_no',15)->nullable();

            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('treatment_id')->nullable();
            $table->unsignedBigInteger('center_id')->nullable();
            $table->unsignedBigInteger('slot_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->float('appointment_fee',7,2);
            $table->float('original_fee',7,2);
            $table->enum('lead_from',['doctor','careall','website','patient'])->default('website');
            $table->enum('appointment_type',['physical', 'online'])->default('online');
            $table->enum('paid_status',['paid', 'unpaid'])->default('unpaid');
            $table->enum('status',['pending', 'approved', 'cancel_by_doctor', 'cancel_by_patient','cancel_by_adminStaff', 'no_show', 'done', 'ongoing', 'follow_up', 'reschedule', 'refund','auto_cancel','awaiting_confirmation'])->default('pending');
            $table->timestamp('payment_timelimit',6)->nullable();
            $table->string('doctor_remarks',250)->nullable();
            $table->date('appointment_date');
            $table->unsignedTinyInteger('is_settled')->default(0);
            $table->unsignedTinyInteger('is_reviewed')->default(0);
            $table->unsignedTinyInteger('is_again')->default(0);
            $table->string('tele_url',250)->nullable();
            $table->string('tele_password',30)->nullable();
            $table->string('patient_token',250)->nullable();
            $table->string('doctor_token',250)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('appointments', function($table){
            $table->foreign('treatment_id')->references('id')->on('treatments');
            $table->foreign('center_id')->references('id')->on('centers');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('slot_id')->references('id')->on('time_slots');
            $table->foreign('doctor_id')->references('id')->on('doctors');
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
        Schema::dropIfExists('appointments');
    }
}
