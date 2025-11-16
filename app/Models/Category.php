<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'is_active',
        'sort_order',
        'parent_id',
        'is_occasion',
        'occasion_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'is_occasion' => 'boolean',
        'occasion_date' => 'date',
    ];

    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the parent category.
     */
    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the subcategories.
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Scope a query to only include occasion categories.
     */
    public function scopeOccasions($query)
    {
        return $query->where('is_occasion', true);
    }

    /**
     * Scope a query to only include regular categories (not occasions).
     */
    public function scopeRegular($query)
    {
        return $query->where('is_occasion', false);
    }

    /**
     * Get the full URL for the category image.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/placeholder-category.svg');
        }

        // If it's already a full URL, return as is
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        // If it starts with /, it's a local path
        if (str_starts_with($this->image, '/')) {
            return asset($this->image);
        }

        // If it contains 'categories/', it's from storage
        if (str_contains($this->image, 'categories/')) {
            return asset('storage/' . $this->image);
        }

        // Otherwise, assume it's in uploads/categories
        return asset('uploads/categories/' . $this->image);
    }
}
