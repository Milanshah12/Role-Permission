@extends('layouts.admin')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{ __("Welcome, ") . Auth::user()->getRoleNames()->first() ." ". Auth::user()->name }}
                </div>
                <div class="p-6 text-gray-900">
                    Current Time:
                    {{ now()->setTimezone($settings->Timezone)->format('Y-m-d H:i:s') }}
                    ({{ $settings->Timezone }})
                </div>
            </div>
        </div>
    </div>
@endsection
