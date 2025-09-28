<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'carrier',
        'status',
        'current_location',
        'description',
        'estimated_delivery',
        'actual_delivery',
        'tracking_history',
        'order_id',
    ];

    protected $casts = [
        'estimated_delivery' => 'datetime',
        'actual_delivery' => 'datetime',
        'tracking_history' => 'array',
    ];

    /**
     * Get the order that owns the tracking.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope a query to only include tracking with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if the tracking is delivered.
     */
    public function getIsDeliveredAttribute()
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if the tracking is in transit.
     */
    public function getIsInTransitAttribute()
    {
        return in_array($this->status, ['picked_up', 'in_transit', 'out_for_delivery']);
    }

    /**
     * Get the latest tracking event.
     */
    public function getLatestEventAttribute()
    {
        if (!$this->tracking_history || empty($this->tracking_history)) {
            return null;
        }
        
        return end($this->tracking_history);
    }
}
