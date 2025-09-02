<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_number', 'order_id', 'buyer_id', 'seller_id', 'status',
        'quoted_price', 'original_price', 'discount_percentage', 'notes',
        'valid_until', 'responded_at'
    ];

    protected $casts = [
        'quoted_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'valid_until' => 'datetime',
        'responded_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get the order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the buyer
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the seller
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Check if quotation is expired
     */
    public function isExpired()
    {
        return $this->valid_until && $this->valid_until->isPast();
    }

    /**
     * Generate unique quotation number
     */
    public static function generateQuotationNumber()
    {
        return 'QUO-' . date('Y') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }
}
