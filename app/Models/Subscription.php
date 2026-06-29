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
        'selected_location',
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

    public function getStatusAttribute($value)
    {
        if ($value === 'active' && in_array($this->package_type, ['day_pass', 'weekly_pass'])) {
            $days = $this->package_type === 'day_pass' ? 1 : 7;
            if ($this->created_at && $this->created_at->copy()->addDays($days)->isPast()) {
                return 'expired';
            }
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return 'expired';
        }

        return $value;
    }

    public function scopeFilterStatus($query, $status)
    {
        if (!$status) {
            return $query;
        }

        if ($status === 'expired') {
            return $query->where(function($q) {
                $q->where('status', 'expired')
                  ->orWhere(function($subQ) {
                      $subQ->where('status', 'active')->where('package_type', 'day_pass')->where('created_at', '<', now()->subDay());
                  })
                  ->orWhere(function($subQ) {
                      $subQ->where('status', 'active')->where('package_type', 'weekly_pass')->where('created_at', '<', now()->subDays(7));
                  })
                  ->orWhere(function($subQ) {
                      $subQ->whereNotNull('ends_at')->where('ends_at', '<', now());
                  });
            });
        } elseif ($status === 'active') {
            return $query->where('status', 'active')
                  ->where(function($q) {
                      $q->whereNotIn('package_type', ['day_pass', 'weekly_pass'])
                        ->orWhere(function($subQ) {
                            $subQ->where('package_type', 'day_pass')->where('created_at', '>=', now()->subDay());
                        })
                        ->orWhere(function($subQ) {
                            $subQ->where('package_type', 'weekly_pass')->where('created_at', '>=', now()->subDays(7));
                        });
                  })
                  ->where(function($q) {
                      $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
                  });
        }

        return $query->where('status', $status);
    }
}
