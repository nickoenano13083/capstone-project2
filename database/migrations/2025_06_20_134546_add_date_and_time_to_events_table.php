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
            $table->date('date')->after('description');
            $table->time('time')->after('date');
            // Optionally drop old columns if not needed:
            // $table->dropColumn('event_date');
            // $table->dropColumn('organizer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['date', 'time']);
            // Optionally add back old columns if dropped:
            // $table->dateTime('event_date');
            // $table->string('organizer', 100);
        });
    }
};
