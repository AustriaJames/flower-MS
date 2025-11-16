<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'event_type',
        'event_date',
        'event_time',
        'guest_count',
        'venue_address',
        'contact_person',
        'contact_phone',
        'special_requirements',
        'venue', // Keep for backward compatibility
        'requirements', // Keep for backward compatibility
        'budget_range',
        'status',
        'admin_notes',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'event_date' => 'date',
        'requirements' => 'array',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category for the booking.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the products associated with the booking.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'booking_product')
                    ->withPivot(['quantity', 'price'])
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include bookings with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include upcoming bookings.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    /**
     * Scope a query to only include past bookings.
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->toDateString());
    }

    /**
     * Get the formatted event date.
     */
    public function getFormattedEventDateAttribute()
    {
        return $this->event_date->format('F d, Y');
    }

    /**
     * Get the formatted event time.
     */
    public function getFormattedEventTimeAttribute()
    {
        return $this->event_time;
    }

    /**
     * Check if the booking is confirmed.
     */
    public function getIsConfirmedAttribute()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if the booking is pending.
     */
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the booking is cancelled.
     */
    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if the booking is completed.
     */
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'rescheduled' => 'bg-warning',
            'cancelled' => 'bg-danger',
            'completed' => 'bg-success',
            default => 'bg-secondary',
        };
    }
}
