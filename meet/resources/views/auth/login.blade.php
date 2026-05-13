<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 border border-gray-100">

            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('build/assets/logo.png') }}" alt="Logo" class="w-40 h-auto">
            </div>

            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
                Login ke Akun Anda
            </h1>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email"
                        class="block mt-1 w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password"
                        class="block mt-1 w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                        type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                 <!-- Forgot Password -->
                @if (Route::has('password.request'))
                    <div class="text-right mt-2">
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-gray-600 hover:text-orange-600 transition-colors duration-200">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-between mt-6 gap-3">
                    <!-- Login Button -->
                    <button type="submit"
                        class="w-full sm:w-1/2 bg-orange-600 hover:bg-orange-700 text-white font-semibold px-5 py-3 rounded-lg transition-all duration-200 shadow-md">
                        {{ __('Log in') }}
                    </button>

                    <!-- Register Button -->
                    {{-- <a href="{{ route('register') }}"
                        class="w-full sm:w-1/2 border border-orange-600 text-orange-600 hover:bg-orange-600 hover:text-white font-semibold px-5 py-3 rounded-lg text-center transition-all duration-200 shadow-md">
                        {{ __('Register') }}
                    </a> --}}
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
