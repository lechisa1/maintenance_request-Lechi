<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('sector_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        $table->foreignId('division_id')->nullable()->after('sector_id')->constrained()->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
                    $table->dropForeign(['sector_id']);
        $table->dropColumn('sector_id');

        $table->dropForeign(['division_id']);
        $table->dropColumn('division_id');
        });
    }
};
