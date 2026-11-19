<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('interval', 16)->default('month');

            $table->unsignedInteger('portfolio_limit')->default(1);
            $table->boolean('custom_domain_enabled')->default(false);
            $table->boolean('remove_branding')->default(false);

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->softDeletes();
            $table->index(['slug', 'deleted_at'], 'plans_slug_active_index');
            $table->index(['is_active', 'deleted_at'], 'plans_active_index');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
