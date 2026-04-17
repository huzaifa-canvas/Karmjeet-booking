<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
