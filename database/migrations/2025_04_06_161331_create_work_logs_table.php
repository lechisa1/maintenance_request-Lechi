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
        Schema::create('work_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('request_id')->constrained('maintenance_requests')->onDelete('cascade');
    $table->foreignId('technician_id')->constrained('users')->onDelete('cascade');
    $table->text('work_done');
    $table->text('materials_used')->nullable();
    $table->integer('time_spent_minutes')->nullable();
    $table->text('completion_notes')->nullable();
    $table->timestamp('log_date')->useCurrent();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};