<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionPayment extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'card_holder_name',
        'amount',
        'payment_method',
        'status'
    ];

    public function getSession()
    {
       return $this->belongsTo(SessionBooking::class,'session_id');
    }

    public function getUser()
    {
       return $this->belongsTo(User::class,'user_id');
    }
}
