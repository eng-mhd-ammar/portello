<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();

            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');

            $table->string('method', 32);
            $table->tinyInteger('status')->comment('PaymentStatus');

            $table->string('transaction_id')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->json('metadata')->nullable();

            $table->softDeletes();
            $table->index(['order_id', 'status', 'deleted_at'], 'payments_order_status_index');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
