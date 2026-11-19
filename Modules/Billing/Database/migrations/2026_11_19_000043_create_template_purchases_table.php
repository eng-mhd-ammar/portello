<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('template_id')->constrained('templates')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamp('purchased_at');

            $table->json('template_snapshot')->nullable();

            $table->softDeletes();
            $table->unique(['user_id', 'template_id', 'deleted_at'], 'template_purchases_user_template_unique');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_purchases');
    }
};
