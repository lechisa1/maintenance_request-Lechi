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
        Schema::table('departments', function (Blueprint $table) {
            //
                    $table->unsignedBigInteger('sector_id')->nullable()->after('id');

        // Optional: add foreign key constraint if you have sectors table
        $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            //
                    $table->dropForeign(['sector_id']);
        $table->dropColumn('sector_id');

        });
    }
};
