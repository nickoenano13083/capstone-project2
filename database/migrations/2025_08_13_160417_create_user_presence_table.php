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
        Schema::create('user_presence', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['online', 'offline', 'away'])->default('offline');
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->string('current_conversation_id')->nullable(); // For typing indicators
            $table->boolean('is_typing')->default(false);
            $table->timestamp('typing_started_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']);
            $table->index(['last_activity_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_presence');
    }
};
