<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractRemarksToWorkdayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workday_table', function (Blueprint $table) {
            $table->text('contract_remarks')->nullable()->after('contract_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workday_table', function (Blueprint $table) {
            $table->dropColumn('contract_remarks');
        });
    }
}
