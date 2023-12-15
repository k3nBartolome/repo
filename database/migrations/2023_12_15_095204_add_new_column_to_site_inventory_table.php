<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToSiteInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_inventory', function (Blueprint $table) {
            // Add the missing column
            $table->unsignedBigInteger('created_by')->nullable();

            // Add the foreign key constraint
            $table->foreign('created_by')->references('id')->on('users')->nullable();

            // Add other columns if needed
            $table->datetime('date_added')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_inventory', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['created_by']);

            // Drop the added columns
            $table->dropColumn(['created_by', 'date_added']);
        });
    }
}
