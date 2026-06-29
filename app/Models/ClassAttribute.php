<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name', 'status'];

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get only active attributes.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get a readable label for the type.
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'category'  => 'Category',
            'type'      => 'Type',
            'age_group' => 'Age Group',
            'format'    => 'Format',
            'room'      => 'Room / Gym Area',
        ];
        return $labels[$this->type] ?? ucfirst($this->type);
    }
}
