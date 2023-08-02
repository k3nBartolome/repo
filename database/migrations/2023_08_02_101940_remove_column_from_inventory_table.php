<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnFromInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropForeign(['released_by']);
            $table->dropForeign(['processed_by']);
            $table->dropColumn(['award_status', 'remarks', 'date_released', 'awardee_name', 'awardee_hrid', 'file_name', 'path', 'released_by', 'processed_by']);
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
            $table->foreign('released_by')->references('id')->on('users');
            $table->foreign('processed_by')->references('id')->on('users');
            $table->string('award_status')->nullable();
            $table->string('remarks')->nullable();
            $table->date('date_released')->nullable();
            $table->string('awardee_name')->nullable();
            $table->string('awardee_hrid')->nullable();
            $table->string('file_name')->nullable();
            $table->string('path')->nullable();
        });
    }
}
