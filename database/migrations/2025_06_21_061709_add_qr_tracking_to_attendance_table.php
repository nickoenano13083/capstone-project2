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
        Schema::table('attendance', function (Blueprint $table) {
            $table->foreignId('qr_code_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('scanned_at')->nullable();
            $table->foreignId('scanned_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropForeign(['qr_code_id']);
            $table->dropColumn('qr_code_id');
            $table->dropColumn('scanned_at');
            $table->dropForeign(['scanned_by']);
            $table->dropColumn('scanned_by');
        });
    }
};
