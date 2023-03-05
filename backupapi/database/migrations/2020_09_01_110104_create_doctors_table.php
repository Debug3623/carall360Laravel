<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pk')->nullable();
            $table->string('full_name',50);
            $table->enum('gender', ['male', 'female','transgender'])->default('male');
            $table->enum('title', ['Dr', 'Prof','Asst'])->default('Dr');
            $table->string('email',50);
            $table->string('pmdc',20);
            $table->string('phone',20);
            $table->string('speciality',250);
            $table->string('url',250);
            $table->text('about')->nullable();
            $table->date('practice_start_year');
            $table->date('dob')->nullable();
            $table->unsignedTinyInteger('is_active')->default(0);
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
        Schema::table('doctors', function($table){
            $table->foreign('organization_id')->references('id')->on('organizations');
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
        Schema::dropIfExists('doctors');
    }
}
