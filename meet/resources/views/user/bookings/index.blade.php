<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Meeting Room</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Blink animation for urgent bookings */
        @keyframes blink-red {
            0%, 100% {
                border-color: #ef4444;
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            50% {
                border-color: #dc2626;
                box-shadow: 0 0 10px 3px rgba(239, 68, 68, 0.4);
            }
        }

        .booking-urgent {
            animation: blink-red 1.5s ease-in-out infinite;
            border-width: 2px;
        }

        /* Pulse animation for alert badge */
        @keyframes pulse-scale {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .alert-badge {
            animation: pulse-scale 1s ease-in-out infinite;
        }

        /* Style untuk slot yang sudah dibooking */
        .slot-booked {
            position: relative;
        }

        .slot-booked::after {
            content: "❌";
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 8px;
        }

        /* Tooltip styling */
        [tooltip] {
            position: relative;
        }

        [tooltip]::before {
            content: attr(tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: pre-wrap;
            text-align: center;
            min-width: 120px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s;
        }

        [tooltip]:hover::before {
            opacity: 1;
            visibility: visible;
        }

        /* Style untuk slot yang dipilih */
        .slot-selected {
            border-color: #10b981 !important;
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            font-weight: 600;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 transition-colors duration-200">
    <div class="min-h-screen">
        <!-- Navigation - Breeze Style -->
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
                <div class="flex justify-between h-14 sm:h-16">
                    <!-- Left Side - Logo -->
                    <div class="flex items-center">
                        <img src="{{ asset('build/assets/logo.png') }}"
                             alt="Logo"
                             class="mr-2 flex-shrink-0"
                             style="width: 60px; height: 26px; object-fit: contain;">
                        <span class="text-sm sm:text-lg lg:text-xl font-bold text-gray-800 truncate">
                            <span class="hidden sm:inline">Sinergi Ayu Semesta</span>
                            <span class="sm:hidden">SAS</span>
                        </span>
                    </div>

                    <!-- Right Side - User Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('user.bookings.index')">
                                    📅 {{ __('My Bookings') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('user.settings.index')">
                                    ⚙️ {{ __('Settings') }}
                                </x-dropdown-link>

                                <div class="border-t border-gray-100"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Hamburger (Mobile) -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu (Mobile) -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('user.bookings.index')" :active="request()->routeIs('user.bookings.*')">
                            📅 {{ __('My Bookings') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('user.settings.index')" :active="request()->routeIs('user.settings.*')">
                            ⚙️ {{ __('Settings') }}
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-3 sm:py-6">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
                @if (session('success'))
                    <div class="mb-3 sm:mb-4 bg-green-100 border border-green-400 text-green-700 px-3 py-2 sm:px-4 sm:py-3 rounded text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-3 sm:mb-4 bg-red-100 border border-red-400 text-red-700 px-3 py-2 sm:px-4 sm:py-3 rounded text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Form Booking -->
                    <div class="bg-white shadow rounded-lg p-4 sm:p-6 order-1 transition-colors duration-200">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">Buat Booking Baru</h2>
                        <form id="bookingForm" action="{{ route('user.bookings.store') }}" method="POST">
                            @csrf

                            <!-- Pilih Ruang -->
                            <div class="mb-3 sm:mb-4">
                                <label for="meeting_room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">Pilih Ruang</label>
                                <select name="meeting_room_id" id="meeting_room_id" required
                                    class="w-full text-sm sm:text-base border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 py-2">
                                    <option value="">-- Pilih Ruang --</option>
                                    @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('meeting_room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }} - {{ $room->location }} ({{ $room->capacity }} orang)
                                    </option>
                                    @endforeach
                                </select>
                                @error('meeting_room_id')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal -->
                            <div class="mb-3 sm:mb-4">
                                <label for="booking_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">Tanggal Mulai</label>

                                @php
                                    $today = \Carbon\Carbon::today();
                                    $currentDay = $today->day;
                                @endphp

                                @if($currentDay < 14)
                                    <div class="mb-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded text-xs text-yellow-800 dark:text-yellow-200">
                                        ⚠️ Booking bulan depan dapat dilakukan mulai tanggal 14 {{ $today->format('F Y') }}
                                    </div>
                                @endif

                                <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}"
                                    max="{{ $maxBookingDate }}"
                                    required
                                    class="w-full text-sm sm:text-base border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 py-2">
                                @error('booking_date')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Maksimal booking: {{ \Carbon\Carbon::parse($maxBookingDate)->format('d M Y') }}
                                </p>
                            </div>

                            <!-- Check Availability Button -->
                            <div class="mb-3 sm:mb-4">
                                <button type="button" id="checkAvailability"
                                    class="w-full px-4 py-2.5 sm:py-2 text-sm sm:text-base bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                    Cek Ketersediaan Jam
                                </button>
                            </div>

                            <!-- Slot Ketersediaan -->
                            <div id="availabilitySlots" class="mb-3 sm:mb-4 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jam Tersedia</label>
                                <div id="slotsContainer" class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 sm:gap-2 max-h-48 sm:max-h-60 overflow-y-auto">
                                    <!-- Slots will be loaded here -->
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Tips: Pilih slot berurutan untuk booking lebih dari 30 menit</p>
                            </div>

                            <!-- Jam Mulai & Selesai -->
                            <div class="grid grid-cols-2 gap-2 sm:gap-4 mb-3 sm:mb-4">
                                <div>
                                    <label for="start_time" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Jam Mulai</label>
                                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                                        class="w-full text-sm sm:text-base border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2">
                                    @error('start_time')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_time" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Jam Selesai</label>
                                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                                        class="w-full text-sm sm:text-base border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2">
                                    @error('end_time')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tujuan Meeting -->
                            <div class="mb-4 sm:mb-4">
                                <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Tujuan Meeting</label>
                                <textarea name="purpose" id="purpose" rows="3"
                                    class="w-full text-sm sm:text-base border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('purpose') }}</textarea>
                                @error('purpose')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full px-4 py-2.5 sm:py-2 text-sm sm:text-base bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">
                                Buat Booking
                            </button>
                        </form>
                    </div>

                    <!-- My Bookings -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6 order-2 transition-colors duration-200">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100 mb-3 sm:mb-4">Booking Saya</h2>
                        <div class="space-y-3 sm:space-y-4 max-h-[400px] sm:max-h-96 overflow-y-auto" id="bookings-container">
                            @forelse($myBookings as $booking)
                            <div class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-750 rounded-lg p-3 sm:p-4 transition-colors duration-200 booking-card"
                                data-booking-id="{{ $booking->id }}"
                                data-booking-date="{{ $booking->booking_date->format('Y-m-d') }}"
                                data-start-time="{{ $booking->start_time }}"
                                data-end-time="{{ $booking->end_time }}"
                                data-status="{{ $booking->status }}">
                                <div class="flex justify-between items-start mb-2 gap-2">
                                    <h3 class="font-semibold text-sm sm:text-base text-gray-900 dark:text-gray-100 flex-1 min-w-0 break-words">
                                        {{ $booking->meetingRoom->name }}
                                    </h3>
                                    <div class="flex flex-col items-end gap-1">
                                        <span class="px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full flex-shrink-0 booking-status-badge
                                            {{ $booking->status === 'active' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                            {{ $booking->status }}
                                        </span>
                                        <!-- Alert badge (hidden by default, shown by JS) -->
                                        <span class="hidden px-2 py-0.5 text-xs font-bold rounded-full bg-red-500 text-white alert-badge animate-pulse">
                                            🔔 Segera Dimulai!
                                        </span>
                                    </div>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                    📅 {{ $booking->booking_date->format('d M Y') }}<br>
                                    ⏰ {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}
                                </p>
                                <!-- Countdown Timer (hidden by default) -->
                                <p class="hidden text-xs font-bold text-red-600 dark:text-red-400 mt-2 countdown-timer">
                                    ⏱️ Mulai dalam <span class="countdown-text"></span>
                                </p>
                                <!-- Timer untuk auto-remove (hidden by default) -->
                                <p class="hidden text-xs font-medium text-gray-500 dark:text-gray-400 mt-2 remove-timer">
                                    🗑️ Card akan dihapus dalam <span class="remove-countdown"></span>
                                </p>
                                @if($booking->purpose)
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 line-clamp-2">{{ $booking->purpose }}</p>
                                @endif
                                @if($booking->status === 'active')
                                <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST" class="mt-2 sm:mt-3 cancel-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs sm:text-sm text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-medium"
                                        onclick="return confirm('Yakin ingin membatalkan booking?')">
                                        Batalkan Booking
                                    </button>
                                </form>
                                @endif
                            </div>
                            @empty
                            <p class="text-center text-gray-500 dark:text-gray-400 py-8 text-sm" id="empty-message">Belum ada booking</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Object untuk menyimpan timer removal per booking
    const removalTimers = {};

    function checkBookingStatus() {
        const bookingCards = document.querySelectorAll('.booking-card');
        const now = new Date();
        let hasActiveBookings = false;

        bookingCards.forEach(card => {
            const status = card.dataset.status;
            const bookingDate = card.dataset.bookingDate;
            const endTime = card.dataset.endTime;
            const bookingId = card.dataset.bookingId;

            if (status === 'active') {
                hasActiveBookings = true;

                // Gabungkan tanggal dan waktu selesai
                const endDateTime = new Date(`${bookingDate}T${endTime}`);

                // Jika waktu sekarang sudah melewati waktu selesai
                if (now > endDateTime) {
                    // Update status di data attribute
                    card.dataset.status = 'completed';

                    // Update tampilan status badge
                    const statusBadge = card.querySelector('.booking-status-badge');
                    statusBadge.textContent = 'selesai';
                    statusBadge.classList.remove(
                        'bg-green-100', 'dark:bg-green-900',
                        'text-green-800', 'dark:text-green-200'
                    );
                    statusBadge.classList.add(
                        'bg-gray-100', 'dark:bg-gray-700',
                        'text-gray-800', 'dark:text-gray-300'
                    );

                    // Sembunyikan tombol batalkan
                    const cancelForm = card.querySelector('.cancel-form');
                    if (cancelForm) {
                        cancelForm.style.display = 'none';
                    }

                    // Sembunyikan countdown timer jika ada
                    const countdownTimer = card.querySelector('.countdown-timer');
                    if (countdownTimer) {
                        countdownTimer.classList.add('hidden');
                    }

                    // Sembunyikan alert badge jika ada
                    const alertBadge = card.querySelector('.alert-badge');
                    if (alertBadge) {
                        alertBadge.classList.add('hidden');
                    }

                    // Mulai countdown 15 menit untuk menghapus card
                    if (!removalTimers[bookingId]) {
                        startRemovalCountdown(card, bookingId);
                    }
                }
            } else if (status === 'completed') {
                // Cek apakah sudah lewat 15 menit dari waktu selesai
                const endDateTime = new Date(`${bookingDate}T${endTime}`);
                const fifteenMinutesAfter = new Date(endDateTime.getTime() + 15 * 60 * 1000);

                if (now > fifteenMinutesAfter) {
                    // Hapus card dengan animasi
                    removeCard(card, bookingId);
                } else if (!removalTimers[bookingId]) {
                    // Mulai countdown jika belum ada
                    startRemovalCountdown(card, bookingId);
                }
            }
        });

        // Cek apakah masih ada booking setelah pengecekan
        checkEmptyState();
    }

    function startRemovalCountdown(card, bookingId) {
        const removeTimer = card.querySelector('.remove-timer');
        const removeCountdown = card.querySelector('.remove-countdown');

        if (!removeTimer || !removeCountdown) return;

        // Tampilkan timer removal
        removeTimer.classList.remove('hidden');

        const bookingDate = card.dataset.bookingDate;
        const endTime = card.dataset.endTime;
        const endDateTime = new Date(`${bookingDate}T${endTime}`);
        const removalTime = new Date(endDateTime.getTime() + 15 * 60 * 1000);

        // Update countdown setiap detik
        removalTimers[bookingId] = setInterval(() => {
            const now = new Date();
            const timeLeft = removalTime - now;

            if (timeLeft <= 0) {
                clearInterval(removalTimers[bookingId]);
                delete removalTimers[bookingId];
                removeCard(card, bookingId);
            } else {
                const minutes = Math.floor(timeLeft / 60000);
                const seconds = Math.floor((timeLeft % 60000) / 1000);
                removeCountdown.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
        }, 1000);
    }

    function removeCard(card, bookingId) {
        // Clear timer jika ada
        if (removalTimers[bookingId]) {
            clearInterval(removalTimers[bookingId]);
            delete removalTimers[bookingId];
        }

        // Animasi fade out
        card.style.transition = 'opacity 0.5s, transform 0.5s';
        card.style.opacity = '0';
        card.style.transform = 'translateX(100%)';

        // Hapus dari DOM setelah animasi selesai
        setTimeout(() => {
            card.remove();
            checkEmptyState();
        }, 500);
    }

    function checkEmptyState() {
        const bookingsContainer = document.getElementById('bookings-container');
        const remainingCards = bookingsContainer.querySelectorAll('.booking-card');

        // Hapus empty message jika ada
        const existingEmptyMessage = bookingsContainer.querySelector('#empty-message');
        if (existingEmptyMessage) {
            existingEmptyMessage.remove();
        }

        // Jika tidak ada card lagi, tampilkan pesan kosong
        if (remainingCards.length === 0) {
            const emptyMessage = document.createElement('p');
            emptyMessage.id = 'empty-message';
            emptyMessage.className = 'text-center text-gray-500 dark:text-gray-400 py-8 text-sm';
            emptyMessage.textContent = 'Belum ada booking';
            bookingsContainer.appendChild(emptyMessage);
        }
    }

    // Jalankan pengecekan pertama kali saat halaman dimuat
    checkBookingStatus();

    // Jalankan pengecekan setiap 1 menit (60000 ms)
    setInterval(checkBookingStatus, 60000);
});
</script>

    <script>
        // Validasi tanggal booking
        const bookingDateInput = document.getElementById('booking_date');
        const maxDate = '{{ $maxBookingDate }}';

        bookingDateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate > maxDate) {
                alert('Tanggal yang dipilih melebihi batas maksimal booking.\n\nBooking untuk bulan depan hanya dapat dilakukan mulai tanggal 14 bulan ini.');
                this.value = maxDate;
            }
        });

        // Variables untuk menyimpan state slots
        let availableSlots = [];
        let selectedSlots = [];

        // Check availability
        document.getElementById('checkAvailability').addEventListener('click', function() {
            const roomId = document.getElementById('meeting_room_id').value;
            const date = document.getElementById('booking_date').value;

            if (!roomId || !date) {
                alert('Silakan pilih ruang dan tanggal terlebih dahulu');
                return;
            }

            // Validasi tanggal maksimal
            if (date > maxDate) {
                alert('Tanggal yang dipilih melebihi batas maksimal booking.\n\nBooking untuk bulan depan hanya dapat dilakukan mulai tanggal 14 bulan ini.');
                return;
            }

            this.disabled = true;
            this.textContent = 'Mengecek...';

            // Reset selected slots
            selectedSlots = [];

            fetch('{{ route("user.bookings.check") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    room_id: roomId,
                    date: date
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                const container = document.getElementById('slotsContainer');
                const availabilityDiv = document.getElementById('availabilitySlots');

                container.innerHTML = '';
                availableSlots = data.slots;

                data.slots.forEach((slot, index) => {
                    const slotDiv = document.createElement('div');
                    slotDiv.className = 'relative';

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = `w-full p-2 text-xs sm:text-sm rounded border transition-colors slot-button ${
                        slot.available
                            ? 'border-green-300 bg-green-50 text-green-700 hover:bg-green-100'
                            : 'border-red-200 bg-red-50 text-red-700 cursor-not-allowed'
                    }`;
                    button.dataset.index = index;
                    button.dataset.start = slot.start;
                    button.dataset.end = slot.end;
                    button.dataset.available = slot.available;

                    // Create time text
                    const timeText = document.createElement('div');
                    timeText.className = 'font-medium';
                    timeText.textContent = `${slot.start} - ${slot.end}`;

                    button.appendChild(timeText);

                    // Add booking info if slot is booked
                    if (!slot.available && slot.booked_by) {
                        const bookedInfo = document.createElement('div');
                        bookedInfo.className = 'text-xs mt-1';

                        const bookedBy = document.createElement('div');
                        bookedBy.className = 'font-semibold';
                        bookedBy.textContent = `👤 ${slot.booked_by}`;

                        bookedInfo.appendChild(bookedBy);

                        // Add purpose if available
                        if (slot.booking_purpose) {
                            const purpose = document.createElement('div');
                            purpose.className = 'text-red-600 italic truncate';
                            purpose.textContent = slot.booking_purpose;
                            purpose.title = slot.booking_purpose;
                            bookedInfo.appendChild(purpose);
                        }

                        button.appendChild(bookedInfo);

                        // Add tooltip for more info
                        button.title = `Dibooking oleh: ${slot.booked_by}${slot.booking_purpose ? `\nTujuan: ${slot.booking_purpose}` : ''}`;
                    }

                    button.disabled = !slot.available;

                    if (slot.available) {
                        button.addEventListener('click', function() {
                            handleSlotSelection(this);
                        });
                    }

                    slotDiv.appendChild(button);
                    container.appendChild(slotDiv);
                });

                availabilityDiv.classList.remove('hidden');

                if (window.innerWidth < 640) {
                    availabilityDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.error) {
                    alert(error.error);
                } else {
                    alert('Gagal mengecek ketersediaan');
                }
            })
            .finally(() => {
                this.disabled = false;
                this.textContent = 'Cek Ketersediaan Jam';
            });
        });

        // Fungsi untuk menangani pemilihan slot
        function handleSlotSelection(clickedButton) {
            const slotIndex = parseInt(clickedButton.dataset.index);
            const slotStart = clickedButton.dataset.start;
            const slotEnd = clickedButton.dataset.end;

            // Cek apakah slot sudah dipilih
            const isSelected = selectedSlots.includes(slotIndex);

            if (isSelected) {
                // Hapus dari selected slots
                selectedSlots = selectedSlots.filter(index => index !== slotIndex);
                clickedButton.classList.remove('slot-selected');
            } else {
                // Tambahkan ke selected slots
                selectedSlots.push(slotIndex);
                clickedButton.classList.add('slot-selected');
            }

            // Urutkan selected slots
            selectedSlots.sort((a, b) => a - b);

            // Update waktu mulai dan selesai
            updateSelectedTimes();
        }

        // Fungsi untuk update waktu mulai dan selesai berdasarkan slot yang dipilih
        function updateSelectedTimes() {
            if (selectedSlots.length === 0) {
                // Reset jika tidak ada slot yang dipilih
                document.getElementById('start_time').value = '';
                document.getElementById('end_time').value = '';
                return;
            }

            // Ambil slot pertama dan terakhir dari yang dipilih
            const firstSlotIndex = selectedSlots[0];
            const lastSlotIndex = selectedSlots[selectedSlots.length - 1];

            const firstSlot = availableSlots[firstSlotIndex];
            const lastSlot = availableSlots[lastSlotIndex];

            // Set waktu mulai dari slot pertama dan waktu selesai dari slot terakhir
            document.getElementById('start_time').value = firstSlot.start;
            document.getElementById('end_time').value = lastSlot.end;

            // Highlight semua slot yang berurutan di antara first dan last
            highlightConsecutiveSlots(firstSlotIndex, lastSlotIndex);
        }

        // Fungsi untuk highlight slot yang berurutan
        function highlightConsecutiveSlots(startIndex, endIndex) {
            const allButtons = document.querySelectorAll('.slot-button');

            allButtons.forEach(button => {
                const buttonIndex = parseInt(button.dataset.index);
                const isAvailable = button.dataset.available === 'true';

                if (isAvailable) {
                    if (buttonIndex >= startIndex && buttonIndex <= endIndex) {
                        button.classList.add('slot-selected');
                        // Tambahkan ke selectedSlots jika belum ada
                        if (!selectedSlots.includes(buttonIndex)) {
                            selectedSlots.push(buttonIndex);
                        }
                    } else {
                        button.classList.remove('slot-selected');
                        // Hapus dari selectedSlots
                        selectedSlots = selectedSlots.filter(index => index !== buttonIndex);
                    }
                }
            });

            // Urutkan kembali selectedSlots
            selectedSlots.sort((a, b) => a - b);
        }

        // ===== BOOKING URGENT NOTIFICATION =====
        function checkUpcomingBookings() {
            const bookingCards = document.querySelectorAll('.booking-card');
            const now = new Date();

            bookingCards.forEach(card => {
                const bookingDate = card.dataset.bookingDate;
                const startTime = card.dataset.startTime;
                const status = card.dataset.status;

                // Only check active bookings
                if (status !== 'active') return;

                // Combine date and time
                const bookingDateTime = new Date(`${bookingDate}T${startTime}`);

                // Calculate time difference in minutes
                const diffMs = bookingDateTime - now;
                const diffMinutes = Math.floor(diffMs / 1000 / 60);

                const alertBadge = card.querySelector('.alert-badge');
                const countdownTimer = card.querySelector('.countdown-timer');
                const countdownText = card.querySelector('.countdown-text');

                // If booking is within 15 minutes (and in the future)
                if (diffMinutes >= 0 && diffMinutes <= 15) {
                    // Add blinking animation
                    card.classList.add('booking-urgent');

                    // Show alert badge
                    if (alertBadge) {
                        alertBadge.classList.remove('hidden');
                    }

                    // Show and update countdown
                    if (countdownTimer && countdownText) {
                        countdownTimer.classList.remove('hidden');

                        if (diffMinutes === 0) {
                            countdownText.textContent = 'kurang dari 1 menit';
                        } else if (diffMinutes === 1) {
                            countdownText.textContent = '1 menit';
                        } else {
                            countdownText.textContent = `${diffMinutes} menit`;
                        }
                    }

                } else if (diffMinutes < 0 && diffMinutes >= -60) {
                    // Booking is in progress (started but less than 1 hour ago)
                    card.classList.remove('booking-urgent');
                    if (alertBadge) {
                        alertBadge.classList.add('hidden');
                    }
                    if (countdownTimer) {
                        countdownTimer.classList.add('hidden');
                    }
                } else {
                    // Remove urgent styling for bookings not in the 15-minute window
                    card.classList.remove('booking-urgent');
                    if (alertBadge) {
                        alertBadge.classList.add('hidden');
                    }
                    if (countdownTimer) {
                        countdownTimer.classList.add('hidden');
                    }
                }
            });
        }

        // Check immediately on page load
        checkUpcomingBookings();

        // Check every 30 seconds
        setInterval(checkUpcomingBookings, 30000);
    </script>
</body>
</html>
