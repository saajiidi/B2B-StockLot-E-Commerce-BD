<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'order_id', 'rating', 'title', 'review', 'is_verified', 'is_approved'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
    ];

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order (for verified purchases)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for verified reviews
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
