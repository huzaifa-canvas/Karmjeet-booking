<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MartialArtsClass extends Model
{
    use HasFactory;

    protected $table = 'martial_arts_classes';

    protected $fillable = [
        'name',
        'image',
        'description',
        'category',
        'type',
        'level',
        'age_group',
        'format',
        'instructor',
        'room',
        'price',
        'is_tax_inclusive',
        'status',
        'stripe_product_id',
        'stripe_price_id',
        'show_drop_in_options',
        'unlimited_price',
        'day_pass_price',
        'weekly_pass_price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'unlimited_price' => 'decimal:2',
        'day_pass_price' => 'decimal:2',
        'weekly_pass_price' => 'decimal:2',
        'is_tax_inclusive' => 'boolean',
        'show_drop_in_options' => 'boolean',
    ];

    // Filter scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFilterCategory($query, $category)
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeFilterType($query, $type)
    {
        return $type ? $query->where('type', $type) : $query;
    }

    public function scopeFilterLevel($query, $level)
    {
        return $level ? $query->where('level', $level) : $query;
    }

    public function scopeFilterAgeGroup($query, $ageGroup)
    {
        return $ageGroup ? $query->where('age_group', $ageGroup) : $query;
    }

    public function scopeFilterFormat($query, $format)
    {
        return $format ? $query->where('format', $format) : $query;
    }

    // Constants for filter dropdowns
    public const CATEGORIES = [
        'Kids', 'Youth', 'Teens', 'Adults', 'Women Only',
        'Competition', 'Fitness / Conditioning', 'Open Training', 'Private'
    ];

    public const TYPES = [
        'Muay Thai', 'Brazilian Jiu-Jitsu', 'Grappling', 'MMA', 'Kickboxing', 'Fitness'
    ];

    public const LEVELS = [
        'Beginner', 'Intermediate', 'Advanced', 'All Levels'
    ];

    public const AGE_GROUPS = [
        '5-8', '9-11', '12-16', '5-6', '7-9', '10-15', 'Adults', 'All Ages'
    ];

    public const FORMATS = [
        'Gi', 'No-Gi', 'Striking', 'Grappling', 'Mixed'
    ];
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'martial_arts_class_id');
    }

    public function pricingPlans()
    {
        return $this->hasMany(PricingPlan::class, 'martial_arts_class_id');
    }
}
