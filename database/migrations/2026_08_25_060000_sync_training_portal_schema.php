<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This migration is intentionally idempotent. Alcatraz can be installed
        // over a database imported from Baddies, where the tables already exist
        // and the original create migrations therefore skip them.

        $this->addColumns('em_customers', [
            'first_name' => fn (Blueprint $t) => $t->string('first_name', 190)->nullable(),
            'last_name' => fn (Blueprint $t) => $t->string('last_name', 190)->nullable(),
            'email' => fn (Blueprint $t) => $t->string('email', 190)->nullable(),
            'phone' => fn (Blueprint $t) => $t->string('phone', 80)->nullable(),
            'password' => fn (Blueprint $t) => $t->string('password')->nullable(),
            'google_id' => fn (Blueprint $t) => $t->string('google_id', 190)->nullable(),
            'profile_photo' => fn (Blueprint $t) => $t->string('profile_photo')->nullable(),
            'email_verified_at' => fn (Blueprint $t) => $t->timestamp('email_verified_at')->nullable(),
            'parent_type' => fn (Blueprint $t) => $t->integer('parent_type')->default(1),
            'relational_id' => fn (Blueprint $t) => $t->unsignedBigInteger('relational_id')->nullable(),
            'active' => fn (Blueprint $t) => $t->boolean('active')->default(true),
            'remember_token' => fn (Blueprint $t) => $t->string('remember_token', 100)->nullable(),
            'password_reset_token' => fn (Blueprint $t) => $t->char('password_reset_token', 64)->nullable(),
            'password_reset_token_encrypted' => fn (Blueprint $t) => $t->text('password_reset_token_encrypted')->nullable(),
            'password_reset_expires_at' => fn (Blueprint $t) => $t->dateTime('password_reset_expires_at')->nullable(),
            'password_reset_requested_at' => fn (Blueprint $t) => $t->dateTime('password_reset_requested_at')->nullable(),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_customer_children', [
            'first_name' => fn (Blueprint $t) => $t->string('first_name', 100)->nullable(),
            'last_name' => fn (Blueprint $t) => $t->string('last_name', 100)->nullable(),
            'team' => fn (Blueprint $t) => $t->string('team', 150)->nullable(),
            'spring_team' => fn (Blueprint $t) => $t->string('spring_team', 150)->nullable(),
            'position' => fn (Blueprint $t) => $t->string('position')->nullable(),
            'class_year' => fn (Blueprint $t) => $t->string('class_year', 20)->nullable(),
            'birthdate' => fn (Blueprint $t) => $t->date('birthdate')->nullable(),
            'gender' => fn (Blueprint $t) => $t->string('gender', 30)->nullable(),
            'school' => fn (Blueprint $t) => $t->string('school', 150)->nullable(),
            'grade' => fn (Blueprint $t) => $t->string('grade', 50)->nullable(),
            'medical_notes' => fn (Blueprint $t) => $t->text('medical_notes')->nullable(),
            'allergies' => fn (Blueprint $t) => $t->text('allergies')->nullable(),
            'is_active' => fn (Blueprint $t) => $t->boolean('is_active')->default(true),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_customer_child_parents', [
            'customer_id' => fn (Blueprint $t) => $t->unsignedBigInteger('customer_id')->nullable(),
            'child_id' => fn (Blueprint $t) => $t->unsignedBigInteger('child_id')->nullable(),
            'relationship' => fn (Blueprint $t) => $t->string('relationship', 50)->nullable(),
            'is_primary' => fn (Blueprint $t) => $t->boolean('is_primary')->default(false),
            'can_book' => fn (Blueprint $t) => $t->boolean('can_book')->default(true),
            'can_pay' => fn (Blueprint $t) => $t->boolean('can_pay')->default(true),
            'can_pickup' => fn (Blueprint $t) => $t->boolean('can_pickup')->default(false),
            'notes' => fn (Blueprint $t) => $t->text('notes')->nullable(),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_packages', [
            'user_id' => fn (Blueprint $t) => $t->unsignedBigInteger('user_id')->nullable(),
            'uuid' => fn (Blueprint $t) => $t->string('uuid', 100)->nullable(),
            'slug' => fn (Blueprint $t) => $t->string('slug')->nullable(),
            'package_name' => fn (Blueprint $t) => $t->string('package_name', 190)->nullable(),
            'package_price' => fn (Blueprint $t) => $t->decimal('package_price', 12, 2)->default(0),
            'package_description' => fn (Blueprint $t) => $t->text('package_description')->nullable(),
            'available_classes' => fn (Blueprint $t) => $t->unsignedInteger('available_classes')->default(1),
            'is_active' => fn (Blueprint $t) => $t->boolean('is_active')->default(true),
            'status' => fn (Blueprint $t) => $t->string('status', 50)->default('active'),
            'active' => fn (Blueprint $t) => $t->boolean('active')->default(true),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_session_events', [
            'user_id' => fn (Blueprint $t) => $t->unsignedBigInteger('user_id')->nullable(),
            'name' => fn (Blueprint $t) => $t->string('name')->nullable(),
            'description' => fn (Blueprint $t) => $t->text('description')->nullable(),
            'documentation' => fn (Blueprint $t) => $t->string('documentation')->nullable(),
            'training_type' => fn (Blueprint $t) => $t->string('training_type', 50)->nullable(),
            'street_address' => fn (Blueprint $t) => $t->string('street_address')->nullable(),
            'city' => fn (Blueprint $t) => $t->string('city')->nullable(),
            'location' => fn (Blueprint $t) => $t->string('location', 500)->nullable(),
            'location_lat' => fn (Blueprint $t) => $t->decimal('location_lat', 10, 7)->nullable(),
            'location_lng' => fn (Blueprint $t) => $t->decimal('location_lng', 10, 7)->nullable(),
            'instructor' => fn (Blueprint $t) => $t->string('instructor')->nullable(),
            'event_date' => fn (Blueprint $t) => $t->date('event_date')->nullable(),
            'start_time' => fn (Blueprint $t) => $t->time('start_time')->nullable(),
            'end_time' => fn (Blueprint $t) => $t->time('end_time')->nullable(),
            'what_to_bring' => fn (Blueprint $t) => $t->longText('what_to_bring')->nullable(),
            'capacity' => fn (Blueprint $t) => $t->integer('capacity')->default(10),
            'is_active' => fn (Blueprint $t) => $t->boolean('is_active')->default(true),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_package_orders', [
            'customer_id' => fn (Blueprint $t) => $t->unsignedBigInteger('customer_id')->nullable(),
            'order_no' => fn (Blueprint $t) => $t->string('order_no', 80)->nullable(),
            'subtotal' => fn (Blueprint $t) => $t->decimal('subtotal', 10, 2)->default(0),
            'tax' => fn (Blueprint $t) => $t->decimal('tax', 10, 2)->default(0),
            'processing_fee' => fn (Blueprint $t) => $t->decimal('processing_fee', 10, 2)->default(0),
            'total' => fn (Blueprint $t) => $t->decimal('total', 10, 2)->default(0),
            'payment_status' => fn (Blueprint $t) => $t->string('payment_status', 50)->default('pending'),
            'payment_method' => fn (Blueprint $t) => $t->string('payment_method', 50)->nullable(),
            'card_last_four' => fn (Blueprint $t) => $t->string('card_last_four', 10)->nullable(),
            'paid_at' => fn (Blueprint $t) => $t->timestamp('paid_at')->nullable(),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_customer_credits', [
            'customer_id' => fn (Blueprint $t) => $t->unsignedBigInteger('customer_id')->nullable(),
            'package_id' => fn (Blueprint $t) => $t->unsignedBigInteger('package_id')->nullable(),
            'order_id' => fn (Blueprint $t) => $t->unsignedBigInteger('order_id')->nullable(),
            'total_classes' => fn (Blueprint $t) => $t->integer('total_classes')->default(0),
            'used_classes' => fn (Blueprint $t) => $t->integer('used_classes')->default(0),
            'remaining_classes' => fn (Blueprint $t) => $t->integer('remaining_classes')->default(0),
            'valid_from' => fn (Blueprint $t) => $t->date('valid_from')->nullable(),
            'valid_until' => fn (Blueprint $t) => $t->date('valid_until')->nullable(),
            'status' => fn (Blueprint $t) => $t->string('status', 50)->default('active'),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_session_bookings', [
            'customer_id' => fn (Blueprint $t) => $t->unsignedBigInteger('customer_id')->nullable(),
            'child_id' => fn (Blueprint $t) => $t->unsignedBigInteger('child_id')->nullable(),
            'customer_child_id' => fn (Blueprint $t) => $t->unsignedBigInteger('customer_child_id')->nullable(),
            'session_event_id' => fn (Blueprint $t) => $t->unsignedBigInteger('session_event_id')->nullable(),
            'package_id' => fn (Blueprint $t) => $t->unsignedBigInteger('package_id')->nullable(),
            'credit_id' => fn (Blueprint $t) => $t->unsignedBigInteger('credit_id')->nullable(),
            'cart_id' => fn (Blueprint $t) => $t->unsignedBigInteger('cart_id')->nullable(),
            'order_id' => fn (Blueprint $t) => $t->unsignedBigInteger('order_id')->nullable(),
            'order_item_id' => fn (Blueprint $t) => $t->unsignedBigInteger('order_item_id')->nullable(),
            'booking_no' => fn (Blueprint $t) => $t->string('booking_no', 80)->nullable(),
            'player_first' => fn (Blueprint $t) => $t->string('player_first', 100)->nullable(),
            'player_last' => fn (Blueprint $t) => $t->string('player_last', 100)->nullable(),
            'grad_year' => fn (Blueprint $t) => $t->integer('grad_year')->nullable(),
            'positions' => fn (Blueprint $t) => $t->longText('positions')->nullable(),
            'status' => fn (Blueprint $t) => $t->string('status', 50)->default('booked'),
            'booked_at' => fn (Blueprint $t) => $t->timestamp('booked_at')->nullable(),
            'reminder_email_sent_at' => fn (Blueprint $t) => $t->timestamp('reminder_email_sent_at')->nullable(),
            'cancelled_at' => fn (Blueprint $t) => $t->timestamp('cancelled_at')->nullable(),
            'completed_at' => fn (Blueprint $t) => $t->timestamp('completed_at')->nullable(),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_customer_package_carts', [
            'customer_id' => fn (Blueprint $t) => $t->unsignedBigInteger('customer_id')->nullable(),
            'booking_id' => fn (Blueprint $t) => $t->unsignedBigInteger('booking_id')->nullable(),
            'package_id' => fn (Blueprint $t) => $t->unsignedBigInteger('package_id')->nullable(),
            'source_type' => fn (Blueprint $t) => $t->string('source_type', 50)->default('direct'),
            'quantity' => fn (Blueprint $t) => $t->integer('quantity')->default(1),
            'unit_price' => fn (Blueprint $t) => $t->decimal('unit_price', 10, 2)->default(0),
            'total_price' => fn (Blueprint $t) => $t->decimal('total_price', 10, 2)->default(0),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_package_order_items', [
            'order_id' => fn (Blueprint $t) => $t->unsignedBigInteger('order_id')->nullable(),
            'package_id' => fn (Blueprint $t) => $t->unsignedBigInteger('package_id')->nullable(),
            'booking_id' => fn (Blueprint $t) => $t->unsignedBigInteger('booking_id')->nullable(),
            'package_name' => fn (Blueprint $t) => $t->string('package_name', 190)->nullable(),
            'quantity' => fn (Blueprint $t) => $t->integer('quantity')->default(1),
            'classes_per_package' => fn (Blueprint $t) => $t->integer('classes_per_package')->default(0),
            'unit_price' => fn (Blueprint $t) => $t->decimal('unit_price', 10, 2)->default(0),
            'total_price' => fn (Blueprint $t) => $t->decimal('total_price', 10, 2)->default(0),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_customer_credit_logs', [
            'customer_id' => fn (Blueprint $t) => $t->unsignedBigInteger('customer_id')->nullable(),
            'credit_id' => fn (Blueprint $t) => $t->unsignedBigInteger('credit_id')->nullable(),
            'booking_id' => fn (Blueprint $t) => $t->unsignedBigInteger('booking_id')->nullable(),
            'type' => fn (Blueprint $t) => $t->string('type', 50)->nullable(),
            'classes' => fn (Blueprint $t) => $t->integer('classes')->default(0),
            'note' => fn (Blueprint $t) => $t->text('note')->nullable(),
            'description' => fn (Blueprint $t) => $t->text('description')->nullable(),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);

        $this->addColumns('em_package_payment_logs', [
            'package_order_id' => fn (Blueprint $t) => $t->unsignedBigInteger('package_order_id')->nullable(),
            'customer_id' => fn (Blueprint $t) => $t->unsignedBigInteger('customer_id')->nullable(),
            'gateway' => fn (Blueprint $t) => $t->string('gateway', 50)->default('authorize_net'),
            'environment' => fn (Blueprint $t) => $t->string('environment', 190)->nullable(),
            'payment_method' => fn (Blueprint $t) => $t->string('payment_method', 50)->default('card'),
            'status' => fn (Blueprint $t) => $t->string('status', 50)->default('pending'),
            'subtotal' => fn (Blueprint $t) => $t->decimal('subtotal', 10, 2)->default(0),
            'processing_fee' => fn (Blueprint $t) => $t->decimal('processing_fee', 10, 2)->default(0),
            'tax' => fn (Blueprint $t) => $t->decimal('tax', 10, 2)->default(0),
            'amount' => fn (Blueprint $t) => $t->decimal('amount', 10, 2)->default(0),
            'response_code' => fn (Blueprint $t) => $t->string('response_code', 50)->nullable(),
            'transaction_id' => fn (Blueprint $t) => $t->string('transaction_id', 100)->nullable(),
            'auth_id' => fn (Blueprint $t) => $t->string('auth_id', 100)->nullable(),
            'message_code' => fn (Blueprint $t) => $t->string('message_code', 100)->nullable(),
            'message_text' => fn (Blueprint $t) => $t->text('message_text')->nullable(),
            'ref_id' => fn (Blueprint $t) => $t->string('ref_id', 100)->nullable(),
            'name_on_card' => fn (Blueprint $t) => $t->string('name_on_card', 190)->nullable(),
            'card_last_four' => fn (Blueprint $t) => $t->string('card_last_four', 10)->nullable(),
            'billing_first_name' => fn (Blueprint $t) => $t->string('billing_first_name', 100)->nullable(),
            'billing_last_name' => fn (Blueprint $t) => $t->string('billing_last_name', 100)->nullable(),
            'billing_email' => fn (Blueprint $t) => $t->string('billing_email', 190)->nullable(),
            'billing_phone' => fn (Blueprint $t) => $t->string('billing_phone', 50)->nullable(),
            'billing_street' => fn (Blueprint $t) => $t->string('billing_street', 190)->nullable(),
            'billing_city' => fn (Blueprint $t) => $t->string('billing_city', 100)->nullable(),
            'billing_state' => fn (Blueprint $t) => $t->string('billing_state', 100)->nullable(),
            'billing_zip' => fn (Blueprint $t) => $t->string('billing_zip', 30)->nullable(),
            'billing_country' => fn (Blueprint $t) => $t->string('billing_country', 100)->nullable(),
            'quantity' => fn (Blueprint $t) => $t->integer('quantity')->default(1),
            'paid_at' => fn (Blueprint $t) => $t->timestamp('paid_at')->nullable(),
            'created_at' => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ]);
    }

    public function down(): void
    {
        // Compatibility migration: intentionally non-destructive.
    }

    private function addColumns(string $table, array $columns): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column => $definition) {
            if (Schema::hasColumn($table, $column)) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($definition): void {
                $definition($blueprint);
            });
        }
    }
};
