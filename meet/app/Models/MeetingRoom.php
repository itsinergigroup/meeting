<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'facilities',
        'status',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
