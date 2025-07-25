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
                        // Add nullable director_id column
            $table->unsignedBigInteger('director_id')->nullable()->after('description');
            
            // Add foreign key constraint
            $table->foreign('director_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('SET NULL');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            //
            $table->dropColumn('director_id');
        });
    }
};

