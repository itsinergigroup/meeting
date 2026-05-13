<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meeting_room_id',
        'booking_date',
        'start_time',
        'end_time',
        'purpose',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', Carbon::today())
            ->where('status', 'active')
            ->orderBy('booking_date')
            ->orderBy('start_time');
    }
}
