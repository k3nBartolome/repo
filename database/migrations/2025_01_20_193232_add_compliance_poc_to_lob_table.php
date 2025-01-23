<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompliancePocToLobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lob', function (Blueprint $table) {
            $table->string('compliance_poc')->nullable(); // Add the new nullable column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lob', function (Blueprint $table) {
            $table->dropColumn('compliance_poc'); // Rollback the column
        });
    }
}
