<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('portfolio_id')->nullable()->constrained('portfolios')->nullOnDelete()->cascadeOnUpdate();

            $table->string('disk');
            $table->string('path');
            $table->string('mime', 127);
            $table->unsignedBigInteger('size');
            $table->string('alt')->nullable();

            $table->softDeletes();
            $table->index(['user_id', 'deleted_at'], 'media_user_active_index');
            $table->index(['portfolio_id', 'deleted_at'], 'media_portfolio_active_index');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
