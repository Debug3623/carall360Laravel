<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',560)->nullable();
            $table->text('description')->nullable();
            $table->string('module',220)->nullable();
            $table->string('url',500);
            $table->string('method',100);
            $table->string('method_name',100)->nullable();
            $table->text('header_params')->nullable();
            $table->text('body_params')->nullable();
            $table->text('response_sample')->nullable();
            $table->string('auth',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_services');
    }
}
