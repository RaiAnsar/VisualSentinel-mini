<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $website->name }}
        </h2>
    </x-slot>
    
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Custom Switch Styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .switch label {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 34px;
        }
        
        .switch label:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
        }
        
        .switch input:checked + label {
            background-color: #4f46e5;
        }
        
        .switch input:checked + label:before {
            -webkit-transform: translateX(30px);
            -ms-transform: translateX(30px);
            transform: translateX(30px);
        }
        
        /* Dark mode styles */
        .dark .switch label {
            background-color: #4b5563;
        }
        
        .dark .switch input:checked + label {
            background-color: #6366f1;
        }
        
        /* Spinner Animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .spinner {
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        
        .hidden {
            display: none;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Header with breadcrumbs and quick actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <a href="{{ route('websites.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3 flex items-center">
                        <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>All Websites</span>
                    </a>
                    <span class="text-gray-500 dark:text-gray-400 mx-2">/</span>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white truncate">{{ $website->name }}</h1>
                </div>
                <div class="mt-3 sm:mt-0 flex flex-wrap items-center space-x-2">
                    <!-- Action buttons -->
                    <div class="mt-4 flex space-x-3">
                        <a href="{{ $website->url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Visit Site
                        </a>
                        
                        <button id="check-now-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg id="check-icon" class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <svg id="check-spinner" class="-ml-1 mr-2 h-5 w-5 spinner hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span id="check-text">Check Now</span>
                        </button>
                        
                        <a href="{{ route('websites.edit', $website->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit
                        </a>
                        
                        <div class="relative" x-data="{ exportOpen: false }">
                            <button @click="exportOpen = !exportOpen" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                Export Data
                            </button>
                            <div x-show="exportOpen" @click.away="exportOpen = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-10" role="menu" aria-orientation="vertical">
                                <div class="py-1" role="none">
                                    <a href="{{ route('websites.export', ['website' => $website->id, 'type' => 'logs', 'days' => 30]) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                        <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Last 30 Days Logs (CSV)
                                    </a>
                                    <a href="{{ route('websites.export', ['website' => $website->id, 'type' => 'logs', 'days' => 90]) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                        <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Last 90 Days Logs (CSV)
                                    </a>
                                </div>
                                <div class="py-1" role="none">
                                    <a href="{{ route('websites.export', ['website' => $website->id, 'type' => 'summary', 'days' => 30]) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                        <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        Monthly Summary (CSV)
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                                More
                            </button>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                <div class="py-1" role="none">
                                    <button id="reset-baseline-btn" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left" role="menuitem">
                                        <svg id="baseline-icon" class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <svg id="baseline-spinner" class="mr-3 h-5 w-5 text-gray-400 spinner hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span id="baseline-text">{{ isset($baselineScreenshot) ? 'Reset Baseline' : 'Set Baseline' }}</span>
                                    </button>
                                </div>
                                <div class="py-1" role="none">
                                    <form action="{{ route('websites.destroy', $website) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this website?')" class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left" role="menuitem">
                                            <svg class="mr-3 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete Website
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Status Overview Cards -->
            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Debug forms -->
                <div class="hidden">
                    <form id="check-now-form" action="{{ route('websites.check-now', $website->id) }}" method="POST">
                        @csrf
                    </form>
                    
                    <form id="reset-baseline-form" action="{{ route('websites.reset-baseline', $website->id) }}" method="POST">
                        @csrf
                    </form>
                </div>
                <!-- Current Status -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md p-3 
                                {{ $website->last_status === 'up' ? 'bg-green-100 dark:bg-green-900' : 
                                  ($website->last_status === 'down' ? 'bg-red-100 dark:bg-red-900' : 
                                  ($website->last_status === 'changed' ? 'bg-yellow-100 dark:bg-yellow-900' : 
                                  'bg-gray-100 dark:bg-gray-700')) }}">
                                @if($website->last_status === 'up')
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @elseif($website->last_status === 'down')
                                    <svg class="h-6 w-6 text-red-600 dark:text-red-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @elseif($website->last_status === 'changed')
                                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                        Current Status
                                    </dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ ucfirst($website->last_status ?? 'Unknown') }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            Last checked: {{ $website->last_checked_at ? $website->last_checked_at->diffForHumans() : 'Never' }}
                        </div>
                    </div>
                </div>

                <!-- HTTP Status -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                        HTTP Status
                                    </dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ $lastLog->status_code ?? 'N/A' }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            Response Time: {{ $lastLog->response_time ?? 'N/A' }} ms
                        </div>
                    </div>
                </div>

                <!-- Uptime -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                        30-Day Uptime
                                    </dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ $uptime ?? '100' }}%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            Checks: {{ $totalChecks ?? 0 }}
                        </div>
                    </div>
                </div>

                <!-- Check Interval -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                        Check Interval
                                    </dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ $website->check_interval }} mins
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            Last baseline: {{ $website->last_baseline_at ? $website->last_baseline_at->diffForHumans() : ($baselineScreenshotDate ? $baselineScreenshotDate->diffForHumans() : 'Never') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Website Details -->
            <div class="mt-6 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Website Information</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Details and monitoring settings.</p>
                    </div>
                    <a href="{{ route('websites.edit', $website) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                        Edit Details
                    </a>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">URL</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                <a href="{{ $website->url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline flex items-center">
                                    {{ $website->url }}
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Check interval</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $website->check_interval }} minutes</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Monitoring options</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                <div class="flex flex-wrap gap-3">
                                    <span class="px-3 py-1 rounded-full {{ ($website->monitoring_options['check_ssl'] ?? false) ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} flex items-center">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        SSL Certificate Monitoring
                                    </span>
                                    <span class="px-3 py-1 rounded-full {{ ($website->monitoring_options['check_content'] ?? false) ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} flex items-center">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Content Change Detection
                                    </span>
                                    <span class="px-3 py-1 rounded-full {{ ($website->monitoring_options['take_screenshots'] ?? false) ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} flex items-center">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Screenshot Comparison
                                    </span>
                                </div>
                            </dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tags</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($website->tags as $tag)
                                        <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 flex items-center">
                                            <svg class="h-2 w-2 mr-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="6" />
                                            </svg>
                                            {{ $tag->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-500 dark:text-gray-400">No tags</span>
                                    @endforelse
                                </div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Notification Settings -->
            <div class="mt-8 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                            <svg class="mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            Website Notifications
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Configure notifications for this specific website.
                        </p>
                    </div>
                    <button id="saveNotificationSettings" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" form="notificationForm" type="submit">
                        Save Settings
                    </button>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-6">
                    <form id="notificationForm" action="{{ route('websites.notifications.update', $website) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <!-- Downtime Alerts -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                                    <svg class="mr-2 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Downtime Alerts
                                </h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-900 dark:text-white mr-3">Email notification</span>
                                        <div class="switch">
                                            <input id="downtime_email" name="notification_settings[downtime][email]" type="checkbox" value="1" {{ (is_array($website->notification_settings) && isset($website->notification_settings['downtime']['email']) && $website->notification_settings['downtime']['email']) ? 'checked' : '' }} />
                                            <label for="downtime_email"></label>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-900 dark:text-white mr-3">Push notification</span>
                                        <div class="switch">
                                            <input id="downtime_push" name="notification_settings[downtime][push]" type="checkbox" value="1" {{ (is_array($website->notification_settings) && isset($website->notification_settings['downtime']['push']) && $website->notification_settings['downtime']['push']) ? 'checked' : '' }} />
                                            <label for="downtime_push"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Change Alerts -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                                    <svg class="mr-2 h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Content Change Alerts
                                </h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-900 dark:text-white mr-3">Email notification</span>
                                        <div class="switch">
                                            <input id="content_change_email" name="notification_settings[content_change][email]" type="checkbox" value="1" {{ (is_array($website->notification_settings) && isset($website->notification_settings['content_change']['email']) && $website->notification_settings['content_change']['email']) ? 'checked' : '' }} />
                                            <label for="content_change_email"></label>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-900 dark:text-white mr-3">Push notification</span>
                                        <div class="switch">
                                            <input id="content_change_push" name="notification_settings[content_change][push]" type="checkbox" value="1" {{ (is_array($website->notification_settings) && isset($website->notification_settings['content_change']['push']) && $website->notification_settings['content_change']['push']) ? 'checked' : '' }} />
                                            <label for="content_change_push"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SSL Certificate Alerts -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                                    <svg class="mr-2 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    SSL Certificate Alerts
                                </h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-900 dark:text-white mr-3">Email when expiring soon</span>
                                        <div class="switch">
                                            <input id="ssl_expiring_email" name="notification_settings[ssl][expiring_email]" type="checkbox" value="1" {{ (is_array($website->notification_settings) && isset($website->notification_settings['ssl']['expiring_email']) && $website->notification_settings['ssl']['expiring_email']) ? 'checked' : '' }} />
                                            <label for="ssl_expiring_email"></label>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-900 dark:text-white mr-3">Email on invalid certificate</span>
                                        <div class="switch">
                                            <input id="ssl_invalid_email" name="notification_settings[ssl][invalid_email]" type="checkbox" value="1" {{ (is_array($website->notification_settings) && isset($website->notification_settings['ssl']['invalid_email']) && $website->notification_settings['ssl']['invalid_email']) ? 'checked' : '' }} />
                                            <label for="ssl_invalid_email"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Performance Alerts -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                                    <svg class="mr-2 h-5 w-5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Performance Alerts
                                </h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-900 dark:text-white mr-3">High response time alerts</span>
                                        <div class="switch">
                                            <input id="performance_email" name="notification_settings[performance][email]" type="checkbox" value="1" {{ (is_array($website->notification_settings) && isset($website->notification_settings['performance']['email']) && $website->notification_settings['performance']['email']) ? 'checked' : '' }} />
                                            <label for="performance_email"></label>
                                        </div>
                                    </div>
                                    <div class="flex items-center mt-4">
                                        <label for="responseTimeThreshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Threshold:</label>
                                        <input type="number" name="notification_settings[performance][threshold]" id="responseTimeThreshold" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md w-24" placeholder="1000" value="{{ is_array($website->notification_settings) && isset($website->notification_settings['performance']['threshold']) ? (is_array($website->notification_settings['performance']['threshold']) ? $website->notification_settings['performance']['threshold'][0] : $website->notification_settings['performance']['threshold']) : 1000 }}">
                                        <span class="ml-1 text-sm text-gray-700 dark:text-gray-300">ms</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Advanced Settings -->
            <div class="mt-8 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                        Advanced Settings
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                        Configure advanced settings for this website.
                    </p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-6">
                    <form id="advancedSettingsForm" action="{{ route('websites.advanced.update', $website) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <!-- IP Override -->
                        <div class="mb-6">
                            <label for="ip_override" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Direct IP Override (bypasses CDN)
                            </label>
                            <div class="flex items-center">
                                <input type="text" name="ip_override" id="ip_override" 
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md" 
                                    placeholder="192.168.1.1" 
                                    value="{{ $website->ip_override ?? '' }}">
                                <div class="ml-4">
                                    <div class="switch">
                                        <input id="use_ip_override" name="use_ip_override" type="checkbox" value="1" {{ ($website->use_ip_override ?? false) ? 'checked' : '' }} />
                                        <label for="use_ip_override"></label>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                If enabled, monitoring requests will be sent to this IP address instead of resolving the domain name
                            </p>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Screenshot Comparison -->
            <div class="mt-8 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                            <svg class="mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Screenshot Comparison
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Compare the current screenshot with the baseline.
                        </p>
                    </div>
                    <div>
                        <form action="{{ route('websites.reset-baseline', $website->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ isset($baselineScreenshot) ? 'Reset Baseline' : 'Set Baseline' }}
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-6">
                    @if(isset($baselineScreenshot) && isset($latestScreenshot))
                        <div class="flex flex-col lg:flex-row space-y-6 lg:space-y-0 lg:space-x-6">
                            <!-- Baseline Screenshot -->
                            <div class="flex-1">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Baseline Screenshot</h4>
                                <div class="relative border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                                    <img src="{{ $baselineScreenshot }}" alt="Baseline Screenshot" class="w-full h-auto">
                                    <div class="absolute top-2 right-2">
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded">
                                            {{ isset($baselineScreenshotDate) ? $baselineScreenshotDate->diffForHumans() : 'Baseline' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Latest Screenshot -->
                            <div class="flex-1">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Latest Screenshot</h4>
                                <div class="relative border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                                    <img src="{{ $latestScreenshot }}" alt="Latest Screenshot" class="w-full h-auto">
                                    <div class="absolute top-2 right-2">
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded">
                                            {{ isset($latestScreenshotDate) ? $latestScreenshotDate->diffForHumans() : 'Latest' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Visual Difference -->
                        <div class="mt-8">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white">Visual Difference</h4>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-700 dark:text-gray-300 mr-2">{{ $visualChangePct ?? 0 }}% Change</span>
                                    <div class="w-64 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                        @php
                                            $widthPercentage = min(($visualChangePct ?? 0), 100);
                                        @endphp
                                        
                                        <!-- Progress bar with dynamic width -->
                                        @if(($visualChangePct ?? 0) < 5)
                                            <!-- Low change - Green -->
                                            <div class="h-2.5 rounded-full bg-green-500" style="width: {{ $widthPercentage }}%"></div>
                                        @elseif(($visualChangePct ?? 0) < 15)
                                            <!-- Moderate change - Yellow -->
                                            <div class="h-2.5 rounded-full bg-yellow-500" style="width: {{ $widthPercentage }}%"></div>
                                        @else
                                            <!-- Significant change - Red -->
                                            <div class="h-2.5 rounded-full bg-red-500" style="width: {{ $widthPercentage }}%"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Comparison Controls -->
                            <div class="flex flex-wrap gap-4 mb-6">
                                <button id="sideBySideModeBtn" class="px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 active">
                                    Side-by-Side
                                </button>
                                <button id="overlayModeBtn" class="px-3 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 text-sm font-medium rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Overlay
                                </button>
                                <button id="differenceModeBtn" class="px-3 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 text-sm font-medium rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Highlight Differences
                                </button>
                            </div>
                            
                            <!-- Diff View Container -->
                            <div id="diffViewContainer" class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden relative">
                                <div id="overlayView" class="hidden relative">
                                    <img src="{{ $baselineScreenshot }}" alt="Baseline Screenshot" class="w-full h-auto opacity-50">
                                    <img src="{{ $latestScreenshot }}" alt="Latest Screenshot" class="w-full h-auto absolute top-0 left-0 opacity-50">
                                    
                                    <div class="absolute bottom-4 left-0 right-0 flex justify-center">
                                        <input type="range" min="0" max="100" value="50" class="slider w-64" id="overlaySlider">
                                    </div>
                                </div>
                                
                                <div id="differenceView" class="hidden">
                                    <!-- This would be dynamically filled with a diff image via JavaScript -->
                                    <div class="flex items-center justify-center py-16 bg-gray-50 dark:bg-gray-900">
                                        <p class="text-gray-500 dark:text-gray-400">
                                            Difference visualization will be generated when this mode is selected
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-base font-medium text-gray-900 dark:text-gray-100">No screenshots available</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Wait for the next check cycle or trigger a manual check.</p>
                            <div class="mt-6">
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Check Now
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Recent Monitoring Logs -->
            <div class="mt-8 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                            <svg class="mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Recent Monitoring Logs
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Last 7 days monitoring activity for this website.
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <select id="timeRangeFilter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            <option value="7">Last 7 days</option>
                            <option value="14">Last 14 days</option>
                            <option value="30">Last 30 days</option>
                            <option value="90">Last 90 days</option>
                        </select>
                        <button class="px-3 py-1 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            Export
                        </button>
                    </div>
                </div>
                
                <!-- Logs Timeline -->
                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <!-- Response Time Chart -->
                    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Response Time Trend</h4>
                        <div class="h-64">
                            <canvas id="responseTimeChart"></canvas>
                        </div>
                    </div>
                
                    <!-- Logs Table with Status Timeline -->
                    <div class="mt-4 overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-indigo-500">
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Response Time</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">HTTP Code</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse($monitoringLogs ?? [] as $log)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-indigo-500">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $log->created_at->format('M j, Y g:i:s A') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @switch($log->status)
                                                        @case('up')
                                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                Up
                                                            </span>
                                                            @break
                                                        @case('down')
                                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                                Down
                                                            </span>
                                                            @break
                                                        @case('changed')
                                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                                Changed
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                                Unknown
                                                            </span>
                                                    @endswitch
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    @if($log->response_time)
                                                        <div class="flex items-center">
                                                            <span class="{{ $log->response_time > 1000 ? 'text-amber-600 dark:text-amber-400' : 'text-green-600 dark:text-green-400' }}">
                                                                {{ $log->response_time }} ms
                                                            </span>
                                                            @if($log->response_time > 1000)
                                                                <svg class="ml-1 h-4 w-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    <span class="{{ $log->http_code >= 400 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-300' }}">
                                                        {{ $log->http_code ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" 
                                                            onclick="showLogDetails('{{ $log->id }}')">
                                                        View Details
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                    No monitoring logs found. Monitoring will begin on the next check interval.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bulk Actions -->
                    <div class="mt-4 flex">
                        <div class="relative inline-block text-left">
                            <button id="bulkActionBtn" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                                Bulk Actions
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <div id="bulkActionDropdown" class="hidden origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="bulkActionBtn">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Delete Selected</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Mark as Reviewed</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Export Selected</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3 flex items-center justify-between sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                        <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">{{ $monitoringLogs->count() }}</span> results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="#" aria-current="page" class="z-10 bg-indigo-50 dark:bg-indigo-900/30 border-indigo-500 dark:border-indigo-500 text-indigo-600 dark:text-indigo-400 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    1
                                </a>
                                <a href="#" class="bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    2
                                </a>
                                <a href="#" class="bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    3
                                </a>
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-400">
                                    ...
                                </span>
                                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Screenshots -->
            @if(isset($screenshots) && count($screenshots) > 0)
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Screenshots</h2>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($screenshots as $screenshot)
                    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg">
                        <div class="p-2">
                            <img src="{{ url('storage/' . $screenshot->path) }}" alt="Screenshot from {{ $screenshot->created_at->format('M j, Y g:i A') }}" class="w-full h-auto rounded">
                        </div>
                        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-700 text-sm text-gray-500 dark:text-gray-400">
                            {{ $screenshot->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize the response time chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('responseTimeChart');
        
        if(ctx) {
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'],
                    datasets: [{
                        label: 'Response Time (ms)',
                        data: [320, 420, 380, 470, 590, 550, 480, 520],
                        borderColor: '#6366F1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 12
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(160, 174, 192, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + ' ms';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Toggle bulk action dropdown
        const bulkActionBtn = document.getElementById('bulkActionBtn');
        const bulkActionDropdown = document.getElementById('bulkActionDropdown');
        
        if(bulkActionBtn && bulkActionDropdown) {
            bulkActionBtn.addEventListener('click', function() {
                bulkActionDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!bulkActionBtn.contains(event.target) && !bulkActionDropdown.contains(event.target)) {
                    bulkActionDropdown.classList.add('hidden');
                }
            });
        }
        
        // Toggle notification switches
        const toggleSwitches = document.querySelectorAll('button[type="button"][class*="bg-"]');
        toggleSwitches.forEach(function(switchElement) {
            if(switchElement.childElementCount > 0) { // Make sure it's a toggle switch
                switchElement.addEventListener('click', function() {
                    const thumbElement = switchElement.querySelector('span');
                    
                    if(switchElement.classList.contains('bg-gray-200') || switchElement.classList.contains('bg-gray-600')) {
                        switchElement.classList.remove('bg-gray-200', 'bg-gray-600');
                        switchElement.classList.add('bg-indigo-600');
                        thumbElement.classList.remove('translate-x-0');
                        thumbElement.classList.add('translate-x-5');
                    } else {
                        switchElement.classList.remove('bg-indigo-600');
                        switchElement.classList.add('bg-gray-200', 'dark:bg-gray-600');
                        thumbElement.classList.remove('translate-x-5');
                        thumbElement.classList.add('translate-x-0');
                    }
                });
            }
        });
    });
    
    // Function to show log details
    function showLogDetails(logId) {
        // Check if modal already exists, remove if it does
        const existingModal = document.getElementById('log-details-modal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Create modal container
        const modal = document.createElement('div');
        modal.id = 'log-details-modal';
        modal.className = 'fixed inset-0 z-50 overflow-y-auto';
        modal.innerHTML = `
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Log Details #${logId}
                        </h3>
                        <button type="button" onclick="closeLogDetailsModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex justify-center">
                            <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="mt-2 text-center text-gray-500 dark:text-gray-400">Loading log details...</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex flex-row-reverse">
                        <button type="button" onclick="closeLogDetailsModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal to body
        document.body.appendChild(modal);
        
        // Simulate loading data from server (in production, you'd fetch actual data)
        setTimeout(() => {
            // Generate mock details based on logId
            const modal = document.getElementById('log-details-modal');
            if (!modal) return;
            
            const statusOptions = ['up', 'down', 'changed'];
            const httpCodes = [200, 301, 302, 404, 500, 503];
            const mockStatus = statusOptions[Math.floor(Math.random() * statusOptions.length)];
            const mockHttpCode = httpCodes[Math.floor(Math.random() * httpCodes.length)];
            const mockResponseTime = Math.floor(Math.random() * 2000) + 100;
            
            // Get the modal content area
            const contentArea = modal.querySelector('.px-6.py-4');
            if (contentArea) {
                // Replace loading spinner with content
                contentArea.innerHTML = `
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Time</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">${new Date().toLocaleString()}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h4>
                            <p class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    ${mockStatus === 'up' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                      mockStatus === 'down' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'}">
                                    ${mockStatus.charAt(0).toUpperCase() + mockStatus.slice(1)}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">HTTP Status Code</h4>
                            <p class="mt-1 text-sm ${mockHttpCode >= 400 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white'}">${mockHttpCode}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Response Time</h4>
                            <p class="mt-1 text-sm ${mockResponseTime > 1000 ? 'text-amber-600 dark:text-amber-400' : 'text-green-600 dark:text-green-400'}">${mockResponseTime} ms</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Additional Data</h4>
                            <div class="mt-1 bg-gray-100 dark:bg-gray-900 p-3 rounded-md">
                                <pre class="text-xs text-gray-800 dark:text-gray-300 overflow-auto">{
  "manual_check": true,
  "timestamp": ${Math.floor(Date.now() / 1000)},
  "url": "${window.location.origin}/website/${logId}",
  "headers": {
    "server": "Apache",
    "content-type": "text/html; charset=UTF-8",
    "cache-control": "max-age=3600"
  },
  "ssl_info": {
    "valid": true,
    "expires_in_days": 90
  }
}</pre>
                            </div>
                        </div>
                    </div>
                `;
            }
        }, 1000);
    }
    
    // Function to close the log details modal
    function closeLogDetailsModal() {
        const modal = document.getElementById('log-details-modal');
        if (modal) {
            modal.remove();
        }
    }
</script>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Helper function for notifications
        function showNotification(title, message, type) {
            const notificationContainer = document.createElement('div');
            notificationContainer.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} text-white transform transition-all duration-500 opacity-0 translate-y-4`;
            
            notificationContainer.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${type === 'success' 
                          ? '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                          : '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'}
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium">${title}</h3>
                        <div class="mt-1 text-sm opacity-90">${message}</div>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" class="inline-flex rounded-md p-1.5 text-white hover:bg-white hover:bg-opacity-20 focus:outline-none" onclick="this.parentNode.parentNode.parentNode.remove()">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notificationContainer);
            
            // Animate in
            setTimeout(() => {
                notificationContainer.classList.remove('opacity-0', 'translate-y-4');
            }, 10);
            
            // Automatically remove after 5 seconds
            setTimeout(() => {
                notificationContainer.classList.add('opacity-0', 'translate-y-4');
                setTimeout(() => {
                    notificationContainer.remove();
                }, 500);
            }, 5000);
        }

        // Create and append CSRF form elements
        function createForm(action, method) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            // For non-POST methods
            if (method !== 'POST') {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = method;
                form.appendChild(methodInput);
            }
            
            document.body.appendChild(form);
            return form;
        }
        
        // Check Now Button
        const checkNowBtn = document.getElementById('checkNowBtn');
        if (checkNowBtn) {
            checkNowBtn.addEventListener('click', function() {
                // Show loading state
                checkNowBtn.disabled = true;
                const originalHTML = checkNowBtn.innerHTML;
                checkNowBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Checking...';
                
                try {
                    // Create and submit form
                    const form = createForm('{{ route("websites.check-now", $website->id) }}', 'POST');
                    form.addEventListener('submit', function() {
                        // This is just for visual feedback - the page will reload anyway
                        showNotification('Success', 'Website check initiated', 'success');
                    });
                    form.submit();
                } catch (error) {
                    // Reset button state and show error
                    checkNowBtn.disabled = false;
                    checkNowBtn.innerHTML = originalHTML;
                    showNotification('Error', 'Failed to check website: ' + error.message, 'error');
                    console.error('Error:', error);
                }
            });
        }
        
        // Reset Baseline Button
        const resetBaselineBtn = document.getElementById('resetBaselineBtn');
        if (resetBaselineBtn) {
            // Get baseline status from a data attribute
            const hasBaseline = resetBaselineBtn.dataset.hasBaseline === 'true';
            
            resetBaselineBtn.addEventListener('click', function() {
                // Show loading state
                resetBaselineBtn.disabled = true;
                const originalHTML = resetBaselineBtn.innerHTML;
                const loadingText = hasBaseline ? 'Resetting...' : 'Setting...';
                resetBaselineBtn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${loadingText}`;
                
                try {
                    // Create and submit form
                    const form = createForm('{{ route("websites.reset-baseline", $website->id) }}', 'POST');
                    form.addEventListener('submit', function() {
                        // This is just for visual feedback - the page will reload anyway
                        const message = hasBaseline ? 'Baseline screenshot has been reset' : 'Baseline screenshot has been set';
                        showNotification('Success', message, 'success');
                    });
                    form.submit();
                } catch (error) {
                    // Reset button state and show error
                    resetBaselineBtn.disabled = false;
                    resetBaselineBtn.innerHTML = originalHTML;
                    const errorMessage = hasBaseline ? 'Failed to reset baseline' : 'Failed to set baseline';
                    showNotification('Error', errorMessage + ': ' + error.message, 'error');
                    console.error('Error:', error);
                }
            });
        }
    });
</script>
@endpush

<!-- Function to compare latest and base screenshots -->
<div class="px-4 py-5 sm:p-6">
    <script>
        // Handle Check Now button
        document.getElementById('check-now-btn').addEventListener('click', function() {
            // Show spinner, hide regular icon
            document.getElementById('check-icon').classList.add('hidden');
            document.getElementById('check-spinner').classList.remove('hidden');
            document.getElementById('check-text').textContent = 'Checking...';
            
            // Disable button while processing
            this.disabled = true;
            
            // Submit the hidden form
            document.getElementById('check-now-form').submit();
        });
        
        // Handle Reset Baseline button
        document.getElementById('reset-baseline-btn').addEventListener('click', function() {
            // Show spinner, hide regular icon
            document.getElementById('baseline-icon').classList.add('hidden');
            document.getElementById('baseline-spinner').classList.remove('hidden');
            document.getElementById('baseline-text').textContent = 'Processing...';
            
            // Disable button while processing
            this.disabled = true;
            
            // Submit the hidden form
            document.getElementById('reset-baseline-form').submit();
        });
    </script>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle toggle for browser push notifications
        const pushToggles = document.querySelectorAll('input[name*="[push]"]');
        
        // Function to show notification
        function showNotification(title, body, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-xs transform transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${type === 'success' ? `
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        ` : type === 'error' ? `
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        ` : `
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        `}
                    </div>
                    <div class="ml-3">
                        <h3 class="font-medium">${title}</h3>
                        <div class="mt-1 text-sm opacity-90">${body}</div>
                    </div>
                </div>
                <button class="absolute top-2 right-2 text-white hover:text-gray-200" onclick="this.parentNode.remove();">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            document.body.appendChild(toast);
            
            // Remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        }

        // Handle browser notifications permission
        pushToggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                if (this.checked) {
                    // Check if the browser supports notifications
                    if (!("Notification" in window)) {
                        showNotification("Error", "This browser does not support push notifications.", "error");
                        this.checked = false;
                        return;
                    }
                    
                    // Check if permission already granted
                    if (Notification.permission === "granted") {
                        showNotification("Success", "Push notifications are enabled!", "success");
                        return;
                    }
                    
                    // Request permission
                    Notification.requestPermission().then(permission => {
                        if (permission === "granted") {
                            showNotification("Success", "Push notifications are now enabled!", "success");
                            // Send test notification
                            setTimeout(() => {
                                const notification = new Notification("Visual Sentinel", {
                                    body: "You will be notified when this website has issues or changes.",
                                    icon: "/favicon.ico"
                                });
                            }, 2000);
                        } else {
                            showNotification("Error", "Push notification permission denied.", "error");
                            this.checked = false;
                        }
                    });
                }
            });
        });

        // Handle Save Notification Settings button
        const saveNotificationSettingsBtn = document.getElementById('saveNotificationSettings');
        if (saveNotificationSettingsBtn) {
            saveNotificationSettingsBtn.addEventListener('click', function() {
                // Show loading state
                saveNotificationSettingsBtn.disabled = true;
                const originalText = saveNotificationSettingsBtn.textContent;
                saveNotificationSettingsBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                `;
                
                try {
                    // Submit the notification form
                    document.getElementById('notificationForm').submit();
                } catch (error) {
                    // Reset button state and show error
                    saveNotificationSettingsBtn.disabled = false;
                    saveNotificationSettingsBtn.textContent = originalText;
                    showNotification('Error', 'Failed to save notification settings: ' + error.message, 'error');
                    console.error('Error:', error);
                }
            });
        }
    });
</script>
@endpush