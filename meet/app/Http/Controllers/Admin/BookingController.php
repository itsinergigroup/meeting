<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'meetingRoom']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        if ($request->filled('meeting_room_id')) {
            $query->where('meeting_room_id', $request->meeting_room_id);
        }

        $bookings = $query->latest()->paginate(10);
        $meetingRooms = MeetingRoom::orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'meetingRooms'));
    }

    public function destroy(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}