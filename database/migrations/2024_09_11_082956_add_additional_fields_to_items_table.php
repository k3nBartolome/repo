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
            // Add new columns if they do not already exist
            if (!Schema::hasColumn('items', 'transferred_by')) {
                $table->unsignedBigInteger('transferred_by')->nullable();
            }
            if (!Schema::hasColumn('items', 'transferred_from')) {
                $table->unsignedBigInteger('transferred_from')->nullable();
            }
            if (!Schema::hasColumn('items', 'transferred_to')) {
                $table->unsignedBigInteger('transferred_to')->nullable();
            }
            if (!Schema::hasColumn('items', 'transferred_date')) {
                $table->datetime('transferred_date')->nullable();
            }
            if (!Schema::hasColumn('items', 'received_by')) {
                $table->unsignedBigInteger('received_by')->nullable();
            }
            if (!Schema::hasColumn('items', 'date_received')) {
                $table->datetime('date_received')->nullable();
            }

            // Add foreign key constraints only if they do not already exist
            if (!Schema::hasColumn('items', 'transferred_by')) {
                $table->foreign('transferred_by', 'fk_items_transferred_by')->references('id')->on('users');
            }
            if (!Schema::hasColumn('items', 'received_by')) {
                $table->foreign('received_by', 'fk_items_received_by')->references('id')->on('users');
            }
            if (!Schema::hasColumn('items', 'transferred_to')) {
                $table->foreign('transferred_to', 'fk_items_transferred_to')->references('id')->on('sites');
            }
            if (!Schema::hasColumn('items', 'transferred_from')) {
                $table->foreign('transferred_from', 'fk_items_transferred_from')->references('id')->on('sites');
            }
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
            // Drop foreign key constraints only if they exist
            if (Schema::hasColumn('items', 'transferred_by')) {
                $table->dropForeign('fk_items_transferred_by');
            }
            if (Schema::hasColumn('items', 'received_by')) {
                $table->dropForeign('fk_items_received_by');
            }
            if (Schema::hasColumn('items', 'transferred_to')) {
                $table->dropForeign('fk_items_transferred_to');
            }
            if (Schema::hasColumn('items', 'transferred_from')) {
                $table->dropForeign('fk_items_transferred_from');
            }

            // Drop the columns if they exist
            if (Schema::hasColumn('items', 'transferred_by')) {
                $table->dropColumn('transferred_by');
            }
            if (Schema::hasColumn('items', 'transferred_from')) {
                $table->dropColumn('transferred_from');
            }
            if (Schema::hasColumn('items', 'transferred_to')) {
                $table->dropColumn('transferred_to');
            }
            if (Schema::hasColumn('items', 'transferred_date')) {
                $table->dropColumn('transferred_date');
            }
            if (Schema::hasColumn('items', 'received_by')) {
                $table->dropColumn('received_by');
            }
            if (Schema::hasColumn('items', 'date_received')) {
                $table->dropColumn('date_received');
            }
        });
    }
}
