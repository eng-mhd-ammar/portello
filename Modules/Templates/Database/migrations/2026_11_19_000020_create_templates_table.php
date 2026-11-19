<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();

            $table->boolean('is_premium')->default(false);
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->json('schema')->nullable();

            $table->softDeletes();
            $table->index(['slug', 'deleted_at'], 'templates_slug_active_index');
            $table->index(['is_premium', 'is_active', 'deleted_at'], 'templates_catalog_index');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
