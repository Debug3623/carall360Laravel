<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doctor_id');
            $table->float('system_amount',7,2);
            $table->enum('status',['payable', 'collectable'])->default('payable');
            $table->enum('paid',['in_progress', 'completed'])->default('in_progress');
            $table->enum('payment_method',['cash', 'bank', 'cheque', 'wallet'])->default('bank');
            $table->string('account_number',30)->nullable();
            $table->timestamp('transfer_datetime',6);
            $table->unsignedBigInteger('paid_by')->nullable();
            $table->text('comments')->nullable();
            $table->string('evidence_url',250)->nullable();
            $table->string('file_name',250)->nullable();
            $table->string('mime_type',100)->nullable();
            $table->enum('file_type',['jpeg','jpg','png'])->nullable();
            $table->float('actual_amount',7,2);
            $table->float('outstanding',7,2)->nullable();
            $table->float('total_commission',7,2)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('payment_summaries', function($table){
            $table->foreign('doctor_id')->references('id')->on('doctors');
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
        Schema::dropIfExists('payment_summaries');
    }
}
