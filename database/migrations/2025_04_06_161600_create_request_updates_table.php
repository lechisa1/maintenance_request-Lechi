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
        Schema::create('request_updates', function (Blueprint $table) {
    $table->id();
    $table->foreignId('request_id')->constrained('maintenance_requests')->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who made the update
    $table->text('update_text');
    $table->string('update_type'); // status_change, note, etc.
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_updates');
    }
};