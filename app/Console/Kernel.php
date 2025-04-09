<?php

namespace App\Console;

use App\Models\Website;
use App\Models\MonitoringLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run websites:check command every 5 minutes with force option
        $schedule->command('websites:check --force')->everyFiveMinutes();

        // Check websites that are due for monitoring every minute
        $schedule->call(function () {
            $now = now();
            $websites = Website::where('is_active', true)
                ->where(function ($query) use ($now) {
                    // First check: websites that have never been checked
                    $query->whereNull('last_checked_at')
                        // Second check: websites that are due according to their interval
                        ->orWhere(function ($q) use ($now) {
                            $q->whereRaw('TIMESTAMPDIFF(MINUTE, last_checked_at, ?) >= check_interval', [$now]);
                        });
                })
                ->get();

            foreach ($websites as $website) {
                try {
                    Log::info("Checking website: {$website->name} ({$website->url})");
                    
                    // Create a new monitoring log
                    $log = new MonitoringLog();
                    $log->website_id = $website->id;
                    
                    // TODO: Replace this with actual HTTP request in production
                    // For now, using demo/mock data
                    $statusCodes = [200, 200, 200, 201, 204, 301, 302, 304, 400, 401, 403, 404, 500, 503];
                    $statusCode = $statusCodes[array_rand($statusCodes)];
                    
                    // 80% chance of site being up
                    $rand = mt_rand(1, 100);
                    $status = ($rand <= 80) ? 'up' : 'down';
                    
                    // Response time between 50ms and 1500ms
                    $responseTime = mt_rand(50, 1500);
                    
                    // Set log values
                    $log->status_code = $statusCode;
                    $log->status = $status;
                    $log->response_time = $responseTime;
                    $log->details = [
                        'checked_at' => now()->toIso8601String(),
                        'ip_used' => $website->use_ip_override ? $website->ip_override : '127.0.0.1',
                        'headers' => [
                            'User-Agent' => 'Visual Sentinel Monitoring/1.0',
                            'Accept' => 'text/html,application/xhtml+xml,application/xml'
                        ]
                    ];
                    
                    // Save the log
                    $log->save();
                    
                    // Update the website with the latest status
                    $website->last_checked_at = now();
                    $website->last_status = $status;
                    $website->last_status_code = $statusCode;
                    $website->last_response_time = $responseTime;
                    $website->save();
                    
                    // Handle screenshot if enabled
                    if (isset($website->monitoring_options['take_screenshots']) && $website->monitoring_options['take_screenshots']) {
                        // Schedule a job to take the screenshot
                        // TODO: Replace with actual screenshot job dispatch in production
                        Log::info("Screenshot would be taken for: {$website->name}");
                    }
                    
                    Log::info("Website check completed: {$website->name} - Status: {$status}");
                } catch (\Exception $e) {
                    Log::error("Error checking website {$website->name}: " . $e->getMessage());
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 