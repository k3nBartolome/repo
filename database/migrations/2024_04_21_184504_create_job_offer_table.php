<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   /*  public function up()
    {
        Schema::create('job_offer', function (Blueprint $table) {
            $table->id();
            $table->string('jo_poc')->nullable();
            $table->dateTime('jo_date')->nullable();
            $table->string('jo_month')->nullable();
            $table->dateTime('jo_lead_time')->nullable();
            $table->dateTime('jo_start_date')->nullable();
            $table->string('jo_start_month')->nullable();
            $table->string('jo_status')->nullable();
            $table->string('jo_type')->nullable();
            $table->string('jo_position')->nullable();
            $table->string('jo_remarks')->nullable();
            $table->unsignedBigInteger('jo_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id')->nullable();
            $table->unsignedBigInteger('jo_added_by')->nullable();
            $table->dateTime('jo_last_update')->nullable();
            $table->dateTime('jo_added_date')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('jo_updated_by')->references('id')->on('users')->nullable();
            $table->foreign('apn_id')->references('id')->on('application_info')->nullable();
            $table->foreign('jo_added_by')->references('id')->on('users')->nullable();
        });
    } */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_offer');
    }
}
