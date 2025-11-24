<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        $name = $this->first_name;

        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }

        $name .= ' ' . $this->last_name;

        return trim($name);
    }

    /**
     * Get the orders for the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the cart items for the user.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the wishlist items for the user.
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the products in the user's wishlist.
     */
    public function wishlistedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlists');
    }

    /**
     * Get the reviews written by the user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the products created by the user.
     */
    public function createdProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'created_by');
    }

    /**
     * Get the bookings created by the user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the chats created by the user.
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    /**
     * Get the chats assigned to this admin user.
     */
    public function assignedChats(): HasMany
    {
        return $this->hasMany(Chat::class, 'assigned_to');
    }

    /**
     * Get the newsletter subscription for the user.
     */
    
    /**
     * Check if the user has a specific product in their wishlist.
     */
    public function hasWishlisted(Product $product): bool
    {
        return $this->wishlistedProducts()->where('product_id', $product->id)->exists();
    }

    /**
     * Check if the user has a specific product in their cart.
     */
    public function hasInCart(Product $product): bool
    {
        return $this->cartItems()->where('product_id', $product->id)->exists();
    }

    /**
     * Get the cart total.
     */
    public function getCartTotalAttribute()
    {
        return $this->cartItems->sum('total_price');
    }

    /**
     * Get the cart item count.
     */
    public function getCartItemCountAttribute()
    {
        return $this->cartItems->sum('quantity');
    }
}
