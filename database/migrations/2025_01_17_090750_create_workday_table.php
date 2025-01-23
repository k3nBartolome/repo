<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workday_table', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_tbl_id');
            $table->string('contract_status')->nullable();
            $table->string('contract_findings')->nullable();
            $table->string('completion')->nullable();
            $table->string('per_findings')->nullable();
            $table->string('ro_feedback')->nullable();
            $table->string('workday_id')->nullable();
            $table->foreign('employee_tbl_id')->references('id')->on('employees');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workday_table');
    }
};
