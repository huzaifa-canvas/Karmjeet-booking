<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'status',
        'requested_at',
        'meeting_date',
        'effective_cancellation_date',
        'notes',
        'admin_notes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'meeting_date' => 'date',
        'effective_cancellation_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Calculate the effective cancellation date (60 days from request)
     */
    public static function calculateEffectiveDate($requestedAt)
    {
        return \Carbon\Carbon::parse($requestedAt)->addDays(60);
    }
}
