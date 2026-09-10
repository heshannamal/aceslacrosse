<?php

namespace Database\Seeders;

use App\Models\EMCustomer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class AcesLoginSeeder extends Seeder
{
    /**
     * Seed the default ACES Training customer and Super Admin login accounts.
     */
    public function run(): void
    {
        $trainingPassword = env('ACES_TRAINING_SEED_PASSWORD');
        $adminPassword = env('ACES_ADMIN_SEED_PASSWORD');

        if (!$trainingPassword || !$adminPassword) {
            throw new RuntimeException(
                'Set ACES_TRAINING_SEED_PASSWORD and ACES_ADMIN_SEED_PASSWORD in .env before running AcesLoginSeeder.'
            );
        }

        EMCustomer::query()->updateOrCreate(
            ['email' => 'customer@aceslacrosse.com'],
            [
                'first_name' => 'ACES',
                'last_name' => 'Customer',
                'password' => Hash::make($trainingPassword),
                'email_verified_at' => now(),
                'parent_type' => 1,
                'account_type' => EMCustomer::ACCOUNT_TYPE_USER,
                'active' => true,
            ]
        );

        $admin = User::query()->updateOrCreate(
            ['email' => 'superadmin@aceslacrosse.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        // Attach the account to the Super Admin group so it receives the
        // complete Admin permission set even when this user is not ID 1.
        if (Schema::hasTable('user_groups') && Schema::hasTable('user_group_user')) {
            $superAdminGroupId = DB::table('user_groups')
                ->where('slug', 'super_admin')
                ->value('id');

            if ($superAdminGroupId) {
                DB::table('user_group_user')->updateOrInsert(
                    [
                        'user_group_id' => $superAdminGroupId,
                        'user_id' => $admin->id,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        $this->command?->info('ACES Training customer and Super Admin accounts seeded successfully.');
    }
}
