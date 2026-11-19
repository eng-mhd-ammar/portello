<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete()->cascadeOnUpdate();

            $table->tinyInteger('status')->comment('SubscriptionStatus');

            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('canceled_at')->nullable();

            $table->foreignId('scheduled_plan_id')->nullable()->constrained('plans')->nullOnDelete()->cascadeOnUpdate();

            $table->json('plan_snapshot')->nullable();

            $table->softDeletes();
            $table->index(['user_id', 'status', 'deleted_at'], 'subscriptions_user_status_index');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
