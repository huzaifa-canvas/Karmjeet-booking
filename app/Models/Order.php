<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'order_number',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'stripe_session_id',
        'stripe_payment_intent',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Boot: auto-generate order_number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class);
    }

    /**
     * Check if this is a guest order (from WordPress plugin).
     */
    public function getIsGuestAttribute()
    {
        return is_null($this->user_id);
    }

    /**
     * Get customer display name (registered user or guest).
     */
    public function getCustomerNameAttribute()
    {
        if ($this->is_guest) {
            return $this->guest_name ?? 'Guest';
        }
        return $this->user->name ?? 'N/A';
    }

    /**
     * Get customer email (registered user or guest).
     */
    public function getCustomerEmailAttribute()
    {
        if ($this->is_guest) {
            return $this->guest_email ?? '';
        }
        return $this->user->email ?? '';
    }

    /**
     * Status badge HTML helper.
     */
    public function getStatusBadgeAttribute()
    {
        $color = match ($this->status) {
            'pending'    => 'warning',
            'processing' => 'info',
            'completed'  => 'success',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };
        $text = ucfirst($this->status);
        return "<span class=\"badge rounded-pill bg-light-{$color}\">{$text}</span>";
    }

    /**
     * Payment Status badge HTML helper.
     */
    public function getPaymentStatusBadgeAttribute()
    {
        $color = match ($this->payment_status) {
            'unpaid'   => 'warning',
            'paid'     => 'success',
            'failed'   => 'danger',
            default    => 'secondary',
        };
        $text = ucfirst($this->payment_status);
        return "<span class=\"badge rounded-pill bg-light-{$color}\">{$text}</span>";
    }
}
