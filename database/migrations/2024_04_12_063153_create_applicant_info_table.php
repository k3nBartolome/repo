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
                $table->string('ai_sr_id');
                $table->string('ai_first_name');
                $table->string('ai_middle_name');
                $table->string('ai_last_name');
                $table->string('ai_suffix');
                $table->string('ai_email_address');
                $table->string('ai_contact_number');
                $table->string('ai_marital_status');
                $table->string('ai_city_municipality');
                $table->string('ai_complete_address');
                $table->string('ai_gender');
                $table->integer('ai_age');
                $table->date('ai_birthdate');
                $table->string('ai_hrid');
                $table->string('ai_highest_educational_attainment');
                $table->string('ai_last_school_attended');
                $table->string('ai_year_graduated');
                $table->string('ai_course_strand');
                $table->unsignedBigInteger('ai_leads_added_by');
                $table->datetime('ai_leads_added_date');
                $table->datetime('ai_last_update_date');
                $table->unsignedBigInteger('ai_last_updated_by');
                $table->timestamps();
            $table->foreign('ai_last_updated_by')->references('id')->on('users');
            $table->foreign('apn_leads_added_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_info', function (Blueprint $table) {
            $table->dropForeign(['ai_last_updated_by']);
            $table->dropForeign(['ai_leads_added_by']);
        });

        Schema::dropIfExists('applicant_info');
    }
}
