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
            $table->string('cancelled_by');
            $table->string('cancelled_date');
            $table->string('completion_status');
            $table->string('created_by');
            $table->string('created_date');
            $table->string('date_requested');
            $table->string('delivery_date');
            $table->string('entry_date');
            $table->string('external_target');
            $table->string('hc');
            $table->string('internal_target');
            $table->boolean('is_active');
            $table->string('notice_days');
            $table->string('notice_weeks');
            $table->string('original_start_date');
            $table->string('pipeline_utilized');
            $table->string('program_id');
            $table->string('pushback_start_date_ta');
            $table->string('pushback_start_date_wf');
            $table->string('reason_for_counter_proposal');
            $table->string('remarks');
            $table->string('requested_start_date_by_wf');
            $table->string('requested_by');
            $table->string('sd');
            $table->string('site_id');
            $table->string('start_date_committed_by_ta');
            $table->string('status');
            $table->string('supposed_start_date');
            $table->string('target');
            $table->string('type_of_hiring');
            $table->string('update_status');
            $table->string('updated_date');
            $table->string('wfm_date_requested');
            $table->string('with_erf');
            $table->string('within_sla');
            $table->string('within_sla_tagging');
            $table->string('approved_by');
            $table->string('approved_date');
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
