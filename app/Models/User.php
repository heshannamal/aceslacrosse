<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name','email','password','is_admin'];
    protected $hidden = ['password','remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'user_group_user')->withTimestamps();
    }

    public function hasAdminPermission($permissionSlug)
    {
        if ((int) $this->id === 1) {
            return true;
        }

        return $this->userGroups()
            ->where('user_groups.active', 1)
            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                $query->where('permissions.slug', $permissionSlug)
                    ->where('permissions.active', 1);
            })->exists();
    }

    public function hasAnyAdminPermission(array $permissionSlugs)
    {
        if ((int) $this->id === 1) {
            return true;
        }

        return $this->userGroups()
            ->where('user_groups.active', 1)
            ->whereHas('permissions', function ($query) use ($permissionSlugs) {
                $query->whereIn('permissions.slug', $permissionSlugs)
                    ->where('permissions.active', 1);
            })->exists();
    }

    public function canAccessAdmin(): bool
    {
        return (int) $this->id === 1
            || (bool) $this->is_admin
            || $this->hasAnyAdminPermission([
                'super_admin_privilages',
                'admin_privilages',
                'manage_users',
                'manage_permissions',
                'manage_user_groups',
            ]);
    }
}
