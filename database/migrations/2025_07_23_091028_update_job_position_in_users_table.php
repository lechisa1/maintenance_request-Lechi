<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::table('users', function (Blueprint $table) {
    $table->unsignedBigInteger('job_position_id')->nullable()->after('department_id');

    // Drop old string column if it exists
    $table->dropColumn('job_position');

    $table->foreign('job_position_id')->references('id')->on('job_positions')->onDelete('set null');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
