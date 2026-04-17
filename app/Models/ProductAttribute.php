<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'status',
    ];

    // ------ Scopes ------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCategories($query)
    {
        return $query->where('type', 'category');
    }

    public function scopeBrands($query)
    {
        return $query->where('type', 'brand');
    }
}
