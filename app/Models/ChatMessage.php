<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'is_admin',
        'is_read',
        'message_type',
        'attachments',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_read' => 'boolean',
        'attachments' => 'array',
    ];

    /**
     * Get the chat that owns the message.
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Get the user that sent the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include admin messages.
     */
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope a query to only include customer messages.
     */
    public function scopeCustomer($query)
    {
        return $query->where('is_admin', false);
    }

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read messages.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Check if the message is from admin.
     */
    public function getIsFromAdminAttribute()
    {
        return $this->is_admin;
    }

    /**
     * Check if the message is from customer.
     */
    public function getIsFromCustomerAttribute()
    {
        return !$this->is_admin;
    }

    /**
     * Get the message sender name.
     */
    public function getSenderNameAttribute()
    {
        if ($this->is_admin) {
            return $this->user->name ?? 'Admin';
        }
        return $this->user->name ?? 'Customer';
    }

    /**
     * Get the message time in a readable format.
     */
    public function getReadableTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the message date in a readable format.
     */
    public function getReadableDateAttribute()
    {
        return $this->created_at->format('M d, Y g:i A');
    }
}
