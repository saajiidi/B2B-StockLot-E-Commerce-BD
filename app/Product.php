<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'sku', 'category_id',
        'user_id', 'price', 'bulk_price_1', 'bulk_price_2', 'bulk_price_3',
        'bulk_qty_1', 'bulk_qty_2', 'bulk_qty_3', 'stock_quantity',
        'min_order_quantity', 'weight', 'dimensions', 'material', 'color',
        'size', 'brand', 'country_of_origin', 'is_stocklot', 'is_active',
        'featured_image', 'gallery_images', 'meta_title', 'meta_description'
    ];

    protected $casts = [
        'is_stocklot' => 'boolean',
        'is_active' => 'boolean',
        'gallery_images' => 'array',
        'price' => 'decimal:2',
        'bulk_price_1' => 'decimal:2',
        'bulk_price_2' => 'decimal:2',
        'bulk_price_3' => 'decimal:2',
    ];

    /**
     * Get the category that owns the product
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user (seller) that owns the product
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all order items for this product
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all reviews for this product
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for stocklot products
     */
    public function scopeStocklot($query)
    {
        return $query->where('is_stocklot', true);
    }

    /**
     * Scope for products in stock
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    /**
     * Get bulk price based on quantity
     */
    public function getBulkPrice($quantity)
    {
        if ($quantity >= $this->bulk_qty_3 && $this->bulk_price_3) {
            return $this->bulk_price_3;
        } elseif ($quantity >= $this->bulk_qty_2 && $this->bulk_price_2) {
            return $this->bulk_price_2;
        } elseif ($quantity >= $this->bulk_qty_1 && $this->bulk_price_1) {
            return $this->bulk_price_1;
        }
        
        return $this->price;
    }

    /**
     * Check if product is low in stock
     */
    public function isLowStock($threshold = 10)
    {
        return $this->stock_quantity <= $threshold;
    }

    /**
     * Get average rating
     */
    public function getAverageRating()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }
}
