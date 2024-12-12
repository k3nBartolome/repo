<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_data', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('site_id')->nullable(); // Foreign key to applicant_sites
            $table->string('first_name')->nullable(); // First name of the applicant
            $table->string('middle_name')->nullable(); // Middle name (nullable)
            $table->string('last_name')->nullable(); // Last name of the applicant
            $table->string('email')->unique()->nullable(); // Email address (unique)
            $table->string('contact_number')->nullable(); // Contact number
            $table->timestamps(); // Created_at and updated_at timestamps
            
            // Define the foreign key constraint
            $table->foreign('site_id')
                  ->references('id')
                  ->on('applicant_site'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_data');
    }
}
