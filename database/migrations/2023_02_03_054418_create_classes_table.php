<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('external_target');
            $table->string('internal_target');
            $table->string('att_tagging');
            $table->string('wfm_date_requested');
            $table->string('type_of_hiring');
            $table->string('sd');
            $table->string('date_requested');
            $table->string('remarks');
            $table->string('pipeline_utilized');
            $table->string('pipeline_offered');
            $table->string('completion_status');
            $table->string('delivery_date');
            $table->string('target');
            $table->string('status');
            $table->string('created_by');
            $table->string('requested_by');
            $table->string('update_status');
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
        Schema::dropIfExists('classes');
    }
}
