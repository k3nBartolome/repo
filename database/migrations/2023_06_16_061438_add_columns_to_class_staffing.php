<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToClassStaffing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_staffing', function (Blueprint $table) {
            $table->integer('active_status')->nullable();
            $table->unsignedBigInteger('class_staffing_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_staffing', function (Blueprint $table) {
            $table->integer('active_status')->nullable();
            $table->unsignedBigInteger('class_staffing_id')->nullable();
        });
    }
}
