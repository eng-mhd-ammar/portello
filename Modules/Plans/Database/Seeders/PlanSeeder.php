<?php

namespace Modules\Plans\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Plans\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::query()->updateOrCreate(
            ['slug' => 'free'],
            [
                'name' => 'Free',
                'description' => 'One portfolio, subdomain, Portello branding.',
                'price' => 0,
                'currency' => 'USD',
                'interval' => 'month',
                'portfolio_limit' => 1,
                'custom_domain_enabled' => false,
                'remove_branding' => false,
                'is_active' => true,
                'sort_order' => 0,
            ]
        );

        Plan::query()->updateOrCreate(
            ['slug' => 'pro'],
            [
                'name' => 'Pro',
                'description' => 'More portfolios, custom domain, remove branding.',
                'price' => 12,
                'currency' => 'USD',
                'interval' => 'month',
                'portfolio_limit' => 10,
                'custom_domain_enabled' => true,
                'remove_branding' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}
