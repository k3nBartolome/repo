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
    public function up()
    {
        Schema::create('application_info', function (Blueprint $table) {
            $table->id();
            $table->string('apn_site_id');
            $table->string('apn_gen_source');
            $table->string('apn_spec_source');
            $table->string('apn_application_date');
            $table->string('apn_application_week_ending');
            $table->string('apn_application_month');
            $table->string('apn_lead_type');
            $table->string('apn_mode_of_application');
            $table->string('apn_actual_mode_of_application');
            $table->string('apn_name_of_event');
            $table->string('apn_segment');
            $table->string('apn_shifter_type');
            $table->string('apn_ko');
            $table->string('apn_word_quiz');
            $table->string('apn_sva');
            $table->string('apn_typing_test');
            $table->string('apn_typing_test_accuracy');
            $table->string('apn_typing_test_speed');
            $table->string('apn_oot_non_oot');
            $table->string('apn_diser_name');
            $table->string('apn_employee_referrer_name');
            $table->string('apn_employee_referrer_hrid');
            $table->string('apn_employee_referrer_account');
            $table->string('apn_applicant_referrer_name');
            $table->string('apn_referrer_by');
            $table->datetime('apn_last_update_date');
            $table->string('apn_added_by');
            $table->datetime('apn_added_date');
            $table->unsignedBigInteger('apn_last_updated_by');
            $table->unsignedBigInteger('ai_id');
            $table->foreign('apn_last_updated_by')->references('id')->on('users');
            $table->foreign('ai_id')->references('id')->on('applicant_info');




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('application_info', function (Blueprint $table) {
        $table->dropColumn([
            'apn_site_id',
            'apn_gen_source',
            'apn_spec_source',
            'apn_application_date',
            'apn_application_week_ending',
            'apn_application_month',
            'apn_lead_type',
            'apn_mode_of_application',
            'apn_actual_mode_of_application',
            'apn_name_of_event',
            'apn_segment',
            'apn_shifter_type',
            'apn_ko',
            'apn_word_quiz',
            'apn_sva',
            'apn_typing_test',
            'apn_typing_test_accuracy',
            'apn_typing_test_speed',
            'apn_oot_non_oot',
            'apn_diser_name',
            'apn_employee_referrer_name',
            'apn_employee_referrer_hrid',
            'apn_employee_referrer_account',
            'apn_applicant_referrer_name',
            'apn_referrer_by',
            'apn_last_update_date',
            'apn_added_by',
            'apn_added_date',
            'apn_last_updated_by',
            'ai_id',
        ]);
    });

    Schema::dropIfExists('application_info');
}
}
