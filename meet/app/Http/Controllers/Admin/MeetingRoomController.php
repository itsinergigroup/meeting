<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;

class MeetingRoomController extends Controller
{
    public function index()
    {
        $rooms = MeetingRoom::latest()->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:150',
            'capacity' => 'required|integer|min:0',
            'facilities' => 'nullable|string',
            'status' => 'required|in:available,maintenance',
        ]);

        MeetingRoom::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruang meeting berhasil ditambahkan.');
    }

    public function edit(MeetingRoom $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, MeetingRoom $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:150',
            'capacity' => 'required|integer|min:0',
            'facilities' => 'nullable|string',
            'status' => 'required|in:available,maintenance',
        ]);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruang meeting berhasil diupdate.');
    }

    public function destroy(MeetingRoom $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruang meeting berhasil dihapus.');
    }
}
