<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueFromEmailInApplicantData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_data', function (Blueprint $table) {
            // Remove the unique constraint on the email column
            $table->dropUnique('applicant_data_email_unique'); // The name of the index
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_data', function (Blueprint $table) {
            // Re-add the unique constraint on the email column if necessary
            $table->unique('email');
        });
    }
}
