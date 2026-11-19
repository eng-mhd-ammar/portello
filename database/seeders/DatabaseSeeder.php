<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\IAM\Database\Seeders\AdminUserSeeder;
use Modules\IAM\Database\Seeders\RolePermissionSeeder;
use Modules\Plans\Database\Seeders\PlanSeeder;
use Modules\Templates\Database\Seeders\TemplateSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            PlanSeeder::class,
            TemplateSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
