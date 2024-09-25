<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->date('lead_date');
            $table->string('lead_screener_name');
            $table->string('lead_source');
            $table->string('lead_type');
            $table->dateTime('lead_application_date');
            $table->date('lead_released_date')->nullable();
            $table->string('lead_srid');
            $table->string('lead_ko');
            $table->string('lead_word_quiz');
            $table->string('lead_sva');
            $table->string('lead_prism_status');
            $table->unsignedBigInteger('site_id');
            $table->string('lead_last_name');
            $table->string('lead_first_name');
            $table->string('lead_middle_name')->nullable();
            $table->string('lead_contact_number');
            $table->string('lead_email_address');
            $table->string('lead_home_address');
            $table->string('lead_region');
            $table->string('lead_oot_or_non');
            $table->string('lead_segment');
            $table->string('lead_shifter_type');
            $table->integer('lead_chat_experience');
            $table->string('lead_educational_attainment');
            $table->string('lead_course_or_strand');
            $table->string('lead_mode_application');
            $table->unsignedBigInteger('lead_general_source');
            $table->unsignedBigInteger('lead_specific_source');
            $table->boolean('lead_is_legal_age');
            $table->string('lead_status');
            $table->string('lead_remarks_status')->nullable();
            $table->text('lead_notes')->nullable();
            $table->string('lead_unique_link')->nullable();
            $table->time('lead_time_in')->nullable();
            $table->time('lead_time_out')->nullable();
            $table->time('lead_aht')->nullable();
            $table->string('lead_sr_movement')->nullable();
            $table->string('lead_pron_accent')->nullable();
            $table->string('lead_grammar')->nullable();
            $table->string('lead_fluency')->nullable();
            $table->string('lead_lexis')->nullable();
            $table->string('lead_comprehension')->nullable();
            $table->string('lead_tone')->nullable();
            $table->dateTime('lead_scheduled_date')->nullable();
            $table->dateTime('lead_reschedule_date')->nullable();
            $table->time('lead_scheduled_time')->nullable();
            $table->unsignedBigInteger('lead_scheduled_site_id');
            $table->string('lead_show_up_status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
