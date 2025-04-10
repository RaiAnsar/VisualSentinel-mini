<?php

namespace App\Console\Commands;

use App\Models\MonitoringLog;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Exception;

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
            
            // Store check start time to calculate response time
            $startTime = microtime(true);
            
            // Prepare HTTP client with some defaults
            $httpClient = Http::timeout(30)
                ->withUserAgent('Visual Sentinel Monitoring/1.0')
                ->withHeaders([
                    'Accept' => 'text/html,application/xhtml+xml,application/xml'
                ]);
            
            // Add custom IP if needed
            if ($website->use_ip_override && !empty($website->ip_override)) {
                $urlParts = parse_url($website->url);
                $httpClient = $httpClient->withOptions([
                    'curl' => [
                        CURLOPT_RESOLVE => [
                            $urlParts['host'].':'.($urlParts['port'] ?? 80).':'.$website->ip_override,
                            $urlParts['host'].':'.($urlParts['port'] ?? 443).':'.$website->ip_override,
                        ],
                    ],
                ]);
            }
            
            // Check SSL certificate if enabled
            $sslDetails = null;
            if (isset($website->monitoring_options['check_ssl']) && $website->monitoring_options['check_ssl']) {
                $sslDetails = $this->checkSSLCertificate($website->url);
            }
            
            // Make the HTTP request
            $response = $httpClient->get($website->url);
            
            // Calculate response time in milliseconds
            $responseTime = round((microtime(true) - $startTime) * 1000);
            
            // Get status code
            $statusCode = $response->status();
            
            // Determine status based on status code
            if ($statusCode >= 200 && $statusCode < 300) {
                $status = 'up';
            } elseif ($statusCode >= 300 && $statusCode < 400) {
                // Redirects are generally OK
                $status = 'up';
                // Add redirect information to log details
                $redirectUrl = $response->header('Location');
                $log->details = array_merge($log->details ?? [], [
                    'redirect' => [
                        'location' => $redirectUrl,
                        'type' => $this->getRedirectType($statusCode)
                    ]
                ]);
            } elseif ($statusCode >= 400 && $statusCode < 500) {
                // Client errors
                $status = 'down';
                $log->details = array_merge($log->details ?? [], [
                    'error_type' => 'client_error',
                    'error_description' => $this->getClientErrorDescription($statusCode)
                ]);
            } else {
                // Server errors or other status codes
                $status = 'down';
                $log->details = array_merge($log->details ?? [], [
                    'error_type' => 'server_error',
                    'error_description' => $this->getServerErrorDescription($statusCode)
                ]);
            }
            
            // Check content changes if enabled
            $contentChanged = false;
            if (isset($website->monitoring_options['check_content']) && $website->monitoring_options['check_content']) {
                $contentChanged = $this->checkContentChanges($website, $response->body());
                if ($contentChanged && $status === 'up') {
                    $status = 'changed';
                }
            }
            
            // Set log values
            $log->status_code = $statusCode;
            $log->status = $status;
            $log->response_time = $responseTime;
            $log->details = [
                'checked_at' => now()->toIso8601String(),
                'ip_used' => $website->use_ip_override ? $website->ip_override : null,
                'headers' => [
                    'response' => $response->headers(),
                    'request' => [
                        'User-Agent' => 'Visual Sentinel Monitoring/1.0',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml'
                    ]
                ],
                'ssl_details' => $sslDetails,
                'content_changed' => $contentChanged
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
                $this->takeScreenshot($website);
            }
            
        } catch (Exception $e) {
            // Log the error
            Log::error("Error checking website {$website->name}: " . $e->getMessage());
            
            // Create an error log
            $log = new MonitoringLog();
            $log->website_id = $website->id;
            $log->status = 'down';
            $log->status_code = 0;
            $log->response_time = 0;
            $log->details = [
                'checked_at' => now()->toIso8601String(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
            $log->save();
            
            // Update website status
            $website->last_checked_at = now();
            $website->last_status = 'down';
            $website->last_status_code = 0;
            $website->save();
            
            if ($this->argument('id')) {
                $this->error("Error checking website: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Check SSL certificate details
     */
    private function checkSSLCertificate($url)
    {
        try {
            $urlParts = parse_url($url);
            $host = $urlParts['host'];
            $port = isset($urlParts['port']) ? $urlParts['port'] : 443;
            
            $context = stream_context_create([
                'ssl' => [
                    'capture_peer_cert' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);
            
            $client = stream_socket_client(
                "ssl://{$host}:{$port}", 
                $errno, 
                $errstr, 
                30, 
                STREAM_CLIENT_CONNECT, 
                $context
            );
            
            if (!$client) {
                return [
                    'valid' => false,
                    'error' => "Failed to connect to SSL: $errstr ($errno)"
                ];
            }
            
            $params = stream_context_get_params($client);
            $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
            
            // Calculate days until expiry
            $validTo = $cert['validTo_time_t'];
            $daysRemaining = floor(($validTo - time()) / 86400);
            
            return [
                'valid' => true,
                'issuer' => $cert['issuer']['O'] ?? null,
                'subject' => $cert['subject']['CN'] ?? null,
                'valid_from' => date('Y-m-d H:i:s', $cert['validFrom_time_t']),
                'valid_to' => date('Y-m-d H:i:s', $validTo),
                'days_remaining' => $daysRemaining,
                'is_expired' => $daysRemaining < 0,
                'is_warning' => $daysRemaining > 0 && $daysRemaining <= 14
            ];
        } catch (Exception $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Take a screenshot of a website
     */
    private function takeScreenshot(Website $website)
    {
        try {
            // Create a unique filename
            $filename = 'screenshots/' . $website->id . '/' . Str::uuid() . '.png';
            
            // Use Browsershot to take a screenshot
            $screenshot = Browsershot::url($website->url)
                ->windowSize(1280, 1024)
                ->waitUntilNetworkIdle()
                ->screenshot();
            
            // Store the screenshot
            Storage::put('public/' . $filename, $screenshot);
            
            // Create a screenshot record
            $screenshot = new \App\Models\Screenshot();
            $screenshot->website_id = $website->id;
            $screenshot->path = $filename;
            $screenshot->metadata = [
                'taken_at' => now()->toIso8601String(),
                'size' => [
                    'width' => 1280,
                    'height' => 1024
                ]
            ];
            $screenshot->save();
            
            // Check if this is the first screenshot (make it baseline)
            $count = \App\Models\Screenshot::where('website_id', $website->id)->count();
            if ($count === 1) {
                $screenshot->is_baseline = true;
                $screenshot->save();
            } else {
                // Compare with baseline if not the first screenshot
                // Run the comparison command
                $this->info("Running screenshot comparison for website {$website->id}, screenshot {$screenshot->id}");
                \Illuminate\Support\Facades\Artisan::call('screenshots:compare', [
                    'website_id' => $website->id,
                    '--screenshot_id' => $screenshot->id
                ]);
            }
            
            Log::info("Screenshot taken for website: {$website->name}");
            
            return $screenshot;
        } catch (Exception $e) {
            Log::error("Error taking screenshot for {$website->name}: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Check for content changes
     */
    private function checkContentChanges(Website $website, $content)
    {
        // Get the last content hash from the database
        $lastLog = MonitoringLog::where('website_id', $website->id)
            ->whereNotNull('content_hash')
            ->latest()
            ->first();
        
        // Calculate content hash (ignoring dynamic elements)
        $contentHash = md5($this->normalizeContent($content));
        
        // Store the new hash in the website
        $website->content_hash = $contentHash;
        $website->save();
        
        // Update the current log with the content hash
        MonitoringLog::where('website_id', $website->id)
            ->latest()
            ->first()
            ->update(['content_hash' => $contentHash]);
        
        // If there's no previous content hash, we can't determine if it changed
        if (!$lastLog) {
            return false;
        }
        
        // Determine if content changed
        return $lastLog->content_hash !== $contentHash;
    }
    
    /**
     * Normalize content to avoid false positives on dynamic content
     */
    private function normalizeContent($content)
    {
        // Remove common dynamic elements that would cause false positives
        $patterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', // Remove all scripts
            '/<noscript\b[^<]*(?:(?!<\/noscript>)<[^<]*)*<\/noscript>/i', // Remove noscript
            '/\b\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}:\d{2})?\b/', // Remove ISO dates
            '/\b\d{2}:\d{2}:\d{2}\b/', // Remove times
            '/\b\d{1,2}\/\d{1,2}\/\d{2,4}\b/', // Remove dates
        ];
        
        $content = preg_replace($patterns, '', $content);
        
        return $content;
    }
    
    /**
     * Get a description for the redirect type
     */
    private function getRedirectType(int $statusCode): string
    {
        return match($statusCode) {
            301 => 'Permanent Redirect',
            302 => 'Temporary Redirect',
            303 => 'See Other',
            307 => 'Temporary Redirect (POST preserved)',
            308 => 'Permanent Redirect (POST preserved)',
            default => 'Redirect'
        };
    }
    
    /**
     * Get a description for client error status codes
     */
    private function getClientErrorDescription(int $statusCode): string
    {
        return match($statusCode) {
            400 => 'Bad Request - The server cannot process the request due to a client error',
            401 => 'Unauthorized - Authentication required',
            403 => 'Forbidden - Server understood request but refuses to authorize it',
            404 => 'Not Found - The requested resource could not be found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            408 => 'Request Timeout',
            429 => 'Too Many Requests - Rate limit exceeded',
            default => 'Client Error'
        };
    }
    
    /**
     * Get a description for server error status codes
     */
    private function getServerErrorDescription(int $statusCode): string
    {
        return match($statusCode) {
            500 => 'Internal Server Error - The server encountered an unexpected condition',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable - The server is temporarily unavailable',
            504 => 'Gateway Timeout',
            default => 'Server Error'
        };
    }
}
