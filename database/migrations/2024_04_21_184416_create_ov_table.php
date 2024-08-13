<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
 /*    public function up()
    {
        Schema::create('ov', function (Blueprint $table) {
            $table->id();
            $table->string('ov_mode')->nullable();
            $table->string('ov_poc')->nullable();
            $table->string('ov_result')->nullable();
            $table->string('ov_status')->nullable();
            $table->string('ov_program')->nullable();
            $table->unsignedBigInteger('ov_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id')->nullable();
            $table->unsignedBigInteger('ov_added_by')->nullable();
            $table->dateTime('ov_last_update')->nullable();
            $table->dateTime('ov_added_date')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('ov_updated_by')->references('id')->on('users')->nullable();
            $table->foreign('apn_id')->references('id')->on('application_info')->nullable();
            $table->foreign('ov_added_by')->references('id')->on('users')->nullable();
        });
    } */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ov');
    }
}
