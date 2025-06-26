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
        Schema::table('attachments', function (Blueprint $table) {
            //
            $table->renameColumn('request_id', 'maintenance_request_id');

            // Remove the old foreign key constraint if needed
            $table->dropForeign(['request_id']);

            // Add the new foreign key constraint
            $table->foreign('maintenance_request_id')
                ->references('id')
                ->on('maintenance_requests')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            //
            $table->renameColumn('maintenance_request_id', 'request_id');

            // Drop the new foreign key constraint
            $table->dropForeign(['maintenance_request_id']);

            // Re-add the old foreign key constraint
            $table->foreign('request_id')
                ->references('id')
                ->on('maintenance_requests')
                ->onDelete('cascade');
        });
    }
};