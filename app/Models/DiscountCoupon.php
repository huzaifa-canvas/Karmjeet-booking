<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    /**
     * Check if coupon is valid and usable
     */
    public function isValid()
    {
        if (!$this->is_active) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        if ($this->valid_from && now()->lt($this->valid_from)) return false;
        if ($this->valid_until && now()->gt($this->valid_until)) return false;
        return true;
    }

    /**
     * Calculate discount amount for a given subtotal
     */
    public function calculateDiscount($subtotal)
    {
        if (!$this->isValid()) return 0;
        if ($this->min_order_amount && $subtotal < $this->min_order_amount) return 0;

        if ($this->type === 'percentage') {
            return round($subtotal * ($this->value / 100), 2);
        }

        // Fixed discount — cannot exceed subtotal
        return min($this->value, $subtotal);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
