<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'in_stock',
        'is_featured',
        'is_flower_of_week',
        'is_active',
        'main_image',
        'gallery_images',
        'specifications',
        'sort_order',
        'rating',
        'review_count',
        'category_id',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_flower_of_week' => 'boolean',
        'is_active' => 'boolean',
        'gallery_images' => 'array',
        'specifications' => 'array',
        'sort_order' => 'integer',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user who created the product.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the users who have this product in their wishlist.
     */
    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    /**
     * Get the bookings that include this product.
     */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_product')
                    ->withPivot(['quantity', 'price'])
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include in-stock products.
     */
    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }

    /**
     * Scope a query to only include flower of the week products.
     */
    public function scopeFlowerOfWeek($query)
    {
        return $query->where('is_flower_of_week', true);
    }

    /**
     * Get the current price (sale price if available, otherwise regular price).
     */
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if the product is on sale.
     */
    public function getIsOnSaleAttribute()
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * Get the discount percentage.
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->is_on_sale) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Get the total units sold for this product.
     */
    public function getUnitsSoldAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    /**
     * Get the total revenue for this product.
     */
    public function getRevenueAttribute()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    /**
     * Get the full URL for the main image.
     */
    public function getMainImageUrlAttribute()
    {
        if (!$this->main_image) {
            return asset('images/placeholder-product.svg');
        }

        // If it's already a full URL, return as is
        if (str_starts_with($this->main_image, 'http')) {
            return $this->main_image;
        }

        // If it starts with /, it's a local path
        if (str_starts_with($this->main_image, '/')) {
            return asset($this->main_image);
        }

        // Otherwise, assume it's in uploads/products
        return asset('uploads/products/' . $this->main_image);
    }

    /**
     * Get the full URLs for gallery images.
     */
    public function getGalleryImageUrlsAttribute()
    {
        if (!$this->gallery_images || !is_array($this->gallery_images)) {
            return [];
        }

        return array_map(function ($image) {
            // If it's already a full URL, return as is
            if (str_starts_with($image, 'http')) {
                return $image;
            }

            // If it starts with /, it's a local path
            if (str_starts_with($image, '/')) {
                return asset($image);
            }

            // Otherwise, assume it's in uploads/products
            return asset('uploads/products/' . $image);
        }, $this->gallery_images);
    }
}
