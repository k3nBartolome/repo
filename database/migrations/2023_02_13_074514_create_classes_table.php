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
            $table->unsignedBigInteger('pushedback_id')->nullable();
            $table->boolean('is_active'); //add
            $table->string('within_sla'); //add
            $table->date('original_start_date'); //add
            $table->date('pushback_start_date_ta')->nullable(); //pushback
            $table->date('pushback_start_date_wf')->nullable(); //pushback
            $table->date('requested_start_date_by_wf')->nullable(); //pushback
            $table->date('start_date_committed_by_ta')->nullable(); //pushback
            $table->datetime('approved_date')->nullable(); //cancel
            $table->datetime('cancelled_date')->nullable(); //cancel
            $table->date('wfm_date_requested'); //add
            $table->float('notice_weeks'); //add
            $table->integer('external_target'); //add
            $table->integer('internal_target'); //add
            $table->integer('notice_days'); //add
            $table->integer('pipeline_utilized')->nullable(); //cancel
            $table->integer('total_target'); //add
            $table->string('remarks'); //add
            $table->integer('status'); //add
            $table->string('category'); //add
            $table->string('type_of_hiring'); //add
            $table->integer('backfill')->nullable(); //add
            $table->integer('growth')->nullable(); //add
            $table->integer('update_status')->nullable(); //pushback
            $table->string('approved_status'); //add
            $table->string('with_erf'); //add
            $table->unsignedBigInteger('approved_by')->nullable(); //cancel
            $table->unsignedBigInteger('cancelled_by')->nullable(); //cancel
            $table->unsignedBigInteger('created_by')->nullable(); //add
            $table->unsignedBigInteger('site_id')->nullable(); //add
            $table->unsignedBigInteger('program_id')->nullable(); //add
            $table->unsignedBigInteger('sla_reason_id')->nullable(); //add
            $table->unsignedBigInteger('updated_by')->nullable(); //pushback
            $table->unsignedBigInteger('date_range_id')->nullable(); //add
            $table->timestamps();
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('date_range_id')->references('id')->on('date_ranges')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
