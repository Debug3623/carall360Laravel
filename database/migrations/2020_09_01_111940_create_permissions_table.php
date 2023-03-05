<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',100);
            $table->string('alies',100)->nullable();
            $table->decimal('menu_order',7,2)->nullable();
            $table->string('selected_menu',25)->nullable();
            $table->unsignedTinyInteger('is_menu')->default(0);
            $table->string('permission_code',50);
            $table->string('description',500)->nullable();
            $table->string('url',150)->nullable();
            $table->string('icon',250)->nullable();
            $table->string('css_class',500)->nullable();
            $table->string('div_id',500)->nullable();
            $table->enum('type',['allow','disallow'])->default('allow');
            $table->unsignedTinyInteger('is_active')->default(1);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
        Schema::table('permissions', function($table){
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
        Schema::dropIfExists('permissions');
    }
}
