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
        Schema::create('assignments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('request_id')->constrained('maintenance_requests')->onDelete('cascade');
    $table->foreignId('director_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('technician_id')->constrained('users')->onDelete('cascade');
    $table->text('director_notes')->nullable();
    $table->timestamp('assigned_at')->useCurrent();
    $table->timestamp('expected_completion_date')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};