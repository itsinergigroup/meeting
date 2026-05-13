@extends('admin.layouts.app')

@section('title', 'Tambah Ruang Meeting')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Tambah Ruang Meeting</h1>
</div>

<div class="bg-white shadow rounded-lg p-6">
    <form action="{{ route('admin.rooms.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Ruang</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
            <input type="text" name="location" id="location" value="{{ old('location') }}"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('location')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Kapasitas</label>
            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 0) }}" min="0"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('capacity')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="facilities" class="block text-sm font-medium text-gray-700 mb-2">Fasilitas</label>
            <textarea name="facilities" id="facilities" rows="3"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('facilities') }}</textarea>
            @error('facilities')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" id="status"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.rooms.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
