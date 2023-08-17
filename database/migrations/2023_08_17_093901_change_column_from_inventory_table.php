<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnFromInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->datetime('date_requested')->nullable()->change();
            $table->datetime('date_approved')->nullable()->change();
            $table->datetime('date_denied')->nullable()->change();
            $table->datetime('date_received')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->datetime('date_requested')->nullable()->change();
            $table->datetime('date_approved')->nullable()->change();
            $table->datetime('date_denied')->nullable()->change();
            $table->datetime('date_received')->nullable()->change();
        });
    }
}
