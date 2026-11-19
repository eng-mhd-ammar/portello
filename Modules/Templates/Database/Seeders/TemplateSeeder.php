<?php

namespace Modules\Templates\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Templates\Models\Template;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        Template::query()->updateOrCreate(
            ['slug' => 'classic'],
            [
                'name' => 'Classic',
                'description' => 'A clean starter layout for personal portfolios.',
                'is_premium' => false,
                'price' => 0,
                'currency' => 'USD',
                'is_active' => true,
                'sort_order' => 0,
                'schema' => ['sections' => ['hero', 'projects', 'contact']],
            ]
        );

        Template::query()->updateOrCreate(
            ['slug' => 'bold'],
            [
                'name' => 'Bold',
                'description' => 'High-contrast typography and a dramatic hero section.',
                'is_premium' => true,
                'price' => 19,
                'currency' => 'USD',
                'is_active' => true,
                'sort_order' => 1,
                'schema' => ['sections' => ['hero', 'about', 'gallery', 'contact']],
            ]
        );
    }
}
