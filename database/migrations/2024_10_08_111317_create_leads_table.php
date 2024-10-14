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
            $table->date('lead_date');
            $table->string('lead_source');
            $table->string('lead_type');
            $table->dateTime('lead_application_date');
            $table->date('lead_released_date')->nullable();
            $table->string('lead_srid');
            $table->string('lead_prism_status');
            $table->string('lead_site');
            $table->string('lead_last_name');
            $table->string('lead_first_name');
            $table->string('lead_middle_name')->nullable();
            $table->string('lead_contact_number');
            $table->string('lead_email_address');
            $table->string('lead_home_address');
            $table->string('lead_gen_source');
            $table->string('lead_spec_source');
            $table->string('lead_position');
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
