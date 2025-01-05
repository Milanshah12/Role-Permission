@extends('layouts.admin')

@section('content')
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-semibold mb-4">Update Settings</h1>

        <form action="{{ route('settings.update') }}" method="POST" class="con bg-white p-6 rounded-lg shadow-lg">
            @csrf
            @method('PUT')


            <div class="mb-4">
                <label for="theme" class="block text-gray-700 font-medium mb-2">Theme:</label>
                <select name="theme" id="theme" class="border border-gray-300 rounded-md w-full p-2">
                    <option value="light" {{ $settings->theme == 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark" {{ $settings->theme == 'dark' ? 'selected' : '' }}>Dark</option>
                </select>
            </div>


            <div class="tit mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Title:</label>
                <input type="text" value="{{ $settings->title }}" name="title"
                    class="border border-gray-300 rounded-md w-full p-2">
            </div>


            <div class="mb-4">
                <label for="timezone" class="block text-gray-700 font-medium mb-2">TimeZone:</label>
                <select name="timezone" id="timezone" class="border border-gray-300 rounded-md w-full p-2">
                    <option value="Asia/Kathmandu" {{ $settings->Timezone == 'Asia/Kathmandu' ? 'selected' : '' }}>
                        Asia/Kathmandu</option>
                    <option value="Europe/London" {{ $settings->Timezone == 'Europe/London' ? 'selected' : '' }}>
                        Europe/London</option>
                    <option value="America/New_York" {{ $settings->Timezone == 'America/New_York' ? 'selected' : '' }}>
                        America/New_York</option>
                    <option value="Australia/Sydney" {{ $settings->Timezone == 'Australia/Sydney' ? 'selected' : '' }}>
                        Australia/Sydney</option>
                    <option value="Africa/Cairo" {{ $settings->Timezone == 'Africa/Cairo' ? 'selected' : '' }}>Africa/Cairo
                    </option>
                    <option value="Asia/Dubai" {{ $settings->Timezone == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai
                    </option>
                </select>
            </div>
            <div class="mb-4">
                <label for="font" class="block text-gray-700 font-medium mb-2">Font:</label>
                <select name="font" id="font" class="border border-gray-300 rounded-md w-full p-2">
                    <option value="Arial" {{ $settings->font == 'Arial' ? 'selected' : '' }}>Arial</option>
                    <option value="Helvetica" {{ $settings->font == 'Helvetica' ? 'selected' : '' }}>Helvetica</option>
                    <option value="Roboto" {{ $settings->font == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                    <option value="Georgia" {{ $settings->font == 'Georgia' ? 'selected' : '' }}>Georgia</option>
                </select>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>


        @if (session('success'))
            <div class="mt-4 text-green-500 text-lg font-medium">{{ session('success') }}</div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        /* You can add custom styles here */
        body {
            background-color: #f7fafc;
        }

        .container {
            max-width: 600px;
        }

        h1 {
            color: #2d3748;
        }

        input,
        select {
            font-size: 1rem;
            transition: all 0.3s;
        }

        input:focus,
        select:focus {
            border-color: #63b3ed;
            outline: none;
        }

        button:hover {
            background-color: #3182ce;
        }
    </style>
@endpush
