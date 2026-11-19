<?php

namespace Modules\IAM\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\IAM\Enums\UserStatus;
use Modules\IAM\Models\User;
use Modules\Plans\V1\Services\SubscriptionProvisioner;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@portello.test'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['admin']);
        app(SubscriptionProvisioner::class)->provisionFree($admin);
    }
}
