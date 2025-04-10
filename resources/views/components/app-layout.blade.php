<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(config('app.env') === 'local')
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

    <title>{{ config('app.name', 'Visual Sentinel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <a href="/" class="text-2xl font-bold gradient-text">Visual Sentinel</a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <button type="button" class="dark-mode-toggle rounded-md p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            <span class="sr-only">Toggle dark mode</span>
                            <svg class="h-6 w-6 dark-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                            </svg>
                            <svg class="h-6 w-6 light-icon hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            </svg>
                        </button>
                        @auth
                            <a href="{{ route('dashboard') }}" class="ml-4 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="ml-4 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400">Login</a>
                            <a href="{{ route('register') }}" class="ml-4 inline-flex items-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8 md:flex md:items-center md:justify-between">
                    <div class="flex space-x-6 md:order-2">
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">GitHub</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <p class="mt-8 text-base text-gray-400 md:order-1 md:mt-0">
                        &copy; 2024 Visual Sentinel. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Dark mode toggle setup occurs via resources/js/darkMode.js
        document.addEventListener('DOMContentLoaded', function() {
            // Using the global toggleDarkMode function defined in darkMode.js
            const darkModeToggle = document.querySelector('.dark-mode-toggle');
            if (darkModeToggle) {
                darkModeToggle.removeAttribute('onclick'); // Remove any existing handler
                darkModeToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.toggleDarkMode();
                });
            }
        });
    </script>
</body>
</html> 