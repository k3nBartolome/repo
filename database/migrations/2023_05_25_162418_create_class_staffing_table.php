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
            $table->integer('day_1');
            $table->integer('day_2');
            $table->integer('day_3');
            $table->integer('day_4');
            $table->integer('day_5');
            $table->integer('day_6');
            $table->integer('day_7');
            $table->integer('day_8');
            $table->string('day_1_start_rate');
            $table->string('day_2_start_rate');
            $table->string('day_3_start_rate');
            $table->string('day_4_start_rate');
            $table->string('day_5_start_rate');
            $table->string('day_6_start_rate');
            $table->string('day_7_start_rate');
            $table->string('day_8_start_rate');
            $table->integer('total_endorsed');
            $table->decimal('endorsed_rate', 8, 2);
            $table->integer('internals');
            $table->integer('externals');
            $table->integer('with_jo');
            $table->integer('pending_jo');
            $table->integer('pending_berlitz');
            $table->integer('pending_ov');
            $table->integer('pending_pre_emps');
            $table->text('additional_remarks')->nullable();
            $table->integer('pipeline');
            $table->integer('show_ups_internal');
            $table->integer('show_ups_external');
            $table->integer('show_ups_total');
            $table->integer('deficit');
            $table->decimal('fill_Rate', 8, 2);
            $table->string('status');
            $table->integer('over_hires');
            $table->string('wave_no');
            $table->string('fallout_reason');
            $table->integer('target');
            $table->date('catch_up_start')->nullable();
            $table->decimal('catch_up_percentage', 8, 2);
            $table->unsignedBigInteger('classes_id');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('created_by');
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
