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
        // First, ensure all existing records have a user_id
        // This will set user_id to the authenticated user's ID for existing records
        // You may need to adjust this based on your application's requirements
        \DB::table('prayer_requests')
            ->whereNull('user_id')
            ->update(['user_id' => 1]); // Replace 1 with the ID of a default admin user

        // Then modify the column to be non-nullable
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });
    }
};
