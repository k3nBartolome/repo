<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScreeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   /*  public function up()
    {
        Schema::create('screening', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sc_scheduled_by')->nullable();
            $table->unsignedBigInteger('sc_processed_by')->nullable();
            $table->string('sc_recruiter_notes')->nullable();
            $table->dateTime('sc_scheduled_timeslot')->nullable();
            $table->dateTime('sc_time_assigned')->nullable();
            $table->string('sc_status')->nullable();
            $table->dateTime('sc_started')->nullable();
            $table->dateTime('sc_end')->nullable();
            $table->time('sc_aht')->nullable();
            $table->unsignedBigInteger('sc_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id')->nullable();
            $table->string('sc_remarks')->nullable();
            $table->unsignedBigInteger('sc_added_by')->nullable();
            $table->dateTime('sc_last_update')->nullable();
            $table->dateTime('sc_added_date')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('sc_scheduled_by')->references('id')->on('users')->nullable();
            $table->foreign('sc_processed_by')->references('id')->on('users')->nullable();
            $table->foreign('sc_updated_by')->references('id')->on('users')->nullable();
            $table->foreign('apn_id')->references('id')->on('application_info')->nullable();
            $table->foreign('sc_added_by')->references('id')->on('users')->nullable();
        });
    } */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('screening');
    }
}
