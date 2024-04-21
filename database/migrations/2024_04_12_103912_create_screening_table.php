<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScreeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id()->nullable();
            $table->string('sc_scheduled_by')->nullable();
            $table->string('sc_processed_by')->nullable();
            $table->string('sc_recruiter_notes')->nullable();
            $table->time('sc_scheduled_timeslot')->nullable();
            $table->time('sc_time_assigned')->nullable();
            $table->string('sc_status')->nullable();
            $table->datetime('sc_start')->nullable();
            $table->datetime('sc_end')->nullable();
            $table->decimal('sc_aht', 5, 2)->nullable()->nullable();
            $table->unsignedBigInteger('sc_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id')->nullable();
            $table->string('sc_remarks')->nullable()->nullable();
            $table->unsignedBigInteger('sc_added_by')->nullable();
            $table->datetime('sc_last_update')->useCurrent()->nullable();
            $table->datetime('sc_added_date')->useCurrent()->nullable();
            $table->foreign('sc_updated_by')->references('id')->on('users')->nullable();
            $table->foreign('sc_added_by')->references('id')->on('users')->nullable();
            $table->foreign('apn_id')->references('id')->on('application_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('screening');
    }
}
