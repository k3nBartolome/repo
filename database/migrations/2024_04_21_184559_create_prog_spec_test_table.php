<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgSpecTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   /*  public function up()
    {
        Schema::create('prog_spec_test', function (Blueprint $table) {
            $table->id();
            $table->dateTime('pst_date')->nullable();
            $table->string('pst_score')->nullable();
            $table->string('pst_type')->nullable();
            $table->string('pst_status')->nullable();
            $table->unsignedBigInteger('pst_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id')->nullable();
            $table->string('pst_remarks')->nullable();
            $table->unsignedBigInteger('pst_added_by')->nullable();
            $table->dateTime('pst_last_update')->nullable();
            $table->dateTime('pst_added_date')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('pst_updated_by')->references('id')->on('users')->nullable();
            $table->foreign('apn_id')->references('id')->on('application_info')->nullable();
            $table->foreign('pst_added_by')->references('id')->on('users')->nullable();
        });
    } */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prog_spec_test');
    }
}
