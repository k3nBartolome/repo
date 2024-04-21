<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ov', function (Blueprint $table) {
            $table->id();
            $table->string('ov_mode');
            $table->string('ov_poc');
            $table->string('ov_result')->nullable();
            $table->string('ov_status')->nullable();
            $table->string('ov_program')->nullable();
            $table->string('ov_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id');
            $table->string('ov_added_by')->nullable();
            $table->dateTime('ov_last_update')->nullable();
            $table->dateTime('ov_added_date');
            $table->foreign('apn_id')->references('id')->on('application_info')->onDelete('cascade'); // replace 'another_table' with the actual table name where 'apn_id' references
            // add more foreign key constraints if necessary
            // e.g., $table->foreign('foreign_key_column')->references('referenced_column')->on('referenced_table')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ov');
    }
}
