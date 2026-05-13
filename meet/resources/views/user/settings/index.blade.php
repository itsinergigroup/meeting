<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Settings</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
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
            <div class="max-w-5xl mx-auto px-3 sm:px-4 lg:px-8">
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

                <div class="mb-4 sm:mb-6">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">⚙️ Pengaturan Akun</h1>
                    <p class="text-sm text-gray-600 mt-1">Kelola profile dan preferensi Anda</p>
                </div>

                <div class="space-y-4 sm:space-y-6">
                    <!-- Profile Information -->
                    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">👤 Informasi Profile</h2>

                        <form action="{{ route('user.settings.profile') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">
                                    Simpan Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">🔒 Ubah Password</h2>

                        <form action="{{ route('user.settings.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                <input type="password" name="current_password" id="current_password" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" id="password" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Notification Preferences -->
                    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">🔔 Preferensi Notifikasi</h2>

                        <form action="{{ route('user.settings.notifications') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <input type="checkbox" name="email_notifications" id="email_notifications" value="1" checked
                                        class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="email_notifications" class="ml-3">
                                        <span class="block text-sm font-medium text-gray-700">Email Notifications</span>
                                        <span class="block text-xs text-gray-500">Terima notifikasi via email</span>
                                    </label>
                                </div>

                                <div class="flex items-start">
                                    <input type="checkbox" name="booking_reminders" id="booking_reminders" value="1" checked
                                        class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="booking_reminders" class="ml-3">
                                        <span class="block text-sm font-medium text-gray-700">Booking Reminders</span>
                                        <span class="block text-xs text-gray-500">Pengingat meeting akan dimulai</span>
                                    </label>
                                </div>

                                <div>
                                    <label for="reminder_time" class="block text-sm font-medium text-gray-700 mb-2">
                                        Waktu Pengingat (menit sebelum meeting)
                                    </label>
                                    <select name="reminder_time" id="reminder_time"
                                        class="w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="5">5 menit</option>
                                        <option value="10">10 menit</option>
                                        <option value="15" selected>15 menit</option>
                                        <option value="30">30 menit</option>
                                        <option value="60">60 menit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end mt-4">
                                <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">
                                    Simpan Preferensi
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Account Statistics -->
                    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">📊 Statistik Akun</h2>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-indigo-600">{{ $user->bookings()->count() }}</div>
                                <div class="text-xs text-gray-600 mt-1">Total Bookings</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ $user->bookings()->where('status', 'active')->count() }}</div>
                                <div class="text-xs text-gray-600 mt-1">Active</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-red-600">{{ $user->bookings()->where('status', 'cancelled')->count() }}</div>
                                <div class="text-xs text-gray-600 mt-1">Cancelled</div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-white shadow rounded-lg p-4 sm:p-6 border-2 border-red-200">
                        <h2 class="text-lg font-semibold text-red-600 mb-4">⚠️ Danger Zone</h2>

                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-sm text-gray-700 mb-4">
                                <strong>Hapus Akun:</strong> Tindakan ini tidak dapat dibatalkan. Semua data dan booking Anda akan dihapus permanen.
                            </p>

                            <button type="button" onclick="confirmDelete()"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium">
                                Hapus Akun
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Hapus Akun?</h3>
                <p class="text-sm text-gray-500 text-center mt-2">
                    Masukkan password Anda untuk konfirmasi. Tindakan ini tidak dapat dibatalkan.
                </p>

                <form action="{{ route('user.settings.delete') }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')

                    <input type="password" name="password" placeholder="Password Anda" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">

                    <div class="flex gap-3 mt-4">
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal on outside click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
