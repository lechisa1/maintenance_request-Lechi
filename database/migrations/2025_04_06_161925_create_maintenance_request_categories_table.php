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
    Schema::create('maintenance_request_categories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('maintenance_request_id')->constrained('maintenance_requests')->onDelete('cascade');
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        $table->unique(['maintenance_request_id', 'category_id'], 'maintenance_request_category_unique'); // Custom unique key name
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_request_categories');
    }
};