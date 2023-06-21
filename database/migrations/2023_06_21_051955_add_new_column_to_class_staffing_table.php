<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToClassStaffingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_staffing', function (Blueprint $table) {
            $table->integer('classes_number')->nullable();
            $table->unsignedBigInteger('cap_starts')->nullable();
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
            $table->integer('classes_number')->nullable();
            $table->unsignedBigInteger('cap_starts')->nullable();
        });
    }
}
