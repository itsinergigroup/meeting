<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('user.settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('user.settings.index')
            ->with('success', 'Profile berhasil diupdate!');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('user.settings.index')
            ->with('success', 'Password berhasil diubah!');
    }

    public function updateNotifications(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'email_notifications' => 'nullable|boolean',
            'booking_reminders' => 'nullable|boolean',
            'reminder_time' => 'nullable|integer|min:5|max:60',
        ]);

        // Save to user preferences (you may need to add columns to users table)
        // For now, we'll use a simple approach

        return redirect()->route('user.settings.index')
            ->with('success', 'Notifikasi berhasil diupdate!');
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password' => 'required|current_password',
        ]);

        // Cancel all active bookings
        $user->bookings()->where('status', 'active')->update(['status' => 'cancelled']);

        // Logout
        auth()->logout();

        // Delete account
        $user->delete();

        return redirect('/')->with('success', 'Account berhasil dihapus.');
    }
}
