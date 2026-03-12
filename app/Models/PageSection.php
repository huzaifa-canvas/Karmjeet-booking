<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    // protected $table = '';
    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */

   //  protected $guarded = [];
   protected $fillable = [
       'page_id',
       'content',
       'section_colour',
       'order_priority',
       'status',
   ];
}
