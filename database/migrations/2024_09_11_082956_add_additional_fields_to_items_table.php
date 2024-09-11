<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Add new columns
            $table->unsignedBigInteger('transferred_by')->nullable();
            $table->unsignedBigInteger('transferred_from')->nullable();
            $table->unsignedBigInteger('transferred_to')->nullable();
            $table->datetime('transferred_date')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->datetime('date_received')->nullable();

            // Add foreign key constraints
            $table->foreign('transferred_by')->references('id')->on('users');
            $table->foreign('received_by')->references('id')->on('users');
            $table->foreign('transferred_to')->references('id')->on('users');
            $table->foreign('transferred_from')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['transferred_by']);
            $table->dropForeign(['received_by']);
            $table->dropForeign(['transferred_to']);
            $table->dropForeign(['transferred_from']);

            // Drop the columns
            $table->dropColumn('transferred_by');
            $table->dropColumn('transferred_from');
            $table->dropColumn('transferred_to');
            $table->dropColumn('transferred_date');
            $table->dropColumn('received_by');
            $table->dropColumn('date_received');
        });
    }
}
