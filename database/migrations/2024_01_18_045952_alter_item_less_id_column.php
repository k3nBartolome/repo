<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterItemLessIdColumn extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Change the item_less_id column type
            $table->string('item_less_id', 255)->change();
        });
        Schema::table('site_inventory', function (Blueprint $table) {
            // Change the item_less_id column type
            $table->string('item_less_id', 255)->change();
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // If needed, revert the column type back to varchar(36)
            $table->string('item_less_id', 36)->change();
        });
        Schema::table('site_inventory', function (Blueprint $table) {
            // If needed, revert the column type back to varchar(36)
            $table->string('item_less_id', 36)->change();
        });
    }
}
