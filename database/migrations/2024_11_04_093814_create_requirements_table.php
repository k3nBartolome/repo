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
            $table->unsignedBigInteger('employee_tbl_id')->nullable();

            // NBI Section
            $table->string('nbi_final_status')->nullable();
            $table->date('nbi_validity_date')->nullable();
            $table->date('nbi_submitted_date')->nullable();
            $table->date('nbi_printed_date')->nullable();
            $table->text('nbi_remarks')->nullable();
            $table->string('nbi_file_name')->nullable();
            $table->timestamp('nbi_last_updated_at')->nullable();
            $table->unsignedBigInteger('nbi_updated_by')->nullable();
            // DT Section
            $table->string('dt_final_status')->nullable();
            $table->date('dt_results_date')->nullable();
            $table->date('dt_transaction_date')->nullable();
            $table->date('dt_endorsed_date')->nullable();
            $table->text('dt_remarks')->nullable();
            $table->string('dt_file_name')->nullable();
            $table->timestamp('dt_last_updated_at')->nullable();
            $table->unsignedBigInteger('dt_updated_by')->nullable();
            // PEME Section
            $table->string('peme_file_name')->nullable();
            $table->text('peme_remarks')->nullable();
            $table->date('peme_endorsed_date')->nullable();
            $table->date('peme_results_date')->nullable();
            $table->date('peme_transaction_date')->nullable();
            $table->string('peme_final_status')->nullable();
            $table->timestamp('peme_last_updated_at')->nullable();
            $table->unsignedBigInteger('peme_updated_by')->nullable();
            // SSS Section
            $table->string('sss_proof_submitted_type')->nullable();
            $table->string('sss_final_status')->nullable();
            $table->date('sss_submitted_date')->nullable();
            $table->text('sss_remarks')->nullable();
            $table->string('sss_number')->nullable();
            $table->string('sss_file_name')->nullable();
            $table->timestamp('sss_last_updated_at')->nullable();
            $table->unsignedBigInteger('sss_updated_by')->nullable();
            // PHIC Section
            $table->date('phic_submitted_date')->nullable();
            $table->string('phic_final_status')->nullable();
            $table->string('phic_proof_submitted_type')->nullable();
            $table->text('phic_remarks')->nullable();
            $table->string('phic_number')->nullable();
            $table->string('phic_file_name')->nullable();
            $table->timestamp('phic_last_updated_at')->nullable();
            $table->unsignedBigInteger('phic_updated_by')->nullable();
            // PAGIBIG Section
            $table->date('pagibig_submitted_date')->nullable();
            $table->string('pagibig_final_status')->nullable();
            $table->string('pagibig_proof_submitted_type')->nullable();
            $table->text('pagibig_remarks')->nullable();
            $table->string('pagibig_number')->nullable();
            $table->string('pagibig_file_name')->nullable();
            $table->timestamp('pagibig_last_updated_at')->nullable();
            $table->unsignedBigInteger('pagibig_updated_by')->nullable();
            // HC Section
            $table->date('health_certificate_validity_date')->nullable();
            $table->date('health_certificate_submitted_date')->nullable();
            $table->text('health_certificate_remarks')->nullable();
            $table->string('health_certificate_file_name')->nullable();
            $table->string('health_certificate_final_status')->nullable();
            $table->timestamp('health_certificate_last_updated_at')->nullable();
            $table->unsignedBigInteger('health_certificate_updated_by')->nullable();
            // OC Section
            $table->date('occupational_permit_validity_date')->nullable();
            $table->date('occupational_permit_submitted_date')->nullable();
            $table->text('occupational_permit_remarks')->nullable();
            $table->string('occupational_permit_file_name')->nullable();
            $table->string('occupational_permit_final_status')->nullable();
            $table->timestamp('occupational_permit_last_updated_at')->nullable();
            $table->unsignedBigInteger('occupational_permit_updated_by')->nullable();
            // OFAC Section
            $table->date('ofac_checked_date')->nullable();
            $table->string('ofac_final_status')->nullable();
            $table->text('ofac_remarks')->nullable();
            $table->string('ofac_file_name')->nullable();
            $table->timestamp('ofac_last_updated_at')->nullable();
            $table->unsignedBigInteger('ofac_updated_by')->nullable();
            // SAM Section
            $table->date('sam_checked_date')->nullable();
            $table->string('sam_final_status')->nullable();
            $table->text('sam_remarks')->nullable();
            $table->string('sam_file_name')->nullable();
            $table->timestamp('sam_last_updated_at')->nullable();
            $table->unsignedBigInteger('sam_updated_by')->nullable();
            $table->date('oig_checked_date')->nullable();
            $table->string('oig_final_status')->nullable();
            $table->text('oig_remarks')->nullable();
            $table->string('oig_file_name')->nullable();
            $table->timestamp('oig_last_updated_at')->nullable();
            $table->unsignedBigInteger('oig_updated_by')->nullable();
            // CIBI Section
            $table->date('cibi_checked_date')->nullable();
            $table->string('cibi_final_status')->nullable();
            $table->text('cibi_remarks')->nullable();
            $table->string('cibi_file_name')->nullable();
            $table->timestamp('cibi_last_updated_at')->nullable();
            $table->unsignedBigInteger('cibi_updated_by')->nullable();

            // BGC Section
            $table->date('bgc_endorsed_date')->nullable();
            $table->date('bgc_results_date')->nullable();
            $table->string('bgc_final_status')->nullable();
            $table->text('bgc_remarks')->nullable();
            $table->string('bgc_file_name')->nullable();
            $table->timestamp('bgc_last_updated_at')->nullable();
            $table->unsignedBigInteger('bgc_updated_by')->nullable();
            // BC Section
            $table->string('birth_certificate_file_name')->nullable();
            $table->date('birth_certificate_submitted_date')->nullable();
            $table->string('birth_certificate_proof_type')->nullable();
            $table->text('birth_certificate_remarks')->nullable();
            $table->timestamp('birth_certificate_last_updated_at')->nullable();
            $table->unsignedBigInteger('birth_certificate_updated_by')->nullable();
            // DBC Section
            $table->string('dependent_birth_certificate_file_name')->nullable();
            $table->date('dependent_birth_certificate_submitted_date')->nullable();
            $table->string('dependent_birth_certificate_proof_type')->nullable();
            $table->text('dependent_birth_certificate_remarks')->nullable();
            $table->timestamp('dependent_birth_certificate_last_updated_at')->nullable();
            $table->unsignedBigInteger('dependent_birth_certificate_updated_by')->nullable();
            // MC Section
            $table->string('marriage_certificate_file_name')->nullable();
            $table->date('marriage_certificate_submitted_date')->nullable();
            $table->string('marriage_certificate_proof_type')->nullable();
            $table->text('marriage_certificate_remarks')->nullable();
            $table->timestamp('marriage_certificate_last_updated_at')->nullable();
            $table->unsignedBigInteger('marriage_certificate_updated_by')->nullable();
            // SR Section
            $table->string('scholastic_record_file_name')->nullable();
            $table->date('scholastic_record_submitted_date')->nullable();
            $table->string('scholastic_record_proof_type')->nullable();
            $table->text('scholastic_record_remarks')->nullable();
            $table->timestamp('scholastic_record_last_updated_at')->nullable();
            $table->unsignedBigInteger('scholastic_record_updated_by')->nullable();
            // PE Section
            $table->string('previous_employment_file_name')->nullable();
            $table->date('previous_employment_submitted_date')->nullable();
            $table->string('previous_employment_proof_type')->nullable();
            $table->text('previous_employment_remarks')->nullable();
            $table->timestamp('previous_employment_last_updated_at')->nullable();
            $table->unsignedBigInteger('previous_employment_updated_by')->nullable();
            // SD Section
            $table->string('supporting_documents_file_name')->nullable();
            $table->date('supporting_documents_submitted_date')->nullable();
            $table->string('supporting_documents_proof_type')->nullable();
            $table->text('supporting_documents_remarks')->nullable();
            $table->timestamp('supporting_documents_last_updated_at')->nullable();
            $table->unsignedBigInteger('supporting_documents_updated_by')->nullable();

            $table->timestamps();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('nbi_updated_by')->references('id')->on('users');
            $table->foreign('dt_updated_by')->references('id')->on('users');
            $table->foreign('peme_updated_by')->references('id')->on('users');
            $table->foreign('sss_updated_by')->references('id')->on('users');
            $table->foreign('phic_updated_by')->references('id')->on('users');
            $table->foreign('pagibig_updated_by')->references('id')->on('users');
            $table->foreign('health_certificate_updated_by')->references('id')->on('users');
            $table->foreign('occupational_permit_updated_by')->references('id')->on('users');
            $table->foreign('ofac_updated_by')->references('id')->on('users');
            $table->foreign('sam_updated_by')->references('id')->on('users');
            $table->foreign('oig_updated_by')->references('id')->on('users');
            $table->foreign('cibi_updated_by')->references('id')->on('users');
            $table->foreign('bgc_updated_by')->references('id')->on('users');
            $table->foreign('birth_certificate_updated_by')->references('id')->on('users');
            $table->foreign('dependent_birth_certificate_updated_by')->references('id')->on('users');
            $table->foreign('marriage_certificate_updated_by')->references('id')->on('users');
            $table->foreign('scholastic_record_updated_by')->references('id')->on('users');
            $table->foreign('previous_employment_updated_by')->references('id')->on('users');
            $table->foreign('supporting_documents_updated_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('employee_tbl_id')->references('id')->on('employees');
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
