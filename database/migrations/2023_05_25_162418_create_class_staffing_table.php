<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassStaffingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_staffing', function (Blueprint $table) {
            $table->id();
            $table->integer('day_1')->nullable();
            $table->integer('day_2')->nullable();
            $table->integer('day_3')->nullable();
            $table->integer('day_4')->nullable();
            $table->integer('day_5')->nullable();
            $table->integer('day_6')->nullable();
            $table->integer('day_7')->nullable();
            $table->integer('day_8')->nullable();
            $table->string('day_1_start_rate')->nullable();
            $table->string('day_2_start_rate')->nullable();
            $table->string('day_3_start_rate')->nullable();
            $table->string('day_4_start_rate')->nullable();
            $table->string('day_5_start_rate')->nullable();
            $table->string('day_6_start_rate')->nullable();
            $table->string('day_7_start_rate')->nullable();
            $table->string('day_8_start_rate')->nullable();
            $table->integer('total_endorsed')->nullable();
            $table->string('endorsed_rate')->nullable();
            $table->integer('internals_hires')->nullable();
            $table->integer('externals_hires')->nullable();
            $table->integer('internals_hires_all')->nullable();
            $table->integer('externals_hires_all')->nullable();
            $table->integer('additional_extended_jo')->nullable();
            $table->integer('with_jo')->nullable();
            $table->integer('pending_jo')->nullable();
            $table->integer('pending_berlitz')->nullable();
            $table->integer('pending_ov')->nullable();
            $table->integer('pending_pre_emps')->nullable();
            $table->string('additional_remarks')->nullable();
            $table->integer('pipeline')->nullable();
            $table->integer('pipeline_target')->nullable();
            $table->integer('pipeline_total')->nullable();
            $table->string('percentage')->nullable();
            $table->integer('show_ups_internal')->nullable();
            $table->integer('show_ups_external')->nullable();
            $table->integer('show_ups_total')->nullable();
            $table->integer('deficit')->nullable();
            $table->integer('deficit_total')->nullable();
            $table->string('fill_rate')->nullable();
            $table->string('hiring_status')->nullable();
            $table->string('status')->nullable();
            $table->integer('over_hires')->nullable();
            $table->string('fallout_reason')->nullable();
            $table->date('catch_up_start')->nullable();
            $table->string('catch_up_percentage')->nullable();
            $table->unsignedBigInteger('classes_id')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->foreign('classes_id')->references('id')->on('classes');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_staffing');
    }
}
