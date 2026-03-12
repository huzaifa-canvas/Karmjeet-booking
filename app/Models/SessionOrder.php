<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coach_id',
        'session_id',
        'coach_name',
        'date',
        'start_time',
        'end_time',
        'start_end_time',
        'duration',
        'full_name',
        'email',
        'phone',
        'objective',
        'price_per_session',
        'payment_method',
        'payment_status',
        'session_status',
        'email_reminder',
    ];

    protected $dates = [
        'date'
    ];

    public function getSession()
    {
       return $this->belongsTo(ScheduleSession::class,'session_id');
    }
    public function getUser()
    {
       return $this->belongsTo(User::class,'user_id');
    }

}
