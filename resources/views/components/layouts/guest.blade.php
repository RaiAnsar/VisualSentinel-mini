<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{'dark': darkMode}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Visual Sentinel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('welcome') }}" class="flex items-center">
                                <img class="h-20 w-auto" src="{{ Vite::asset('public/images/logo.svg') }}" alt="Visual Sentinel Logo">
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Dark mode toggle -->
                        <button 
                            type="button" 
                            class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none" 
                            onclick="toggleDarkMode()"
                        >
                            <!-- Sun icon -->
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 hidden dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <!-- Moon icon -->
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 block dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                        @if (Route::has('login'))
                            <div class="space-x-6">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="text-base font-semibold text-[#7B42F6] hover:text-[#4A6FFB]">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-base font-semibold text-[#7B42F6] hover:text-[#4A6FFB]">Log in</a>
                                    <a href="{{ route('register') }}" class="text-base font-semibold rounded-md bg-gradient-to-r from-[#7B42F6] to-[#4A6FFB] px-4 py-2 text-white shadow-sm hover:from-[#6935D9] hover:to-[#4162DE]">Register</a>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 flex">
            {{ $slot }}
        </main>
    </div>
</body>
</html> 