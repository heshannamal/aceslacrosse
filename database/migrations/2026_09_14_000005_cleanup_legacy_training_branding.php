<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('em_session_events') && Schema::hasColumn('em_session_events', 'description')) {
            DB::table('em_session_events')
                ->whereNotNull('description')
                ->where(function ($query) {
                    $query->where('description', 'like', '%Alcatraz%')
                        ->orWhere('description', 'like', '%Outlaws%');
                })
                ->orderBy('id')
                ->chunkById(100, function ($rows) {
                    foreach ($rows as $row) {
                        $description = (string) $row->description;
                        $description = str_ireplace('Alcatraz Outlaws', 'ACES Lacrosse', $description);
                        $description = str_ireplace('Alcatraz', 'ACES Lacrosse', $description);

                        DB::table('em_session_events')
                            ->where('id', $row->id)
                            ->update(['description' => $description]);
                    }
                });
        }

        if (Schema::hasTable('em_package_orders') && Schema::hasColumn('em_package_orders', 'order_no')) {
            DB::table('em_package_orders')
                ->where('order_no', 'like', 'AO-TR-%')
                ->orderBy('id')
                ->chunkById(100, function ($rows) {
                    foreach ($rows as $row) {
                        $orderNo = (string) $row->order_no;
                        DB::table('em_package_orders')
                            ->where('id', $row->id)
                            ->update([
                                'order_no' => 'ACES-TR-' . substr($orderNo, strlen('AO-TR-')),
                            ]);
                    }
                });
        }
    }

    public function down(): void
    {
        // Branding cleanup is intentionally not reversible.
    }
};
