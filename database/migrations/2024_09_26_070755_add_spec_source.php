<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecSource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*   public function up()
    {
        Schema::table('spec_source', function (Blueprint $table) {
            $table->id();
            $table->string('source_name');
            $table->unsignedBigInteger('gen_source_id');
            $table->timestamps();
            $table->foreign('lead_gen_source')->references('id')->on('gen_source')->nullable();
        });
    } */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    /*     public function down()
    {
        Schema::table('spec_source', function (Blueprint $table) {
        });
    } */
}
