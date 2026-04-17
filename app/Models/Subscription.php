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
}
