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
            $table->text('nbi')->nullable();
            $table->text('nbi_remarks')->nullable();
            $table->date('nbi_validity_date')->nullable();
            $table->date('nbi_printed_date')->nullable();
            $table->text('nbi_file_name')->nullable();
            $table->text('nbi_file_path')->nullable();
            $table->text('dt_results')->nullable();
            $table->date('dt_transaction_date')->nullable();
            $table->date('dt_result_date')->nullable();
            $table->text('dt_file_name')->nullable();
            $table->text('dt_file_path')->nullable();
            $table->text('peme_status')->nullable();
            $table->text('peme_remarks')->nullable();
            $table->text('peme_file_name')->nullable();
            $table->text('peme_file_path')->nullable();
            $table->text('bgc')->nullable();
            $table->text('bgc_remarks')->nullable();
            $table->date('bgc_endorsed_date')->nullable();
            $table->date('bgc_received_date')->nullable();
            $table->text('bgc_file_name')->nullable();
            $table->text('bgc_file_path')->nullable();
            $table->text('vendor')->nullable();
            $table->text('sss_number')->nullable();
            $table->text('sss_remarks')->nullable();
            $table->text('sss_file_name')->nullable();
            $table->text('sss_file_path')->nullable();
            $table->text('phic_number')->nullable();
            $table->text('phic_remarks')->nullable();
            $table->text('phic_proof')->nullable();
            $table->text('phic_file_name')->nullable();
            $table->text('phic_file_path')->nullable();
            $table->text('hdmf_number')->nullable();
            $table->text('hdmf_remarks')->nullable();
            $table->text('hdmf_proof')->nullable();
            $table->text('hdmf_file_name')->nullable();
            $table->text('hdmf_file_path')->nullable();
            $table->text('tin')->nullable();
            $table->text('tin_remarks')->nullable();
            $table->text('tin_proof')->nullable();
            $table->text('tin_file_name')->nullable();
            $table->text('tin_file_path')->nullable();
            $table->text('form_1902')->nullable();
            $table->text('attachment_1902')->nullable();
            $table->text('1902_file_name')->nullable();
            $table->text('1902_file_path')->nullable();
            $table->text('health_certificate')->nullable();
            $table->text('health_certificate_file_name')->nullable();
            $table->text('health_certificate_file_path')->nullable();
            $table->text('vaccination_card')->nullable();
            $table->date('first_dose_date')->nullable();
            $table->date('second_dose_date')->nullable();
            $table->text('vaccination_card_file_name')->nullable();
            $table->text('vaccination_card_file_path')->nullable();
            $table->text('signed_bank_form')->nullable();
            $table->text('type_of_first_valid_id')->nullable();
            $table->text('type_of_second_valid_id')->nullable();
            $table->text('two_by_two')->nullable();
            $table->text('two_by_two_file_name')->nullable();
            $table->text('two_by_two_file_path')->nullable();
            $table->text('form_2316')->nullable();
            $table->text('form_2316_file_name')->nullable();
            $table->text('form_2316_file_path')->nullable();
            $table->text('nso_birth_certificate')->nullable();
            $table->text('nso_birth_certificate_file_name')->nullable();
            $table->text('nso_birth_certificate_file_path')->nullable();
            $table->text('dependents_nso_birth_certificate')->nullable();
            $table->text('dependents_nso_birth_certificate_file_name')->nullable();
            $table->text('dependents_nso_birth_certificate_file_path')->nullable();
            $table->text('marriage_certificate')->nullable();
            $table->text('marriage_certificate_file_name')->nullable();
            $table->text('marriage_certificate_file_path')->nullable();
            $table->text('cibi')->nullable();
            $table->date('cibi_search_date')->nullable();
            $table->text('ofac')->nullable();
            $table->text('sam')->nullable();
            $table->text('oig')->nullable();
            $table->text('month_milestone')->nullable();
            $table->text('week_ending')->nullable();
            $table->date('fifteenth_day_deadline')->nullable();
            $table->date('end_of_product_training')->nullable();
            $table->text('past_due')->nullable();
            $table->text('on_track')->nullable();
            $table->text('nbi_dt')->nullable();
            $table->text('job_offer_letter')->nullable();
            $table->text('interview_form_compliance')->nullable();
            $table->text('tmp_notes')->nullable();
            $table->text('tmp_ii')->nullable();
            $table->text('tmp_id')->nullable();
            $table->text('tmp_ov')->nullable();
            $table->text('tmp_status')->nullable();
            $table->text('jo_hr_contract_compliance')->nullable();
            $table->text('data_privacy_form')->nullable();
            $table->text('undertaking_non_employment_agreement')->nullable();
            $table->text('addendum_att')->nullable();
            $table->text('addendum_language_assessment')->nullable();
            $table->text('social_media_policy')->nullable();
            $table->text('contract_tmp_remarks')->nullable();
            $table->text('endorsed_by_hs')->nullable();
            $table->text('endorsed_to_compliance')->nullable();
            $table->text('return_to_hs_with_findings')->nullable();
            $table->text('last_received_from_hs_with_findings')->nullable();
            $table->text('status_201')->nullable();
            $table->text('compliance_remarks')->nullable();
            $table->text('with_findings')->nullable();
            $table->text('transmittal_to_act_hris_email_subject_sent')->nullable();
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
