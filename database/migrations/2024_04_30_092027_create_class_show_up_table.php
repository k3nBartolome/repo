<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassShowUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
/*     public function up()
    {
        Schema::create('class_show_up', function (Blueprint $table) {
            $table->id();
            $table->string('csu_status')->nullable();
            $table->string('csu_day')->nullable();
            $table->unsignedBigInteger('csu_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id')->nullable();
            $table->unsignedBigInteger('csu_added_by')->nullable();
            $table->dateTime('csu_last_update')->nullable();
            $table->dateTime('csu_added_date')->nullable();
            $table->unsignedBigInteger('classes_id')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('csu_updated_by')->references('id')->on('users')->nullable();
            $table->foreign('apn_id')->references('id')->on('application_info')->nullable();
            $table->foreign('csu_added_by')->references('id')->on('users')->nullable();
            $table->foreign('classes_id')->references('id')->on('classes')->nullable();
        });
    } */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_show_up');
    }
}
