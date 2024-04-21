<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgSpecTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prog_spec_test', function (Blueprint $table) {
            $table->id();
            $table->date('pst_date');
            $table->integer('pst_score')->nullable();
            $table->string('pst_type')->nullable();
            $table->string('pst_status')->nullable();
            $table->string('pst_updated_by')->nullable();
            $table->unsignedBigInteger('apn_id');
            $table->text('pst_remarks')->nullable();
            $table->string('pst_added_by')->nullable();
            $table->dateTime('pst_last_update')->nullable();
            $table->dateTime('pst_added_date');
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
        Schema::dropIfExists('prog_spec_test');
    }
}
