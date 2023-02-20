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
            $table->date('original_start_date');
            $table->date('pushback_start_date_ta')->nullable();
            $table->date('pushback_start_date_wf')->nullable();
            $table->date('requested_start_date_by_wf')->nullable();
            $table->date('start_date_committed_by_ta')->nullable();
            $table->date('supposed_start_date')->nullable();
            $table->datetime('approved_date')->nullable();
            $table->datetime('cancelled_date')->nullable();
            $table->date('wfm_date_requested');
            $table->float('notice_weeks');
            $table->integer('external_target');
            $table->integer('internal_target');
            $table->integer('notice_days');
            $table->integer('pipeline_utilized')->nullable();
            $table->integer('total_target');
            $table->string('reason_for_counter_proposal')->nullable();
            $table->string('remarks');
            $table->string('status');
            $table->string('type_of_hiring');
            $table->integer('backfill')->nullable();
            $table->integer('growth')->nullable();
            $table->string('update_status')->nullable();
            $table->string('approved_status');
            $table->string('with_erf');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->unsignedBigInteger('sla_reason_id')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
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
