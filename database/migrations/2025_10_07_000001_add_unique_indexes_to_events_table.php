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
        Schema::table('events', function (Blueprint $table) {
            // Ensure indexes do not already exist before creating
            $table->unique(['chapter_id', 'date', 'time', 'location'], 'events_unique_chapter_date_time_location');
            $table->unique(['chapter_id', 'title', 'date', 'time', 'location'], 'events_unique_chapter_title_date_time_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropUnique('events_unique_chapter_date_time_location');
            $table->dropUnique('events_unique_chapter_title_date_time_location');
        });
    }
};


