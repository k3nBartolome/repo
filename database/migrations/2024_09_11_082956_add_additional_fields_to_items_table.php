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
            $table->unsignedBigInteger('transferred_by')->nullable()->after('file_path');
            $table->string('transferred_from')->nullable()->after('transferred_by');
            $table->unsignedInteger('transferred_quantity')->nullable()->after('transferred_from');
            $table->unsignedBigInteger('transferred_to')->nullable()->after('transferred_quantity');
            $table->date('transferred_date')->nullable()->after('transferred_to');
            $table->string('received_by')->nullable()->after('transferred_date');
            $table->date('date_received')->nullable()->after('received_by');

            // Add foreign key constraints
            $table->foreign('transferred_by')->references('id')->on('users');
            $table->foreign('received_by')->references('id')->on('users');
            $table->foreign('transferred_to')->references('id')->on('sites');
            $table->foreign('transferred_from')->references('id')->on('sites');
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
            $table->dropColumn('transferred_quantity');
            $table->dropColumn('transferred_to');
            $table->dropColumn('transferred_date');
            $table->dropColumn('received_by');
            $table->dropColumn('date_received');
        });
    }
}
