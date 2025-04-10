<?php

namespace App\Console;

use App\Models\Website;
use App\Models\MonitoringLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run websites:check command every 5 minutes to check all websites
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
                    Log::info("Checking website due for monitoring: {$website->name} ({$website->url})");
                    // Run the checkWebsites command for this specific website
                    Artisan::call('websites:check', ['id' => $website->id]);
                } catch (\Exception $e) {
                    Log::error("Error scheduling check for website {$website->name}: " . $e->getMessage());
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