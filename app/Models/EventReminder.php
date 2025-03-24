<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'event_id',
        'description',
        'event_date',
        'status',
        'user_id',
        'reminder_sent_at',
        'emails'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    /**
     * Get the user associated with this reminder.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the event is upcoming.
     */
    public function isUpcoming()
    {
        return $this->event_date->isFuture();
    }

    /**
     * Check if the event is past.
     */
    public function isPast()
    {
        return $this->event_date->isPast();
    }
}
