<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EMSessionEvent extends Model
{
    protected $table = 'em_session_events';

    /**
     * Keep the database payload explicit. The session form submits a temporary
     * `instructors[]` array which is collapsed into the real `instructor`
     * column by the controller; it must never be mass-assigned as a DB column.
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'documentation',
        'training_type',
        'street_address',
        'city',
        'location',
        'location_lat',
        'location_lng',
        'instructor',
        'event_date',
        'start_time',
        'end_time',
        'what_to_bring',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'capacity' => 'integer',
        'is_active' => 'boolean',
        'location_lat' => 'decimal:7',
        'location_lng' => 'decimal:7',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function bookings()
    {
        return $this->hasMany(EMSessionBooking::class, 'session_event_id');
    }

    /**
     * Only confirmed records are bookings. pending_payment is a Training cart
     * item and must not reduce availability or make a session full.
     */
    public function activeBookings()
    {
        return $this->bookings()
            ->whereIn('status', ['booked', 'paid', 'completed']);
    }

    public function bookedBookings()
    {
        return $this->activeBookings();
    }

    public function pendingReservations()
    {
        return $this->bookings()->where('status', 'pending_payment');
    }

    public function getBookedCountAttribute(): int
    {
        if (array_key_exists('bookings_count', $this->attributes)) {
            return (int) $this->attributes['bookings_count'];
        }

        return $this->activeBookings()->count();
    }

    public function getSpotsLeftAttribute(): ?int
    {
        $capacity = (int) $this->capacity;

        if ($capacity <= 0) {
            return null;
        }

        return max(0, $capacity - $this->booked_count);
    }

    public function getIsFullAttribute(): bool
    {
        $capacity = (int) $this->capacity;

        return $capacity > 0 && $this->booked_count >= $capacity;
    }

    public function getAddressAttribute(): ?string
    {
        $parts = array_values(array_filter([
            trim((string) $this->street_address),
            trim((string) $this->city),
        ], static fn ($value) => $value !== ''));

        if ($parts !== []) {
            return implode(', ', $parts);
        }

        $location = trim((string) $this->location);

        return $location !== '' ? $location : null;
    }
}
