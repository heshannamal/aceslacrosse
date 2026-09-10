<?php

namespace Database\Seeders;

use App\Models\EMCustomer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AcesLoginSeeder extends Seeder
{
    /**
     * Seed the default ACES Training customer and Super Admin login accounts.
     */
    public function run(): void
    {
        $trainingPasswordHash = '$2y$12$abue5FePsdZroY605LRIse5q61pCwBmhxZJe8Qa2mbGEsytMi9OAi';
        $adminPasswordHash = '$2y$12$nIUCGj3mP1mjeptL.J.RY.q9hstVuMcJ975a4SkymUYlZcL1F/kYm';

        EMCustomer::query()->updateOrCreate(
            ['email' => 'customer@aceslacrosse.com'],
            [
                'first_name' => 'ACES',
                'last_name' => 'Customer',
                'password' => $trainingPasswordHash,
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
                'password' => $adminPasswordHash,
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
