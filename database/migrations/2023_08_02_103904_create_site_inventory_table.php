<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_inventory', function (Blueprint $table) {
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
        Schema::dropIfExists('site_inventory');
    }
}
