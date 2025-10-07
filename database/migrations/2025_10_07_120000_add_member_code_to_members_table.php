<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('member_code', 20)->unique()->nullable()->after('user_id');
        });

        // Backfill existing members with sequential codes MEM-000001, MEM-000002, ...
        $members = DB::table('members')->orderBy('id')->get(['id', 'member_code']);

        // Determine starting sequence from existing member_code if any
        $maxSeq = 0;
        foreach ($members as $m) {
            if (!empty($m->member_code) && preg_match('/^MEM-(\d{6})$/', $m->member_code, $mm)) {
                $seq = intval($mm[1]);
                if ($seq > $maxSeq) {
                    $maxSeq = $seq;
                }
            }
        }

        foreach ($members as $m) {
            if (empty($m->member_code)) {
                $maxSeq++;
                $code = 'MEM-' . str_pad((string)$maxSeq, 6, '0', STR_PAD_LEFT);
                DB::table('members')->where('id', $m->id)->update(['member_code' => $code]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropUnique(['member_code']);
            $table->dropColumn('member_code');
        });
    }
};


