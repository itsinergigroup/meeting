<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRooms = MeetingRoom::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalBookings = Booking::count();
        $activeBookings = Booking::active()->count();

        $recentBookings = Booking::with(['user', 'meetingRoom'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalRooms',
            'totalUsers',
            'totalBookings',
            'activeBookings',
            'recentBookings'
        ));
    }
}
