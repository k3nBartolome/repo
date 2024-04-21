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
            $table->dateTime('li_start');
            $table->dateTime('li_end');
            $table->string('li_aht');
            $table->date('li_date');
            $table->string('li_month');
            $table->string('li_meek');
            $table->string('li_pron_accent');
            $table->string('li_grammar');
            $table->string('li_fluency');
            $table->string('li_lexis');
            $table->string('li_comprehension');
            $table->string('li_tone');
            $table->integer('li_step_score');
            $table->string('li_updated_by');
            $table->unsignedBigInteger('apn_id');
            $table->string('li_status');
            $table->text('li_remarks')->nullable();
            $table->string('li_added_by');
            $table->dateTime('li_last_update')->nullable();
            $table->dateTime('li_added_date');
            $table->foreign('apn_id')->references('id')->on('application_info');
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
