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

            $table->string('b2', 255)->change();
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

            $table->string('program_type', 1)->change(); // Change the length as per your needs
            $table->renameColumn('program_type', 'b2');
        });
    }
}
