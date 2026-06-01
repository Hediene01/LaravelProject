<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'user_id',
        'name',
        'keywords',
        'slug',
        'description',
        'detail',
        'meta_title',
        'meta_description',
        'price',
        'compare_price',
        'sku',
        'image_url',
        'gallery',
        'inventory',
        'min_stock',
        'discount',
        'average_rating',
        'reviews_count',
        'is_featured',
        'is_active',
        'attributes',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'average_rating' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'gallery' => 'array',
            'attributes' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)
            ->where('status', 'approved')
            ->latest();
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function syncReviewMetrics(): void
    {
        $approved = $this->reviews()->where('status', 'approved');

        $this->forceFill([
            'average_rating' => round((float) $approved->avg('rating'), 2),
            'reviews_count' => $approved->count(),
        ])->save();
    }
}
