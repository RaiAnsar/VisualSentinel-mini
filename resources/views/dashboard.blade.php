<x-layouts.app>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard</h1>
            
            <!-- Stats overview with larger icons -->
            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Websites -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition duration-300 hover:shadow-lg hover:scale-[1.02] transform">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-xl p-4">
                                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $totalWebsites ?? 0 }}
                                </div>
                                <div class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Total Websites
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('websites.index') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 flex items-center">
                                View all
                                <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Websites -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition duration-300 hover:shadow-lg hover:scale-[1.02] transform">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-4">
                                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $upWebsites ?? 0 }}
                                </div>
                                <div class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Up Websites <span class="text-xs">(of {{ $activeWebsites ?? 0 }} active)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('websites.index') }}?status=up" class="font-medium text-green-600 dark:text-green-400 hover:text-green-500 flex items-center">
                                View active
                                <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Changed Websites -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition duration-300 hover:shadow-lg hover:scale-[1.02] transform">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl p-4">
                                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $changedWebsites ?? 0 }}
                                </div>
                                <div class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Changed Websites
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('websites.index') }}?status=changed" class="font-medium text-amber-600 dark:text-amber-400 hover:text-amber-500 flex items-center">
                                View changes
                                <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Down Websites -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition duration-300 hover:shadow-lg hover:scale-[1.02] transform">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl p-4">
                                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $downWebsites ?? 0 }}
                                </div>
                                <div class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Down Websites
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('websites.index') }}?status=down" class="font-medium text-red-600 dark:text-red-400 hover:text-red-500 flex items-center">
                                View issues
                                <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monitoring Charts -->
            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Status Breakdown Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Status Breakdown</h3>
                        <div class="mt-4 h-64 flex items-center justify-center">
                            <div class="w-full h-full" id="status-chart"></div>
                        </div>
                    </div>
                </div>

                <!-- Response Time Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Average Response Times</h3>
                        <div class="mt-4 h-64 flex items-center justify-center">
                            <div class="w-full h-full" id="response-time-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Websites with issues -->
            @if(isset($websitesWithIssues) && $websitesWithIssues->count() > 0)
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="h-6 w-6 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Websites with Issues
                </h2>
                <div class="mt-4 bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flow-root">
                            <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($websitesWithIssues as $website)
                                <li class="py-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out px-2">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full flex items-center justify-center {{ $website->last_status === 'down' ? 'bg-red-100 dark:bg-red-900' : 'bg-yellow-100 dark:bg-yellow-900' }}">
                                                @if($website->last_status === 'down')
                                                    <svg class="h-6 w-6 text-red-600 dark:text-red-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                @else
                                                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $website->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ $website->url }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $website->last_status === 'down' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                {{ ucfirst($website->last_status) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $website->last_checked_at ? $website->last_checked_at->diffForHumans() : 'Never' }}
                                            </span>
                                        </div>
                                        <div>
                                            <a href="{{ route('websites.show', $website) }}" class="inline-flex items-center shadow-sm px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm leading-5 font-medium rounded-full text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent activity -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="h-6 w-6 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Recent Activity
                </h2>
                <div class="mt-4 bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flow-root">
                            <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($recentLogs ?? [] as $log)
                                <li class="py-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out px-2">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <span class="flex items-center justify-center h-10 w-10 rounded-full {{ $log->status === 'up' ? 'bg-green-100 dark:bg-green-900' : ($log->status === 'down' ? 'bg-red-100 dark:bg-red-900' : 'bg-yellow-100 dark:bg-yellow-900') }}">
                                                @if($log->status === 'up')
                                                <svg class="h-6 w-6 text-green-600 dark:text-green-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                @elseif($log->status === 'down')
                                                <svg class="h-6 w-6 text-red-600 dark:text-red-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                @else
                                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $log->website->name }}
                                            </p>
                                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                <span class="inline-block mr-3">
                                                    {{ ucfirst($log->status) }}
                                                </span>
                                                @if($log->status_code)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                        {{ $log->status_code }}
                                                    </span>
                                                @endif
                                                @if($log->error_message)
                                                    <span class="ml-2 truncate">{{ $log->error_message }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $log->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <div>
                                            <a href="{{ route('websites.show', $log->website) }}" class="inline-flex items-center shadow-sm px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm leading-5 font-medium rounded-full text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="py-6 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="mt-2 text-base text-gray-500 dark:text-gray-400">No monitoring activity yet</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Activity will appear here once your websites are monitored</p>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ApexCharts Library -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Detect dark mode
        const isDarkMode = document.documentElement.classList.contains('dark');
        
        // Status breakdown donut chart
        const statusOptions = {
            series: JSON.parse('[{{ $upWebsites ?? 0 }}, {{ $changedWebsites ?? 0 }}, {{ $downWebsites ?? 0 }}]'),
            labels: ['Up', 'Changed', 'Down'],
            chart: {
                type: 'donut',
                height: '100%',
                fontFamily: 'Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
            },
            colors: ['#10B981', '#F59E0B', '#EF4444'],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                labels: {
                    colors: isDarkMode ? '#D1D5DB' : '#374151',
                },
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '50%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Websites',
                                formatter: function(w) {
                                    const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return total;
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                style: {
                    fontSize: '14px',
                    fontFamily: 'Inter, system-ui, sans-serif',
                    fontWeight: 'medium',
                    colors: [isDarkMode ? '#FFFFFF' : '#000000']
                }
            },
            tooltip: {
                theme: isDarkMode ? 'dark' : 'light',
            },
            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 250
                        }
                    }
                }
            ],
        };

        // Sample data for response time chart (replace with real data from backend)
        const responseTimes = {
            dates: ['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'],
            times: [320, 280, 300, 290, 305, 295, 285, 275]
        };

        const responseTimeOptions = {
            series: [{
                name: 'Avg. Response Time (ms)',
                data: responseTimes.times
            }],
            chart: {
                height: '100%',
                type: 'line',
                toolbar: {
                    show: false
                },
                fontFamily: 'Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
            },
            colors: ['#6366F1'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: responseTimes.dates,
                labels: {
                    style: {
                        colors: isDarkMode ? '#D1D5DB' : '#374151',
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: isDarkMode ? '#D1D5DB' : '#374151',
                    }
                }
            },
            markers: {
                size: 4,
                colors: ["#6366F1"],
                strokeColors: "#fff",
                strokeWidth: 2,
                hover: {
                    size: 7,
                }
            },
            grid: {
                borderColor: isDarkMode ? '#374151' : '#E5E7EB',
            },
            tooltip: {
                theme: isDarkMode ? 'dark' : 'light',
            }
        };

        if (document.getElementById('status-chart')) {
            const statusChart = new ApexCharts(document.getElementById('status-chart'), statusOptions);
            statusChart.render();
        }

        if (document.getElementById('response-time-chart')) {
            const responseTimeChart = new ApexCharts(document.getElementById('response-time-chart'), responseTimeOptions);
            responseTimeChart.render();
        }
    });
    </script>
</x-layouts.app>
