<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitialInterviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('initial_interview', function (Blueprint $table) {
            $table->id();
            $table->string('ii_ofac');
            $table->string('ii_final_step')->nullable();
            $table->string('ii_final_status')->nullable();
            $table->text('ii_status_remarks')->nullable();
            $table->string('ii_account');
            $table->string('ii_recruiter_name');
            $table->text('ii_profile_summary')->nullable();
            $table->text('ii_recruiter_notes')->nullable();
            $table->string('ii_cs_vertical')->nullable();
            $table->string('ii_sales_vertical')->nullable();
            $table->string('ii_tech_vertical')->nullable();
            $table->integer('ii_total_mos')->nullable();
            $table->integer('ii_recently_mos')->nullable();
            $table->integer('ii_tenure_in_last_job')->nullable();
            $table->integer('ii_tenure_in_last_job_month')->nullable();
            $table->string('ii_interviewer')->nullable();
            $table->dateTime('ii_time_started')->nullable();
            $table->dateTime('ii_time_ended')->nullable();
            $table->string('ii_aht')->nullable();
            $table->integer('ii_cs_score')->nullable();
            $table->integer('ii_srp_score')->nullable();
            $table->string('ii_status')->nullable();
            $table->text('ii_remarks')->nullable();
            $table->string('ii_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id');
            $table->string('ii_added_by')->nullable();
            $table->dateTime('ii_last_update')->nullable();
            $table->dateTime('ii_added_date');
            $table->string('ii_expected_salary')->nullable();
            $table->foreign('apn_id')->references('id')->on('application_info')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('initial_interview');
    }
}
