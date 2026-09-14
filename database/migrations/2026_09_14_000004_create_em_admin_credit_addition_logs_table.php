<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('em_admin_credit_addition_logs')) {
            return;
        }

        Schema::create('em_admin_credit_addition_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->unsignedBigInteger('child_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('credit_id')->nullable();
            $table->integer('credit_amount');
            $table->string('credit_option', 30)->nullable();
            $table->integer('balance_before')->default(0);
            $table->integer('balance_after')->default(0);
            $table->text('note')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->index('admin_user_id', 'aces_credit_add_logs_admin_idx');
            $table->index('child_id', 'aces_credit_add_logs_child_idx');
            $table->index('customer_id', 'aces_credit_add_logs_customer_idx');
            $table->index('credit_id', 'aces_credit_add_logs_credit_idx');
            $table->index('created_at', 'aces_credit_add_logs_created_idx');

            $table->foreign('admin_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('child_id')->references('id')->on('em_customer_children')->nullOnDelete();
            $table->foreign('customer_id')->references('id')->on('em_customers')->nullOnDelete();
            $table->foreign('credit_id')->references('id')->on('em_customer_credits')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('em_admin_credit_addition_logs');
    }
};
