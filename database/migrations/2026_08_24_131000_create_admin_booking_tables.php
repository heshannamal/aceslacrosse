<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('em_customers')) {
            Schema::create('em_customers', function (Blueprint $table) {
                $table->id();
                $table->string('first_name', 190)->nullable();
                $table->string('last_name', 190)->nullable();
                $table->string('email', 190)->nullable()->index();
                $table->string('phone', 80)->nullable();
                $table->string('password')->nullable();
                $table->string('google_id', 190)->nullable();
                $table->string('profile_photo')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->integer('parent_type')->default(1);
                $table->unsignedBigInteger('relational_id')->nullable()->index();
                $table->boolean('active')->default(true)->index();
                $table->rememberToken();
                $table->char('password_reset_token', 64)->nullable()->index();
                $table->text('password_reset_token_encrypted')->nullable();
                $table->dateTime('password_reset_expires_at')->nullable()->index();
                $table->dateTime('password_reset_requested_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('em_customer_children')) {
            Schema::create('em_customer_children', function (Blueprint $table) {
                $table->id();
                $table->string('first_name', 100)->nullable();
                $table->string('last_name', 100)->nullable();
                $table->string('team', 150)->nullable();
                $table->string('spring_team', 150)->nullable();
                $table->string('position')->nullable();
                $table->string('class_year', 20)->nullable();
                $table->date('birthdate')->nullable();
                $table->string('gender', 30)->nullable();
                $table->string('school', 150)->nullable();
                $table->string('grade', 50)->nullable();
                $table->text('medical_notes')->nullable();
                $table->text('allergies')->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('em_packages')) {
            Schema::create('em_packages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('uuid', 100)->nullable()->index();
                $table->string('slug')->unique();
                $table->string('package_name', 190);
                $table->decimal('package_price', 12, 2)->default(0);
                $table->text('package_description')->nullable();
                $table->unsignedInteger('available_classes')->default(1);
                $table->boolean('is_active')->default(true)->index();
                $table->string('status', 50)->default('active')->index();
                $table->boolean('active')->default(true)->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('em_package_orders')) {
            Schema::create('em_package_orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->index();
                $table->string('order_no', 80)->unique();
                $table->decimal('subtotal', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('processing_fee', 10, 2)->default(0);
                $table->decimal('total', 10, 2)->default(0);
                $table->string('payment_status', 50)->default('pending')->index();
                $table->string('payment_method', 50)->nullable();
                $table->string('card_last_four', 10)->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('em_session_events')) {
            Schema::create('em_session_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('documentation')->nullable();
                $table->string('training_type', 50)->nullable()->index();
                $table->string('street_address')->nullable();
                $table->string('city')->nullable();
                $table->string('location', 500)->nullable();
                $table->decimal('location_lat', 10, 7)->nullable();
                $table->decimal('location_lng', 10, 7)->nullable();
                $table->string('instructor')->nullable();
                $table->date('event_date')->index();
                $table->time('start_time');
                $table->time('end_time');
                $table->longText('what_to_bring')->nullable();
                $table->integer('capacity')->default(10);
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('em_customer_child_parents')) {
            Schema::create('em_customer_child_parents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->index();
                $table->unsignedBigInteger('child_id')->index();
                $table->string('relationship', 50)->nullable();
                $table->boolean('is_primary')->default(false);
                $table->boolean('can_book')->default(true);
                $table->boolean('can_pay')->default(true);
                $table->boolean('can_pickup')->default(false);
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->unique(['customer_id', 'child_id'], 'em_customer_child_parent_unique');
            });
        }

        if (!Schema::hasTable('em_customer_credits')) {
            Schema::create('em_customer_credits', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->index();
                $table->unsignedBigInteger('package_id')->nullable()->index();
                $table->unsignedBigInteger('order_id')->nullable()->index();
                $table->integer('total_classes')->default(0);
                $table->integer('used_classes')->default(0);
                $table->integer('remaining_classes')->default(0);
                $table->date('valid_from')->nullable();
                $table->date('valid_until')->nullable();
                $table->string('status', 50)->default('active')->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('em_session_bookings')) {
            Schema::create('em_session_bookings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->index();
                $table->unsignedBigInteger('child_id')->nullable()->index();
                $table->unsignedBigInteger('customer_child_id')->nullable()->index();
                $table->unsignedBigInteger('session_event_id')->index();
                $table->unsignedBigInteger('package_id')->nullable()->index();
                $table->unsignedBigInteger('credit_id')->nullable()->index();
                $table->unsignedBigInteger('cart_id')->nullable()->index();
                $table->unsignedBigInteger('order_id')->nullable()->index();
                $table->unsignedBigInteger('order_item_id')->nullable()->index();
                $table->string('booking_no', 80)->unique();
                $table->string('player_first', 100)->nullable();
                $table->string('player_last', 100)->nullable();
                $table->integer('grad_year')->nullable();
                $table->longText('positions')->nullable();
                $table->string('status', 50)->default('booked')->index();
                $table->timestamp('booked_at')->nullable();
                $table->timestamp('reminder_email_sent_at')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('em_customer_package_carts')) {
            Schema::create('em_customer_package_carts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->index();
                $table->unsignedBigInteger('booking_id')->nullable()->index();
                $table->unsignedBigInteger('package_id')->index();
                $table->string('source_type', 50)->default('direct');
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 10, 2)->default(0);
                $table->decimal('total_price', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('em_package_order_items')) {
            Schema::create('em_package_order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id')->index();
                $table->unsignedBigInteger('package_id')->index();
                $table->unsignedBigInteger('booking_id')->nullable()->index();
                $table->string('package_name', 190);
                $table->integer('quantity')->default(1);
                $table->integer('classes_per_package')->default(0);
                $table->decimal('unit_price', 10, 2)->default(0);
                $table->decimal('total_price', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('em_customer_credit_logs')) {
            Schema::create('em_customer_credit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->index();
                $table->unsignedBigInteger('credit_id')->index();
                $table->unsignedBigInteger('booking_id')->nullable()->index();
                $table->string('type', 50)->index();
                $table->integer('classes')->default(0);
                $table->text('note')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('em_package_payment_logs')) {
            Schema::create('em_package_payment_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('package_order_id')->index();
                $table->unsignedBigInteger('customer_id')->index();
                $table->string('gateway', 50)->default('authorize_net');
                $table->string('environment', 190)->nullable();
                $table->string('payment_method', 50)->default('card');
                $table->string('status', 50)->default('pending')->index();
                $table->decimal('subtotal', 10, 2)->default(0);
                $table->decimal('processing_fee', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('amount', 10, 2)->default(0);
                $table->string('response_code', 50)->nullable();
                $table->string('transaction_id', 100)->nullable()->index();
                $table->string('auth_id', 100)->nullable();
                $table->string('message_code', 100)->nullable();
                $table->text('message_text')->nullable();
                $table->string('ref_id', 100)->nullable();
                $table->string('name_on_card', 190)->nullable();
                $table->string('card_last_four', 10)->nullable();
                $table->string('billing_first_name', 100)->nullable();
                $table->string('billing_last_name', 100)->nullable();
                $table->string('billing_email', 190)->nullable();
                $table->string('billing_phone', 50)->nullable();
                $table->string('billing_street', 190)->nullable();
                $table->string('billing_city', 100)->nullable();
                $table->string('billing_state', 100)->nullable();
                $table->string('billing_zip', 30)->nullable();
                $table->string('billing_country', 100)->nullable();
                $table->integer('quantity')->default(1);
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('em_package_payment_logs');
        Schema::dropIfExists('em_customer_credit_logs');
        Schema::dropIfExists('em_package_order_items');
        Schema::dropIfExists('em_customer_package_carts');
        Schema::dropIfExists('em_session_bookings');
        Schema::dropIfExists('em_customer_credits');
        Schema::dropIfExists('em_customer_child_parents');
        Schema::dropIfExists('em_session_events');
        Schema::dropIfExists('em_package_orders');
        Schema::dropIfExists('em_packages');
        Schema::dropIfExists('em_customer_children');
        Schema::dropIfExists('em_customers');
    }
};
