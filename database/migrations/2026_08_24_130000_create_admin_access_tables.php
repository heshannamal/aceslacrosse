<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false)->after('password')->index();
            });
        }

        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name', 190);
                $table->string('slug', 190)->unique();
                $table->text('description')->nullable();
                $table->boolean('active')->default(true)->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('user_groups')) {
            Schema::create('user_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name', 190);
                $table->string('slug', 190)->unique();
                $table->text('description')->nullable();
                $table->boolean('active')->default(true)->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('permission_user_group')) {
            Schema::create('permission_user_group', function (Blueprint $table) {
                $table->id();
                $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
                $table->foreignId('user_group_id')->constrained('user_groups')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['permission_id', 'user_group_id'], 'permission_group_unique');
            });
        }

        if (!Schema::hasTable('user_group_user')) {
            Schema::create('user_group_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_group_id')->constrained('user_groups')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_group_id', 'user_id'], 'group_user_unique');
            });
        }

        $now = now();
        $permissions = [
            ['name' => 'Super Admin Privileges', 'slug' => 'super_admin_privilages', 'description' => 'Full administrative access.'],
            ['name' => 'Admin Privileges', 'slug' => 'admin_privilages', 'description' => 'Manage members, bookings, packages, sessions and payments.'],
            ['name' => 'Manage Users', 'slug' => 'manage_users', 'description' => 'Create, edit and remove admin users.'],
            ['name' => 'Manage Permissions', 'slug' => 'manage_permissions', 'description' => 'Create and remove permissions.'],
            ['name' => 'Manage User Groups', 'slug' => 'manage_user_groups', 'description' => 'Manage user groups and assignments.'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['slug' => $permission['slug']],
                array_merge($permission, ['active' => 1, 'created_at' => $now, 'updated_at' => $now])
            );
        }

        DB::table('user_groups')->updateOrInsert(
            ['slug' => 'super_admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Full access to the ACES Lacrosse admin panel.',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $groupId = DB::table('user_groups')->where('slug', 'super_admin')->value('id');
        $permissionIds = DB::table('permissions')->whereIn('slug', array_column($permissions, 'slug'))->pluck('id');

        foreach ($permissionIds as $permissionId) {
            DB::table('permission_user_group')->updateOrInsert(
                ['permission_id' => $permissionId, 'user_group_id' => $groupId],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }

        if (Schema::hasTable('users') && DB::table('users')->where('id', 1)->exists()) {
            DB::table('users')->where('id', 1)->update(['is_admin' => 1]);
            DB::table('user_group_user')->updateOrInsert(
                ['user_group_id' => $groupId, 'user_id' => 1],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_group_user');
        Schema::dropIfExists('permission_user_group');
        Schema::dropIfExists('user_groups');
        Schema::dropIfExists('permissions');

        if (Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }
    }
};
