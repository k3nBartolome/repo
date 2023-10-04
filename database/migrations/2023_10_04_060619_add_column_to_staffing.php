<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToStaffing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_staffing', function (Blueprint $table) {
            $table->integer('open')->nullable();
            $table->integer('filled')->nullable();
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
            $table->integer('open')->nullable();
            $table->integer('filled')->nullable();
        });
    }
}
