<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();

            $table->string('code', 8);
            $table->tinyInteger('identifier_type')->comment('IdentifierType');
            $table->string('identifier');
            $table->tinyInteger('type')->comment('VerificationCodeType');

            $table->timestamp('expired_at');
            $table->timestamp('verified_at')->nullable();

            $table->unsignedTinyInteger('attempts')->default(0);

            $table->softDeletes();
            $table->timestamps();

            $table->index(['identifier', 'identifier_type', 'type', 'deleted_at'], 'verification_codes_identifier_active_index');
            $table->index(['expired_at', 'verified_at'], 'verification_codes_expiry_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_codes');
    }
};
