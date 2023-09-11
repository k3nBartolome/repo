<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostcolumnToInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->nullable();
        });
    }
}
