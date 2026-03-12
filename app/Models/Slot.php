<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'coaching_id',
        'days',
        'start_time',
        'end_time',
        'duration',
        'session',
    ];

    protected $casts = [
        'session' => 'object',
        'days' => 'object'
   ];

   public function getSession()
   {
      return $this->belongsTo(Coaching::class,'session_id');
   }
   public function getUser()
   {
      return $this->belongsTo(User::class,'user_id');
   }

}
