<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained('portfolios')->cascadeOnDelete()->cascadeOnUpdate();

            $table->string('host');
            $table->tinyInteger('type')->comment('PortfolioDomainType');

            $table->tinyInteger('status')->comment('PortfolioDomainStatus');
            $table->string('verification_token')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->string('failure_reason')->nullable();

            $table->boolean('is_primary')->default(false);

            $table->softDeletes();
            $table->unique(['host', 'deleted_at'], 'portfolio_domains_host_active_unique');
            $table->index(['portfolio_id', 'is_primary', 'deleted_at'], 'portfolio_domains_primary_index');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_domains');
    }
};
