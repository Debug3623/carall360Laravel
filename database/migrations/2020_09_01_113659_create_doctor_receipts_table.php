<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('summary_id');
            $table->float('amount',7,2);
            $table->float('service_charges',7,2)->default(0);
            $table->enum('status',['payable', 'collectable'])->default('payable');
            $table->unsignedTinyInteger('is_settled')->default(0);
            $table->timestamps();
        });
        Schema::table('doctor_receipts', function($table){
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('summary_id')->references('id')->on('payment_summaries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_receipts');
    }
}
