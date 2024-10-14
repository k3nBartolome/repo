<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*     public function up()
    {
        Schema::table('position', function (Blueprint $table) {
            $table->id();
            $table->string('position_name');
            $table->string('description');
            $table->timestamps();
        });
    } */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    /* public function down()
    {
        Schema::table('position', function (Blueprint $table) {
            Schema::dropIfExists('position');
        });
    } */
}
