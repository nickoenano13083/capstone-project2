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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['document', 'video', 'link', 'image', 'audio', 'pdf', 'presentation'])->default('document');
            $table->string('file_path')->nullable();
            $table->string('url')->nullable();
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->string('file_type')->nullable(); // MIME type
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('category')->default('general');
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->integer('download_count')->default(0);
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
