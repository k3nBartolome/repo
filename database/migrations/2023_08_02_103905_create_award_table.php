<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award', function (Blueprint $table) {
            $table->id();
            $table->string('award_status')->nullable();
            $table->string('remarks')->nullable();
            $table->date('date_released')->nullable();
            $table->string('awardee_name')->nullable();
            $table->string('awardee_hrid')->nullable();
            $table->string('file_name')->nullable();
            $table->string('path')->nullable();
            $table->unsignedBigInteger('inventory_item_id')->nullable();
            $table->unsignedBigInteger('released_by')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('released_by')->references('id')->on('users');
            $table->foreign('processed_by')->references('id')->on('users');
            $table->foreign('inventory_item_id')->references('id')->on('site_inventory');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('award');
    }
}
