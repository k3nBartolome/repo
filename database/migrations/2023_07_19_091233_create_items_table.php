<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_less_id')->nullable();
            $table->string('item_name');
            $table->integer('quantity')->default(0);
            $table->integer('original_quantity');
            $table->decimal('cost');
            $table->decimal('total_cost', 10, 2);
            $table->string('budget_code', 10, 2);
            $table->string('type');
            $table->string('category');
            $table->date('date_expiry')->nullable();
            $table->boolean('is_active')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('site_id');
            $table->foreign('site_id')->references('id')->on('sites');
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
        Schema::dropIfExists('items');
    }
}
