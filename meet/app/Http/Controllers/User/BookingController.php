<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $rooms = MeetingRoom::available()->get();
        $myBookings = Booking::where('user_id', auth()->id())
            ->with('meetingRoom')
            ->upcoming()
            ->get();

        // Hitung tanggal maksimal yang bisa dibooking
        $maxBookingDate = $this->getMaxBookingDate();

        return view('user.bookings.index', compact('rooms', 'myBookings', 'maxBookingDate'));
    }

    /**
     * Menghitung tanggal maksimal yang bisa dibooking
     * Logika:
     * - Jika hari ini < 14 bulan ini: hanya bisa booking sampai akhir bulan ini
     * - Jika hari ini >= 14 bulan ini: bisa booking sampai akhir bulan depan
     */
    private function getMaxBookingDate()
    {
        $today = Carbon::today();
        $currentDay = $today->day;

        if ($currentDay < 14) {
            // Belum tanggal 14, hanya bisa booking bulan ini
            return $today->copy()->endOfMonth()->format('Y-m-d');
        } else {
            // Sudah tanggal 14 atau lebih, bisa booking sampai akhir bulan depan
            return $today->copy()->addMonth()->endOfMonth()->format('Y-m-d');
        }
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:meeting_rooms,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        // Validasi tambahan untuk maksimal tanggal booking
        $maxBookingDate = $this->getMaxBookingDate();
        if ($request->date > $maxBookingDate) {
            return response()->json([
                'error' => 'Booking untuk bulan depan hanya dapat dilakukan mulai tanggal 14 bulan ini.',
                'max_date' => $maxBookingDate
            ], 422);
        }

        $room = MeetingRoom::findOrFail($request->room_id);
        $date = $request->date;

        // Jam kerja 08:00 - 17:00
        $workStart = Carbon::parse('08:00');
        $workEnd = Carbon::parse('17:00');
        $allSlots = [];

        // Generate semua slot per 30 menit
        $current = $workStart->copy();
        while ($current < $workEnd) {
            $slotStart = $current->format('H:i');
            $slotEnd = $current->copy()->addMinutes(30)->format('H:i');

            $allSlots[] = [
                'start' => $slotStart,
                'end' => $slotEnd,
                'available' => true,
                'booked_by' => null,
                'booking_purpose' => null
            ];

            $current->addMinutes(30);
        }

        // Ambil booking yang sudah ada dengan data user
        $bookedSlots = Booking::where('meeting_room_id', $request->room_id)
            ->where('booking_date', $date)
            ->where('status', 'active')
            ->with('user') // Eager load user data
            ->get();

        // Tandai slot yang sudah dibooking dan tambahkan info pemesan
        foreach ($allSlots as &$slot) {
            foreach ($bookedSlots as $booking) {
                $slotStart = Carbon::parse($slot['start']);
                $slotEnd = Carbon::parse($slot['end']);
                $bookStart = Carbon::parse($booking->start_time);
                $bookEnd = Carbon::parse($booking->end_time);

                // Cek overlap - slot tidak tersedia jika ada irisan waktu
                if ($slotStart < $bookEnd && $slotEnd > $bookStart) {
                    $slot['available'] = false;
                    $slot['booked_by'] = $booking->user->name;
                    $slot['booking_purpose'] = $booking->purpose;
                    break;
                }
            }
        }

        return response()->json([
            'room' => $room,
            'date' => $date,
            'slots' => $allSlots
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'purpose' => 'nullable|string|max:500',
        ]);

        // Validasi tambahan untuk maksimal tanggal booking
        $maxBookingDate = $this->getMaxBookingDate();
        if ($validated['booking_date'] > $maxBookingDate) {
            return back()->with('error', 'Booking untuk bulan depan hanya dapat dilakukan mulai tanggal 14 bulan ini.');
        }

        // Validasi durasi minimal 30 menit
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = Carbon::parse($validated['end_time']);
        $durationInMinutes = $startTime->diffInMinutes($endTime);

        if ($durationInMinutes < 30) {
            return back()->with('error', 'Durasi booking minimal 30 menit.');
        }

        // Validasi jam kerja (08:00 - 17:00)
        $workStart = Carbon::parse('08:00');
        $workEnd = Carbon::parse('17:00');

        if ($startTime < $workStart || $endTime > $workEnd) {
            return back()->with('error', 'Jam booking harus dalam jam kerja (08:00 - 17:00).');
        }

        $validated['user_id'] = auth()->id();

        // Single booking only - no repeat functionality
        return $this->createSingleBooking($validated);
    }

    private function createSingleBooking($validated)
    {
        // Cek conflict
        $conflict = $this->checkBookingConflict(
            $validated['meeting_room_id'],
            $validated['booking_date'],
            $validated['start_time'],
            $validated['end_time']
        );

        if ($conflict) {
            return back()->with('error', 'Jadwal yang dipilih sudah dibooking. Silakan pilih jadwal lain.');
        }

        Booking::create($validated);

        return redirect()->route('user.bookings.index')
            ->with('success', 'Booking berhasil dibuat!');
    }

    private function checkBookingConflict($roomId, $date, $startTime, $endTime)
    {
        return Booking::where('meeting_room_id', $roomId)
            ->where('booking_date', $date)
            ->where('status', 'active')
            ->where(function ($query) use ($startTime, $endTime) {
                // Conflict terjadi jika:
                // 1. Booking baru mulai di tengah booking existing (start_time < endTime && start_time >= startTime)
                // 2. Booking baru selesai di tengah booking existing (end_time > startTime && end_time <= endTime)
                // 3. Booking baru menutupi seluruh booking existing (startTime <= start_time && endTime >= end_time)

                // Gunakan logika: booking conflict jika start_time < end_time_baru DAN end_time > start_time_baru
                $query->whereRaw("start_time < ? AND end_time > ?", [$endTime, $startTime]);
            })
            ->exists();
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('user.bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}