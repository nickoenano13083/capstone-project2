<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateEventsDateColumn extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // First, ensure all existing dates are in the correct format
        $events = DB::table('events')->get();
        
        foreach ($events as $event) {
            try {
                $date = \Carbon\Carbon::parse($event->date)->format('Y-m-d');
                DB::table('events')
                    ->where('id', $event->id)
                    ->update(['date' => $date]);
            } catch (\Exception $e) {
                // If date parsing fails, set to today's date as fallback
                DB::table('events')
                    ->where('id', $event->id)
                    ->update(['date' => now()->format('Y-m-d')]);
            }
        }

        // Now modify the column type
        Schema::table('events', function (Blueprint $table) {
            $table->date('date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('date')->change();
        });
    }
}
