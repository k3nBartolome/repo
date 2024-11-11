<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lob', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_tbl_id');
            $table->string('region')->nullable();
            $table->string('site')->nullable();
            $table->string('lob')->nullable();
            $table->string('team_name')->nullable();
            $table->string('project_code')->nullable();
            $table->timestamps();

            $table->foreign('employee_tbl_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lob');
    }
}
