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
            $table->unsignedBigInteger('two_dimensional_id')->nullable();
            $table->boolean('is_active')->nullable(); //add
            $table->string('within_sla')->nullable();
            $table->string('condition')->nullable(); //add
            $table->string('requested_by')->nullable(); //add
            $table->date('original_start_date')->nullable(); //add
            $table->string('changes')->nullable(); //add
            $table->date('agreed_start_date')->nullable();
            $table->date('approved_date')->nullable(); //cancel
            $table->date('cancelled_date')->nullable(); //cancel
            $table->date('wfm_date_requested')->nullable(); //add
            $table->float('notice_weeks')->nullable(); //add
            $table->integer('external_target')->nullable(); //add
            $table->integer('internal_target')->nullable(); //add
            $table->integer('notice_days')->nullable(); //add
            $table->integer('pipeline_utilized')->nullable(); //cancel
            $table->integer('total_target')->nullable(); //add
            $table->string('remarks')->nullable(); //add
            $table->string('status')->nullable(); //add
            $table->string('category')->nullable(); //add
            $table->string('type_of_hiring')->nullable(); //add
            $table->integer('update_status')->nullable(); //pushback
            $table->string('approved_status')->nullable(); //add
            $table->string('with_erf')->nullable(); //add
            $table->string('erf_number')->nullable(); //add
            $table->string('approved_by')->nullable(); //cancel
            $table->string('cancelled_by')->nullable(); //cancel
            $table->string('ta')->nullable();
            $table->string('wf')->nullable();
            $table->string('tr')->nullable();
            $table->string('cl')->nullable();
            $table->string('op')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); //add
            $table->unsignedBigInteger('site_id')->nullable(); //add
            $table->unsignedBigInteger('program_id')->nullable(); //add
            $table->unsignedBigInteger('updated_by')->nullable(); //pushback
            $table->unsignedBigInteger('date_range_id')->nullable();
            $table->timestamps();
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
