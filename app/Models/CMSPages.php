<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMSPages extends Model
{
    use HasFactory;
    protected $table = 'cms_pages';
    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */

   //  protected $guarded = [];
   protected $fillable = [
       'page_type',
       'data_key',
       'data_value',
   ];

   public function getValue($key)
   {
       return $this->select('data_value')->where('data_key',$key)->first();
   }
}
