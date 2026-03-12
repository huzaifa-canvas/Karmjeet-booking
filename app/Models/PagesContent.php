<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagesContent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
    ];

    public function sections()
    {
      return $this->hasMany(PageSection::class,'page_id')->orderBy('order_priority');
    }

    public function sectionsFront()
    {
      return $this->hasMany(PageSection::class,'page_id')->where('status', 1)->orderBy('order_priority');
    }
}
