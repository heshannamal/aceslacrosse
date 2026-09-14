<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('em_customer_remember_tokens')) {
            Schema::create('em_customer_remember_tokens', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id');
                $table->string('selector', 64)->unique();
                $table->string('validator_hash', 64);
                $table->string('user_agent', 500)->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at');
                $table->timestamps();

                $table->index(['customer_id', 'expires_at'], 'aces_customer_remember_customer_expiry_idx');
                $table->index('expires_at', 'aces_customer_remember_expiry_idx');
                $table->foreign('customer_id')->references('id')->on('em_customers')->cascadeOnDelete();
            });
        }

        if (!Schema::hasTable('admin_remember_tokens')) {
            Schema::create('admin_remember_tokens', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('selector', 64)->unique();
                $table->string('validator_hash', 64);
                $table->string('user_agent', 500)->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at');
                $table->timestamps();

                $table->index(['user_id', 'expires_at'], 'aces_admin_remember_user_expiry_idx');
                $table->index('expires_at', 'aces_admin_remember_expiry_idx');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_remember_tokens');
        Schema::dropIfExists('em_customer_remember_tokens');
    }
};
