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
            $table->boolean('is_active');
            $table->boolean('within_sla');
            $table->date('delivery_date');
            $table->date('original_start_date');
            $table->date('pushback_start_date_ta');
            $table->date('pushback_start_date_wf');
            $table->date('requested_start_date_by_wf');
            $table->date('start_date_committed_by_ta');
            $table->date('supposed_start_date');
            $table->datetime('approved_date');
            $table->datetime('cancelled_date');
            $table->datetime('date_requested');
            $table->datetime('entry_date');
            $table->datetime('wfm_date_requested');
            $table->float('notice_weeks');
            $table->integer('external_target');
            $table->integer('internal_target');
            $table->integer('notice_days');
            $table->integer('pipeline_utilized');
            $table->integer('target');
            $table->string('reason_for_counter_proposal');
            $table->string('remarks');
            $table->string('status');
            $table->string('type_of_hiring');
            $table->integer('backfill');
            $table->integer('growth');
            $table->string('update_status');
            $table->string('approved_status');
            $table->string('with_erf');
            $table->unsignedBigInteger('approved_by');
            $table->unsignedBigInteger('cancelled_by');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('sla_reason_id');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            //$table->foreign('sla_reason_id')->references('id')->on('sla_reasons')->onDelete('cascade');
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
