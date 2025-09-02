<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password', 'user_type', 'company_name',
        'business_license', 'phone', 'address', 'city', 'country', 'postal_code',
        'website', 'description', 'is_verified', 'is_active', 'credit_limit',
        'payment_terms', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'credit_limit' => 'decimal:2',
    ];

    const USER_TYPE_BUYER = 'buyer';
    const USER_TYPE_SELLER = 'seller';
    const USER_TYPE_BOTH = 'both';

    /**
     * Get all products listed by this seller
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all orders placed by this buyer
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all orders received by this seller
     */
    public function receivedOrders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /**
     * Get all quotations sent by this seller
     */
    public function sentQuotations()
    {
        return $this->hasMany(Quotation::class, 'seller_id');
    }

    /**
     * Get all quotations received by this buyer
     */
    public function receivedQuotations()
    {
        return $this->hasMany(Quotation::class, 'buyer_id');
    }

    /**
     * Get all reviews written by this user
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Check if user is a seller
     */
    public function isSeller()
    {
        return in_array($this->user_type, [self::USER_TYPE_SELLER, self::USER_TYPE_BOTH]);
    }

    /**
     * Check if user is a buyer
     */
    public function isBuyer()
    {
        return in_array($this->user_type, [self::USER_TYPE_BUYER, self::USER_TYPE_BOTH]);
    }

    /**
     * Check if user is verified
     */
    public function isVerified()
    {
        return $this->is_verified;
    }

    /**
     * Scope for verified users
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for sellers
     */
    public function scopeSellers($query)
    {
        return $query->whereIn('user_type', [self::USER_TYPE_SELLER, self::USER_TYPE_BOTH]);
    }

    /**
     * Scope for buyers
     */
    public function scopeBuyers($query)
    {
        return $query->whereIn('user_type', [self::USER_TYPE_BUYER, self::USER_TYPE_BOTH]);
    }
}
