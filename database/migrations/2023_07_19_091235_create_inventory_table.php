<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->string('transaction_no');
            $table->string('transaction_type');
            $table->string('request_type');
            $table->string('status');
            $table->string('award_status');
            $table->string('denial_reason');
            $table->string('remarks');
            $table->date('date_released');
            $table->date('date_requested');
            $table->date('date_approved');
            $table->date('date_denied');
            $table->date('date_received');
            $table->string('awardee_name');
            $table->string('awardee_hrid');
            $table->string('file_name');
            $table->string('path');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('released_by');
            $table->unsignedBigInteger('approved_by');
            $table->unsignedBigInteger('denied_by');
            $table->unsignedBigInteger('received_by');
            $table->unsignedBigInteger('processed_by');
            $table->unsignedBigInteger('requested_by');
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('released_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
            $table->foreign('denied_by')->references('id')->on('users');
            $table->foreign('received_by')->references('id')->on('users');
            $table->foreign('processed_by')->references('id')->on('users');
            $table->foreign('requested_by')->references('id')->on('users');
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
        Schema::dropIfExists('inventory');
    }
}
