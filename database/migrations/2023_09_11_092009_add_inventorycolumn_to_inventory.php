<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInventorycolumnToInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->unsignedBigInteger('inventory_item_id')->nullable();
            $table->foreign('inventory_item_id')
                    ->references('id')
                    ->on('site_inventory');
        });
    }

    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropForeign(['inventory_item_id']);
            $table->dropColumn('inventory_item_id');
        });
    }
}
