<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEmployeeIdColumnInEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique(['employee_id']);

            // Redefine the column without the unique constraint
            $table->string('employee_id')->change();
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Add the unique constraint back if you roll back
            $table->string('employee_id')->unique()->change();
        });
    }
}
