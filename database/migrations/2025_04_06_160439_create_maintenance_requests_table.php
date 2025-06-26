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
    Schema::create('maintenance_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // The employer who made the request
    $table->string('title');
    $table->text('description');
    $table->string('location');
    $table->enum('priority', ['low', 'medium', 'high', 'emergency'])->default('medium');
    $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'rejected','not_fixed'])->default('pending');
    $table->timestamp('requested_at')->useCurrent();
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};