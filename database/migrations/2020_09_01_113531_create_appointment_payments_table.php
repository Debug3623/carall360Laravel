<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->enum('payment_method',['ep_cc','ep_otc','ep_ma', 'jc_otc', 'jc_ma', 'jc_cc', 'bank', 'cash']);
            $table->string('transaction_ref',50)->nullable();
            //internal transaction status
            $table->enum('status', ['init', 'pending', 'awaiting_confirmation', 'processed', 'void','canceled'])->nullable();
            $table->text('thirdparty_response')->nullable();
            $table->string('evidence_url',250)->nullable();
            $table->string('file_name',250)->nullable();
            $table->string('mime_type',100)->nullable();
            $table->enum('file_type',['jpeg','jpg','png'])->nullable();
            $table->timestamp('pay_date',6);
            $table->string('thirdparty_payment_status',20)->nullable();
            $table->string('thirdparty_id',30)->nullable();            
            /* for bank transfer and cash based tranactions  some user will verify */
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->dateTime('verified_at')->nullable();

            $table->timestamps();
        });
        Schema::table('appointment_payments', function($table){
            $table->foreign('appointment_id')->references('id')->on('appointments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_payments');
    }
}
