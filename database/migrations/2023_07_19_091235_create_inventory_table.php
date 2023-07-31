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
            $table->unsignedBigInteger('inventory_id')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('quantity_approved')->nullable();
            $table->string('request_type')->nullable();
            $table->string('status')->nullable();
            $table->string('award_status')->nullable();
            $table->string('denial_reason')->nullable();
            $table->string('remarks')->nullable();
            $table->date('date_released')->nullable();
            $table->date('date_requested')->nullable();
            $table->date('date_approved')->nullable();
            $table->date('date_denied')->nullable();
            $table->date('date_received')->nullable();
            $table->string('awardee_name')->nullable();
            $table->string('awardee_hrid')->nullable();
            $table->string('file_name')->nullable();
            $table->string('path')->nullable();
            $table->boolean('is_active')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->unsignedBigInteger('released_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('denied_by')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
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
