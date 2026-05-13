<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MeetingRoomController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// User Routes (harus login)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.bookings.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User Booking Routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [UserBookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/check-availability', [UserBookingController::class, 'checkAvailability'])->name('bookings.check');
    Route::patch('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');

    // User Settings Routes
    Route::get('/settings', [\App\Http\Controllers\User\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [\App\Http\Controllers\User\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [\App\Http\Controllers\User\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/notifications', [\App\Http\Controllers\User\SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::delete('/settings/account', [\App\Http\Controllers\User\SettingsController::class, 'deleteAccount'])->name('settings.delete');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('rooms', MeetingRoomController::class)->except(['show']);
    Route::resource('bookings', AdminBookingController::class)->only(['index', 'destroy']);
});

require __DIR__ . '/auth.php';
