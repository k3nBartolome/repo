<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBciTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bci', function (Blueprint $table) {
            $table->id();
            $table->time('bci_aht')->nullable();
            $table->string('bci_integrity')->nullable();
            $table->string('bci_orientation')->nullable();
            $table->string('bci_psdm')->nullable();
            $table->string('bci_achievement_orientation')->nullable();
            $table->string('bci_stress_tolerance')->nullable();
            $table->string('bci_sales_focus')->nullable();
            $table->string('bci_score')->nullable();
            $table->string('bci_status')->nullable();
            $table->string('bci_notes')->nullable();
            $table->unsignedBigInteger('bci_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id')->nullable();
            $table->string('bci_remarks')->nullable();
            $table->unsignedBigInteger('bci_added_by')->nullable();
            $table->dateTime('bci_last_update')->nullable();
            $table->dateTime('bci_added_date')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('bci_updated_by')->references('id')->on('users')->nullable();
            $table->foreign('apn_id')->references('id')->on('application_info')->nullable();
            $table->foreign('bci_added_by')->references('id')->on('users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bci');
    }
}
