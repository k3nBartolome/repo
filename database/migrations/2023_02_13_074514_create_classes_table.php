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
            $table->boolean('is_active')->nullable();
            $table->string('within_sla')->nullable();
            $table->string('condition', 5000)->nullable();
            $table->string('requested_by')->nullable();
            $table->date('original_start_date')->nullable();
            $table->string('changes')->nullable();
            $table->date('agreed_start_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->date('cancelled_date')->nullable();
            $table->date('wfm_date_requested')->nullable();
            $table->float('notice_weeks')->nullable();
            $table->integer('external_target')->nullable();
            $table->integer('internal_target')->nullable();
            $table->integer('notice_days')->nullable();
            $table->integer('pipeline_utilized')->nullable();
            $table->integer('total_target')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable();
            $table->string('category')->nullable();
            $table->string('type_of_hiring')->nullable();
            $table->integer('update_status')->nullable();
            $table->string('approved_status')->nullable();
            $table->string('with_erf')->nullable();
            $table->string('erf_number')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->string('ta')->nullable();
            $table->string('wf')->nullable();
            $table->string('tr')->nullable();
            $table->string('cl')->nullable();
            $table->string('op')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('date_range_id')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index(['site_id', 'program_id', 'date_range_id', 'status']);

            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('program_id')->references('id')->on('programs');
            $table->foreign('date_range_id')->references('id')->on('date_ranges');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
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
