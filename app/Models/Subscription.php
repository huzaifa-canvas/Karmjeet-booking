<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'martial_arts_class_id',
        'stripe_subscription_id',
        'stripe_customer_id',
        'status',
        'next_payment_date',
        'ends_at',
        'package_type',
    ];

    protected $casts = [
        'next_payment_date' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function martialArtsClass()
    {
        return $this->belongsTo(MartialArtsClass::class, 'martial_arts_class_id');
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function cancellationRequest()
    {
        return $this->hasOne(CancellationRequest::class)->latest();
    }
    public function getPriceAttribute()
    {
        if (!$this->martialArtsClass) {
            return 0;
        }

        if ($this->package_type === 'unlimited') {
            return $this->martialArtsClass->unlimited_price ?? $this->martialArtsClass->price;
        } elseif ($this->package_type === 'day_pass') {
            return $this->martialArtsClass->day_pass_price ?? 0;
        } elseif ($this->package_type === 'weekly_pass') {
            return $this->martialArtsClass->weekly_pass_price ?? 0;
        }

        return $this->martialArtsClass->price;
    }
}
