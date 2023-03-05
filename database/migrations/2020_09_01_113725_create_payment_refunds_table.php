<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('paid_by');
            $table->float('amount',7,2);
            $table->float('refund_charges',7,2);
            $table->enum('status',['initiated', 'in_progress', 'completed', 'rejected'])->default('initiated');
            $table->string('old_status',50);
            $table->string('patient_account_number',30)->nullable();
            $table->timestamp('paid_datetime',6);
            $table->string('comments',250)->nullable();
            $table->string('reason',500)->nullable();
            $table->enum('payment_method',['ep_cc','ep_otc','ep_ma', 'jc_otc', 'jc_ma', 'jc_cc', 'bank', 'cash']);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('payment_refunds', function($table){
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->foreign('paid_by')->references('id')->on('users');
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
        Schema::dropIfExists('payment_refunds');
    }
}
