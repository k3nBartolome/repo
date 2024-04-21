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
    public function up()
    {
        Schema::create('job_offer', function (Blueprint $table) {
            $table->id();
            $table->string('jo_poc');
            $table->date('jo_date');
            $table->string('jo_month');
            $table->integer('jo_lead_time')->nullable();
            $table->date('jo_start_date')->nullable();
            $table->string('jo_start_month')->nullable();
            $table->string('jo_status')->nullable();
            $table->string('jo_type')->nullable();
            $table->string('jo_position')->nullable();
            $table->text('jo_remarks')->nullable();
            $table->string('jo_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id');
            $table->string('jo_added_by')->nullable();
            $table->dateTime('jo_last_update')->nullable();
            $table->dateTime('jo_added_date');
            $table->foreign('apn_id')->references('id')->on('application_info')->onDelete('cascade'); // replace 'another_table' with the actual table name where 'apn_id' references
            // add more foreign key constraints if necessary
            // e.g., $table->foreign('foreign_key_column')->references('referenced_column')->on('referenced_table')->onDelete('cascade');
        });
    }

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
