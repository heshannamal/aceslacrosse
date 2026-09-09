<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addColumns('em_customers', [
            'parent_type' => fn(Blueprint $t) => $t->integer('parent_type')->default(1),
            'relational_id' => fn(Blueprint $t) => $t->unsignedBigInteger('relational_id')->nullable(),
            'active' => fn(Blueprint $t) => $t->boolean('active')->default(true),
        ]);
        $this->addColumns('em_customer_children', [
            'team' => fn(Blueprint $t) => $t->string('team',150)->nullable(),
            'spring_team' => fn(Blueprint $t) => $t->string('spring_team',150)->nullable(),
            'position' => fn(Blueprint $t) => $t->string('position')->nullable(),
            'class_year' => fn(Blueprint $t) => $t->string('class_year',20)->nullable(),
            'birthdate' => fn(Blueprint $t) => $t->date('birthdate')->nullable(),
            'is_active' => fn(Blueprint $t) => $t->boolean('is_active')->default(true),
        ]);
        $this->addColumns('em_packages', [
            'slug' => fn(Blueprint $t) => $t->string('slug')->nullable(),
            'package_description' => fn(Blueprint $t) => $t->text('package_description')->nullable(),
            'available_classes' => fn(Blueprint $t) => $t->unsignedInteger('available_classes')->default(1),
            'is_active' => fn(Blueprint $t) => $t->boolean('is_active')->default(true),
            'active' => fn(Blueprint $t) => $t->boolean('active')->default(true),
            'status' => fn(Blueprint $t) => $t->string('status',50)->default('active'),
        ]);
        $this->addColumns('em_session_events', [
            'name' => fn(Blueprint $t) => $t->string('name')->nullable(),
            'description' => fn(Blueprint $t) => $t->text('description')->nullable(),
            'documentation' => fn(Blueprint $t) => $t->string('documentation')->nullable(),
            'training_type' => fn(Blueprint $t) => $t->string('training_type',50)->nullable(),
            'street_address' => fn(Blueprint $t) => $t->string('street_address')->nullable(),
            'city' => fn(Blueprint $t) => $t->string('city')->nullable(),
            'location' => fn(Blueprint $t) => $t->string('location',500)->nullable(),
            'location_lat' => fn(Blueprint $t) => $t->decimal('location_lat',10,7)->nullable(),
            'location_lng' => fn(Blueprint $t) => $t->decimal('location_lng',10,7)->nullable(),
            'instructor' => fn(Blueprint $t) => $t->string('instructor')->nullable(),
            'event_date' => fn(Blueprint $t) => $t->date('event_date')->nullable(),
            'start_time' => fn(Blueprint $t) => $t->time('start_time')->nullable(),
            'end_time' => fn(Blueprint $t) => $t->time('end_time')->nullable(),
            'what_to_bring' => fn(Blueprint $t) => $t->longText('what_to_bring')->nullable(),
            'capacity' => fn(Blueprint $t) => $t->integer('capacity')->default(10),
            'is_active' => fn(Blueprint $t) => $t->boolean('is_active')->default(true),
        ]);
        $this->addColumns('em_customer_credits', [
            'remaining_classes' => fn(Blueprint $t) => $t->integer('remaining_classes')->default(0),
            'used_classes' => fn(Blueprint $t) => $t->integer('used_classes')->default(0),
            'status' => fn(Blueprint $t) => $t->string('status',50)->default('active'),
            'valid_from' => fn(Blueprint $t) => $t->date('valid_from')->nullable(),
            'valid_until' => fn(Blueprint $t) => $t->date('valid_until')->nullable(),
        ]);
        $this->addColumns('em_session_bookings', [
            'customer_child_id' => fn(Blueprint $t) => $t->unsignedBigInteger('customer_child_id')->nullable(),
            'package_id' => fn(Blueprint $t) => $t->unsignedBigInteger('package_id')->nullable(),
            'credit_id' => fn(Blueprint $t) => $t->unsignedBigInteger('credit_id')->nullable(),
            'cart_id' => fn(Blueprint $t) => $t->unsignedBigInteger('cart_id')->nullable(),
            'order_id' => fn(Blueprint $t) => $t->unsignedBigInteger('order_id')->nullable(),
            'booking_no' => fn(Blueprint $t) => $t->string('booking_no',80)->nullable(),
            'status' => fn(Blueprint $t) => $t->string('status',50)->default('booked'),
            'booked_at' => fn(Blueprint $t) => $t->timestamp('booked_at')->nullable(),
            'cancelled_at' => fn(Blueprint $t) => $t->timestamp('cancelled_at')->nullable(),
        ]);
    }
    public function down(): void {}
    private function addColumns(string $table, array $columns): void
    {
        if (!Schema::hasTable($table)) return;
        foreach ($columns as $column => $definition) {
            if (!Schema::hasColumn($table, $column)) {
                Schema::table($table, function (Blueprint $blueprint) use ($definition) { $definition($blueprint); });
            }
        }
    }
};
