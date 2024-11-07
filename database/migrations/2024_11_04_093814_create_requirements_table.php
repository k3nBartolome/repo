<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_tbl_id');
            $table->string('nbi')->nullable();
            $table->text('nbi_remarks')->nullable();
            $table->date('nbi_validity')->nullable();
            $table->date('nbi_printed_date')->nullable();
            $table->string('nbi_file_name')->nullable();
            $table->string('nbi_file_path')->nullable();
            $table->string('dt_results')->nullable();
            $table->date('dt_transaction_date')->nullable();
            $table->date('dt_result_date')->nullable();
            $table->string('dt_file_name')->nullable();
            $table->string('dt_file_path')->nullable();
            $table->string('peme_status')->nullable();
            $table->text('peme_remarks')->nullable();
            $table->string('peme_file_name')->nullable();
            $table->string('peme_file_path')->nullable();
            $table->string('bgc')->nullable();
            $table->text('bgc_remarks')->nullable();
            $table->date('bgc_endorsed_date')->nullable();
            $table->date('bgc_received_date')->nullable();
            $table->string('bgc_file_name')->nullable();
            $table->string('bgc_file_path')->nullable();
            $table->string('vendor')->nullable();
            $table->string('sss_number')->nullable();
            $table->text('sss_remarks')->nullable();
            $table->string('sss_file_name')->nullable();
            $table->string('sss_file_path')->nullable();
            $table->string('phic_number')->nullable();
            $table->text('phic_remarks')->nullable();
            $table->string('phic_proof')->nullable();
            $table->string('phic_file_name')->nullable();
            $table->string('phic_file_path')->nullable();
            $table->string('hdmf_number')->nullable();
            $table->text('hdmf_remarks')->nullable();
            $table->string('hdmf_proof')->nullable();
            $table->string('hdmf_file_name')->nullable();
            $table->string('hdmf_file_path')->nullable();
            $table->string('tin')->nullable();
            $table->text('tin_remarks')->nullable();
            $table->string('tin_proof')->nullable();
            $table->string('tin_file_name')->nullable();
            $table->string('tin_file_path')->nullable();
            $table->string('form_1902')->nullable();
            $table->string('attachment_1902')->nullable();
            $table->string('1902_file_name')->nullable();
            $table->string('1902_file_path')->nullable();
            $table->string('health_certificate')->nullable();
            $table->string('health_certificate_file_name')->nullable();
            $table->string('health_certificate_file_path')->nullable();
            $table->string('vaccination_card')->nullable();
            $table->date('first_dose_date')->nullable();
            $table->date('second_dose_date')->nullable();
            $table->string('vaccination_card_file_name')->nullable();
            $table->string('vaccination_card_file_path')->nullable();
            $table->string('signed_bank_form')->nullable();
            $table->string('type_of_first_valid_id')->nullable();
            $table->string('type_of_second_valid_id')->nullable();
            $table->string('two_by_two')->nullable();
            $table->string('two_by_two_file_name')->nullable();
            $table->string('two_by_two_file_path')->nullable();
            $table->string('form_2316')->nullable();
            $table->string('form_2316_file_name')->nullable();
            $table->string('form_2316_file_path')->nullable();
            $table->string('nso_birth_certificate')->nullable();
            $table->string('nso_birth_certificate_file_name')->nullable();
            $table->string('nso_birth_certificate_file_path')->nullable();
            $table->string('dependents_nso_birth_certificate')->nullable();
            $table->string('dependents_nso_birth_certificate_file_name')->nullable();
            $table->string('dependents_nso_birth_certificate_file_path')->nullable();
            $table->string('marriage_certificate')->nullable();
            $table->string('marriage_certificate_file_name')->nullable();
            $table->string('marriage_certificate_file_path')->nullable();
            $table->string('cibi')->nullable();
            $table->date('cibi_search_date')->nullable();
            $table->string('ofac')->nullable();
            $table->string('sam')->nullable();
            $table->string('oig')->nullable();
            $table->string('month_milestone')->nullable();
            $table->string('week_ending')->nullable();
            $table->date('fifteenth_day_deadline')->nullable();
            $table->date('end_of_product_training')->nullable();
            $table->boolean('past_due')->default(false);
            $table->boolean('on_track')->default(false);
            $table->string('nbi_dt')->nullable();
            $table->string('job_offer_letter')->nullable();
            $table->string('interview_form_compliance')->nullable();
            $table->string('tmp_notes')->nullable();
            $table->string('tmp_ii')->nullable();
            $table->string('tmp_id')->nullable();
            $table->string('tmp_ov')->nullable();
            $table->string('tmp_status')->nullable();
            $table->string('jo_hr_contract_compliance')->nullable();
            $table->string('data_privacy_form')->nullable();
            $table->string('undertaking_non_employment_agreement')->nullable();
            $table->string('addendum_att')->nullable();
            $table->string('addendum_language_assessment')->nullable();
            $table->string('social_media_policy')->nullable();
            $table->text('contract_tmp_remarks')->nullable();
            $table->string('endorsed_by_hs')->nullable();
            $table->string('endorsed_to_compliance')->nullable();
            $table->string('return_to_hs_with_findings')->nullable();
            $table->string('last_received_from_hs_with_findings')->nullable();
            $table->string('status_201')->nullable();
            $table->text('compliance_remarks')->nullable();
            $table->boolean('with_findings')->default(false);
            $table->string('transmittal_to_act_hris_email_subject_sent')->nullable();
            $table->text('act_hris_remarks')->nullable();
            $table->timestamps();

            $table->foreign('employee_tbl_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requirements');
    }
}
