<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'martial_arts_class_id',
        'name',
        'price',
        'interval',
        'class_limit_per_week',
        'is_tax_inclusive',
        'stripe_price_id',
        'is_active',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_tax_inclusive' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function martialArtsClass()
    {
        return $this->belongsTo(MartialArtsClass::class, 'martial_arts_class_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
