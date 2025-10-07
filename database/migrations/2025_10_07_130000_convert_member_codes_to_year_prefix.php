<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convert existing codes from MEM-###### to YEAR-###### using created_at year
        $members = DB::table('members')->select('id', 'member_code', 'created_at')->get();
        foreach ($members as $m) {
            if (empty($m->member_code)) {
                // Assign new YEAR-based code
                $year = substr((string) ($m->created_at ?? date('Y-m-d H:i:s')), 0, 4);
                $seq = DB::table('members')
                    ->where('member_code', 'like', $year . '-%')
                    ->count() + 1;
                $code = $year . '-' . str_pad((string) $seq, 6, '0', STR_PAD_LEFT);
                DB::table('members')->where('id', $m->id)->update(['member_code' => $code]);
                continue;
            }
            if (preg_match('/^MEM-(\d{6})$/', $m->member_code, $mm)) {
                $year = substr((string) ($m->created_at ?? date('Y-m-d H:i:s')), 0, 4);
                // Keep the existing sequence but move under the year's prefix; if conflict, find next available
                $seq = $mm[1];
                $target = $year . '-' . $seq;
                $exists = DB::table('members')->where('member_code', $target)->where('id', '!=', $m->id)->exists();
                if ($exists) {
                    // Find next available sequence for that year
                    $maxExisting = DB::table('members')
                        ->where('member_code', 'like', $year . '-%')
                        ->selectRaw('MAX(CAST(SUBSTR(member_code, 6) AS UNSIGNED)) as max_seq')
                        ->value('max_seq');
                    $next = intval($maxExisting ?: 0) + 1;
                    $target = $year . '-' . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
                }
                DB::table('members')->where('id', $m->id)->update(['member_code' => $target]);
            }
        }
    }

    public function down(): void
    {
        // No reliable way to revert to MEM- prefix; leaving as-is.
    }
};


