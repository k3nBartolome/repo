<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   /*  public function up()
    {
        Schema::create('application_info', function (Blueprint $table) {
            $table->id();
            $table->string('apn_segment')->nullable();
            $table->string('apn_shifter_type')->nullable();
            $table->string('apn_mode_of_application')->nullable();
            $table->string('apn_actual_mode_of_application')->nullable();
            $table->string('apn_lead_type')->nullable();
            $table->unsignedBigInteger('apn_site')->nullable();
            $table->integer('apn_ko')->nullable();
            $table->integer('apn_word_quiz')->nullable();
            $table->integer('apn_sva')->nullable();
            $table->string('apn_typing_test')->nullable();
            $table->string('apn_typing_test_accuracy')->nullable();
            $table->string('apn_typing_test_speed')->nullable();
            $table->string('apn_oot_non_oot')->nullable();
            $table->string('apn_gen_source')->nullable();
            $table->string('apn_specific_source')->nullable();
            $table->string('apn_name_of_event')->nullable();
            $table->string('apn_diser_name')->nullable();
            $table->string('apn_employee_referrer_name')->nullable();
            $table->string('apn_referrer_hrid')->nullable();
            $table->string('apn_referrer_account')->nullable();
            $table->string('apn_applicant_referrer_name')->nullable();
            $table->unsignedBigInteger('apn_updated_by')->nullable();
            $table->unsignedBigInteger('ai_id')->nullable();
            $table->unsignedBigInteger('apn_added_by')->nullable();
            $table->dateTime('apn_added_date')->nullable();
            $table->dateTime('apn_last_update')->nullable();
            $table->dateTime('apn_application_date')->nullable();
            $table->unsignedBigInteger('apn_application_week_ending')->nullable();
            $table->string('apn_application_month')->nullable();
            $table->dateTime('apn_leads_added_date')->nullable();
            $table->timestamps();


            $table->foreign('apn_site')->references('id')->on('sites')->nullable();
            $table->foreign('apn_updated_by')->references('id')->on('users')->nullable();
            $table->foreign('ai_id')->references('id')->on('applicant_info')->nullable();
            $table->foreign('apn_added_by')->references('id')->on('users')->nullable();
            $table->foreign('apn_application_week_ending')->references('id')->on('date_ranges')->nullable();
        });
    }
 */
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_info');
    }
}
