<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MeetingRoom;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin System',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Regular Users
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create Meeting Rooms
        MeetingRoom::create([
            'name' => 'Ruang Mawar',
            'location' => 'Lantai 1',
            'capacity' => 10,
            'facilities' => 'AC, Proyektor, Whiteboard',
            'status' => 'available',
        ]);

        MeetingRoom::create([
            'name' => 'Ruang Melati',
            'location' => 'Lantai 2',
            'capacity' => 8,
            'facilities' => 'AC, TV LED',
            'status' => 'available',
        ]);

        MeetingRoom::create([
            'name' => 'Ruang Anggrek',
            'location' => 'Lantai 3',
            'capacity' => 15,
            'facilities' => 'AC, Proyektor, TV LED, Whiteboard',
            'status' => 'available',
        ]);

        // Create Sample Booking
        Booking::create([
            'user_id' => 2,
            'meeting_room_id' => 1,
            'booking_date' => date('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'purpose' => 'Meeting Tim Project A',
            'status' => 'active',
        ]);
    }
}
