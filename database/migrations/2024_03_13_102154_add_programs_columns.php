<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProgramsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programs', function (Blueprint $table) {

            $table->string('id_creation')->nullable();
            $table->string('pre_emps')->nullable();


            DB::statement('ALTER TABLE programs MODIFY b2 VARCHAR(255)');


            $table->renameColumn('b2', 'program_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('programs', function (Blueprint $table) {
            // Drop added columns
            $table->dropColumn('id_creation');
            $table->dropColumn('pre_emps');


            $table->boolean('program_type')->change();
            $table->renameColumn('program_type', 'b2');
        });
    }
}
