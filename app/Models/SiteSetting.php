<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
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
    'type',
    'key',
    'value',
    ];
}
