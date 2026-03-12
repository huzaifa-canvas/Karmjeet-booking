<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionBooking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'coach_id',
        'session_id',
        'session_type',
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
        'card_holder_name',
        'price_per_session',
        'payment_method',
        'customer_id',
        'payment_status',
        'session_status',
        'email_reminder',
    ];

    protected $dates = [
        'date'
    ];

    public function getSession()
    {
       return $this->belongsTo(Coaching::class,'session_id');
    }
    public function getOnDemandSession()
    {
       return $this->belongsTo(ScheduleSession::class,'session_id');
    }
    public function getUser()
    {
       return $this->belongsTo(User::class,'user_id');
    }
    public function getTeacher()
    {
       return $this->belongsTo(User::class,'coach_id');
    }








}
