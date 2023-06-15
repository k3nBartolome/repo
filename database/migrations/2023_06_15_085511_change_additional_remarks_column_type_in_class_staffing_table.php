<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAdditionalRemarksColumnTypeInClassStaffingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_staffing', function (Blueprint $table) {
            $table->text('additional_remarks')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_staffing', function (Blueprint $table) {
            $table->string('additional_remarks')->change();
        });
    }
}
