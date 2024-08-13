<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->datetime('hire_date')->nullable();
            $table->string('team')->nullable();
            $table->string('immediate_supervisor_hrid')->nullable();
            $table->string('immediate_supervisor_name')->nullable();
            $table->string('work_setup')->nullable();
            $table->string('offer_target')->nullable();
            $table->string('offer_category_doc')->nullable();
            $table->string('required_program_specific')->nullable();
            $table->string('program_specific_id')->nullable();
            $table->string('basic_pay_training')->nullable();
            $table->string('basic_pay_production')->nullable();
            $table->string('night_differential_training')->nullable();
            $table->string('night_differential_production')->nullable();
            $table->string('bonus_training')->nullable();
            $table->string('bonus_production')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn([
                'start_date',
                'end_date',
                'hire_date',
                'team',
                'immediate_supervisor_hrid',
                'immediate_supervisor_name',
                'work_setup',
                'offer_target',
                'offer_category_doc',
                'required_program_specific',
                'program_specific_id',
                'basic_pay_training',
                'basic_pay_production',
                'night_differential_training',
                'night_differential_production',
                'bonus_training',
                'bonus_production'
            ]);
        });
    }
}
