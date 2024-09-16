<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageInterviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_interview', function (Blueprint $table) {
            $table->id();
            $table->dateTime('li_started')->nullable();
            $table->dateTime('li_end')->nullable();
            $table->time('li_aht')->nullable();
            $table->dateTime('li_date')->nullable();
            $table->string('li_month')->nullable();
            $table->string('li_week')->nullable();
            $table->string('li_pron_accent')->nullable();
            $table->string('li_grammar')->nullable();
            $table->string('li_fluency')->nullable();
            $table->string('li_lexis')->nullable();
            $table->string('li_comprehension')->nullable();
            $table->string('li_tone')->nullable();
            $table->string('li_step_score')->nullable();
            $table->unsignedBigInteger('li_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id')->nullable();
            $table->string('li_status')->nullable();
            $table->string('li_remarks')->nullable();
            $table->unsignedBigInteger('li_added_by')->nullable();
            $table->dateTime('li_last_update')->nullable();
            $table->dateTime('li_added_date')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('li_updated_by')->references('id')->on('users')->nullable();
            $table->foreign('apn_id')->references('id')->on('application_info')->nullable();
            $table->foreign('li_added_by')->references('id')->on('users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_interview');
    }
}
