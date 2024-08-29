<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileColumnsToSiteInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_inventory', function (Blueprint $table) {
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
        });
    }

    public function down()
    {
        Schema::table('site_inventory', function (Blueprint $table) {
            $table->dropColumn(['file_name', 'file_path']);
        });
    }
}
