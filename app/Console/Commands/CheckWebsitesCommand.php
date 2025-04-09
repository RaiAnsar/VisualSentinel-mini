<?php

namespace App\Console\Commands;

use App\Models\MonitoringLog;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckWebsitesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websites:check 
                            {id? : Optional website ID to check a specific website}
                            {--force : Force check regardless of last check time}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check websites immediately, ignoring their check intervals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $websiteId = $this->argument('id');
        
        if ($websiteId) {
            // Check specific website
            $website = Website::find($websiteId);
            
            if (!$website) {
                $this->error("Website with ID {$websiteId} not found.");
                return 1;
            }
            
            $this->info("Checking website: {$website->name} ({$website->url})");
            $this->checkWebsite($website);
            $this->info("Check completed for website: {$website->name}");
        } else {
            // Check all active websites
            $websites = Website::where('is_active', true)->get();
            
            if ($websites->isEmpty()) {
                $this->warn("No active websites found to check.");
                return 0;
            }
            
            $this->info("Found {$websites->count()} active websites to check.");
            
            $progressBar = $this->output->createProgressBar($websites->count());
            $progressBar->start();
            
            foreach ($websites as $website) {
                $this->checkWebsite($website);
                $progressBar->advance();
            }
            
            $progressBar->finish();
            $this->newLine();
            $this->info("All website checks completed.");
        }
        
        return 0;
    }
    
    /**
     * Check a single website
     */
    private function checkWebsite(Website $website)
    {
        try {
            // Create a new monitoring log
            $log = new MonitoringLog();
            $log->website_id = $website->id;
            
            // Generate a random status code (for demo purposes)
            // In a real application, this would actually check the website
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
                // In production, this would dispatch a job to take a screenshot
                Log::info("Screenshot would be taken for: {$website->name}");
            }
            
        } catch (\Exception $e) {
            Log::error("Error checking website {$website->name}: " . $e->getMessage());
            if ($websiteId = $this->argument('id')) {
                $this->error("Error checking website: " . $e->getMessage());
            }
        }
    }
}
