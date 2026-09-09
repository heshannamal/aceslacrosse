<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('em_customers') && !Schema::hasColumn('em_customers', 'account_type')) {
            Schema::table('em_customers', function (Blueprint $table) {
                $table->string('account_type', 20)
                    ->default('user')
                    ->after('parent_type')
                    ->index();
            });
        }

        if (Schema::hasTable('em_packages') && !Schema::hasColumn('em_packages', 'guest_price')) {
            Schema::table('em_packages', function (Blueprint $table) {
                $table->decimal('guest_price', 12, 2)
                    ->nullable()
                    ->after('package_price');
            });

            DB::table('em_packages')
                ->whereNull('guest_price')
                ->update(['guest_price' => DB::raw('package_price')]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('em_packages') && Schema::hasColumn('em_packages', 'guest_price')) {
            Schema::table('em_packages', function (Blueprint $table) {
                $table->dropColumn('guest_price');
            });
        }

        if (Schema::hasTable('em_customers') && Schema::hasColumn('em_customers', 'account_type')) {
            Schema::table('em_customers', function (Blueprint $table) {
                $table->dropColumn('account_type');
            });
        }
    }
};
