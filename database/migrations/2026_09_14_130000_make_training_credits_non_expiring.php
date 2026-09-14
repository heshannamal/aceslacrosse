<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('em_customer_credits')) {
            return;
        }

        $updates = [];

        if (Schema::hasColumn('em_customer_credits', 'valid_from')) {
            $updates['valid_from'] = null;
        }

        if (Schema::hasColumn('em_customer_credits', 'valid_until')) {
            $updates['valid_until'] = null;
        }

        if ($updates !== []) {
            DB::table('em_customer_credits')->update($updates);
        }
    }

    public function down(): void
    {
        // Previous validity dates cannot be reconstructed. ACES Training credits
        // intentionally remain non-expiring and valid until fully consumed.
    }
};
