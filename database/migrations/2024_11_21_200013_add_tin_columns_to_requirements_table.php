<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTinColumnsToRequirementsTable extends Migration
{
    public function up()
    {
        Schema::table('requirements', function (Blueprint $table) {
            // Add TIN-related columns
            $table->date('tin_submitted_date')->nullable();
            $table->string('tin_final_status')->nullable();
            $table->string('tin_proof_submitted_type')->nullable();
            $table->text('tin_remarks')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('tin_file_name')->nullable();
            $table->timestamp('tin_last_updated_at')->nullable();
            $table->unsignedBigInteger('tin_updated_by')->nullable();

            // Add foreign key constraint to tin_updated_by
            $table->foreign('tin_updated_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('requirements', function (Blueprint $table) {
            // Drop the columns if rolling back the migration
            $table->dropForeign(['tin_updated_by']); // Drop the foreign key constraint first
            $table->dropColumn([
                'tin_submitted_date',
                'tin_final_status',
                'tin_proof_submitted_type',
                'tin_remarks',
                'tin_number',
                'tin_file_name',
                'tin_last_updated_at',
                'tin_updated_by',
            ]);
        });
    }
}
