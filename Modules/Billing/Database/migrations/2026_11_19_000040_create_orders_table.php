<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();

            $table->string('number');
            $table->tinyInteger('type')->comment('OrderType');
            $table->tinyInteger('status')->comment('OrderStatus');

            $table->string('currency', 3)->default('USD');

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            $table->timestamp('paid_at')->nullable();

            $table->softDeletes();
            $table->unique(['number', 'deleted_at'], 'orders_number_active_unique');
            $table->index(['user_id', 'status', 'deleted_at'], 'orders_user_status_index');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
