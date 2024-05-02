<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_info', function (Blueprint $table) {
            $table->id();
            $table->string('ai_sr_id')->nullable();
            $table->string('ai_first_name');
            $table->string('ai_middle_name')->nullable();
            $table->string('ai_last_name');
            $table->string('ai_suffix')->nullable();
            $table->string('ai_email_address')->nullable();
            $table->string('ai_contact_number')->nullable();
            $table->string('ai_marital_status')->nullable();
            $table->string('ai_city_municipality')->nullable();
            $table->string('ai_complete_address')->nullable();
            $table->string('ai_gender')->nullable();
            $table->integer('ai_age')->nullable();
            $table->date('ai_birthdate')->nullable();
            $table->string('ai_hrid')->nullable();
            $table->string('ai_highest_educational_attainment')->nullable();
            $table->string('ai_last_school_attended')->nullable();
            $table->string('ai_year_graduated')->nullable();
            $table->string('ai_course_strand')->nullable();
            $table->unsignedBigInteger('ai_leads_added_by')->nullable();
            $table->dateTime('ai_leads_added_date')->nullable();
            $table->dateTime('ai_last_update_date')->nullable();
            $table->unsignedBigInteger('ai_last_updated_by')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('ai_leads_added_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ai_last_updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_info');
    }
}
