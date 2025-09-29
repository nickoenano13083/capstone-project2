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
        Schema::table('messages', function (Blueprint $table) {
            // File attachment fields
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('attachment_type')->nullable();
            $table->unsignedBigInteger('attachment_size')->nullable();
            
            // Read receipt fields
            $table->timestamp('read_at')->nullable();
            $table->unsignedBigInteger('read_by')->nullable();
            
            // Message type (text, file, image)
            $table->enum('message_type', ['text', 'file', 'image'])->default('text');
            
            // Add indexes for better performance
            $table->index(['receiver_id', 'is_read']);
            $table->index(['sender_id', 'created_at']);
            
            // Foreign key for read_by
            $table->foreign('read_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['read_by']);
            $table->dropIndex(['receiver_id', 'is_read']);
            $table->dropIndex(['sender_id', 'created_at']);
            $table->dropColumn([
                'attachment_path',
                'attachment_name', 
                'attachment_type',
                'attachment_size',
                'read_at',
                'read_by',
                'message_type'
            ]);
        });
    }
};
