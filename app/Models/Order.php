<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'tracking_number',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'delivery_type',
        'delivery_date',
        'delivery_time',
        'pickup_time',
        'payment_method',
        'payment_status',
        'cancellation_requested',
        'notes',
        'order_date',
        'estimated_delivery',
        'delivered_at',
        'user_id',
        'shipping_address_id',
        'billing_address_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'order_date' => 'datetime',
        'estimated_delivery' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(OrderAddress::class, 'shipping_address_id');
    }

    /**
     * Get the billing address for the order.
     */
    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(OrderAddress::class, 'billing_address_id');
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Alias for orderItems relationship for backward compatibility.
     */
    public function items(): HasMany
    {
        return $this->orderItems();
    }

    /**
     * Alias for shippingAddress relationship for backward compatibility.
     */
    public function address(): BelongsTo
    {
        return $this->shippingAddress();
    }

    /**
     * Get the tracking information for the order.
     */
    public function tracking(): HasOne
    {
        return $this->hasOne(Tracking::class);
    }

    /**
     * Scope a query to only include orders with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the formatted order number.
     */
    public function getFormattedOrderNumberAttribute()
    {
        return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if the order is completed.
     */
    public function getIsCompletedAttribute()
    {
        return in_array($this->status, ['delivered']);
    }

    /**
     * Check if the order is cancelled.
     */
    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get the status badge class for display.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'processing' => 'bg-primary',
            'shipped' => 'bg-info',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the total amount with proper fallback.
     */
    public function getTotalAttribute()
    {
        return $this->total_amount ?? 0;
    }
}
