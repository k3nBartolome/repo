<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->date('lead_date')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('lead_type')->nullable();
            $table->dateTime('lead_application_date')->nullable();
            $table->date('lead_released_date')->nullable();
            $table->string('lead_srid')->nullable();
            $table->string('lead_prism_status')->nullable();
            $table->string('lead_site')->nullable();
            $table->string('lead_last_name')->nullable();
            $table->string('lead_first_name')->nullable();
            $table->string('lead_middle_name')->nullable();
            $table->string('lead_contact_number')->nullable();
            $table->string('lead_email_address')->nullable();
            $table->string('lead_home_address')->nullable();
            $table->string('lead_gen_source')->nullable();
            $table->string('lead_spec_source')->nullable();
            $table->string('lead_position')->nullable();
            $table->unsignedBigInteger('lead_assigned_to')->nullable();
            $table->unsignedBigInteger('lead_added_by')->nullable();
            $table->unsignedBigInteger('lead_updated_by')->nullable();
            $table->foreign('lead_assigned_to')->references('id')->on('users');
            $table->foreign('lead_added_by')->references('id')->on('users');
            $table->foreign('lead_updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('leads');
    }
}
