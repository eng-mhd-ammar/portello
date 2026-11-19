<?php

namespace Modules\IAM\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\IAM\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'portfolios.index',
            'portfolios.create',
            'portfolios.show',
            'portfolios.update',
            'portfolios.delete',
            'billing.index',
            'plans.manage',
            'templates.index',
            'templates.manage',
            'admin.access',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $user = Role::query()->firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->syncPermissions([
            'portfolios.index',
            'portfolios.create',
            'portfolios.show',
            'portfolios.update',
            'portfolios.delete',
            'billing.index',
            'templates.index',
        ]);

        $admin = Role::query()->firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::query()->where('guard_name', 'web')->pluck('name'));

        User::query()
            ->whereDoesntHave('roles')
            ->each(function (User $orphan): void {
                $orphan->assignRole('user');
            });
    }
}
