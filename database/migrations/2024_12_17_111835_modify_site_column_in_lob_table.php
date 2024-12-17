<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySiteColumnInLobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lob', function (Blueprint $table) {
            // Modify the 'site' column to be an unsignedBigInteger
            $table->unsignedBigInteger('site')->change();

            // Add the foreign key reference to the 'sites' table
            $table->foreign('site')->references('id')->on('sites');
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
            // Drop the foreign key constraint
            $table->dropForeign(['site']);

            // Optionally, change the 'site' column back (e.g., to string if needed)
            $table->string('site')->change();
        });
    }
}

