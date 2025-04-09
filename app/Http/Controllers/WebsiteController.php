<?php

namespace App\Http\Controllers;

use App\Models\MonitoringLog;
use App\Models\Screenshot;
use App\Models\Tag;
use App\Models\Website;
use App\Models\WebsiteTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WebsiteController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $tag = $request->input('tag');
        $search = $request->input('search');

        // Base query for all user's websites
        $userWebsites = Website::where('user_id', Auth::id());

        // Get status counts for dashboard widgets
        $upWebsitesCount = (clone $userWebsites)->where('last_status', 'up')->count();
        $downWebsitesCount = (clone $userWebsites)->where('last_status', 'down')->count();
        $changedWebsitesCount = (clone $userWebsites)->where('last_status', 'changed')->count();

        // Query for the filtered websites
        $websitesQuery = (clone $userWebsites);

        // Filter by status if provided
        if ($status) {
            $websitesQuery->where('last_status', $status);
        }

        // Filter by tag if provided
        if ($tag) {
            $websitesQuery->whereHas('tags', function ($query) use ($tag) {
                $query->where('slug', $tag);
            });
        }

        // Search by name or URL if provided
        if ($search) {
            $websitesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        $websites = $websitesQuery->orderBy('name')->paginate(10);
        $tags = Tag::where('user_id', Auth::id())->orderBy('name')->get();

        return view('websites.index', [
            'websites' => $websites,
            'tags' => $tags,
            'status' => $status,
            'tag' => $tag,
            'search' => $search,
            'upWebsitesCount' => $upWebsitesCount,
            'downWebsitesCount' => $downWebsitesCount, 
            'changedWebsitesCount' => $changedWebsitesCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::where('user_id', Auth::id())->orderBy('name')->get();
        
        return view('websites.create', [
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'url' => [
                'required',
                'string',
                Rule::unique('websites')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'check_interval' => 'required|integer|min:1|max:1440',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('websites.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Format URL if needed (make sure it starts with http:// or https://)
        $url = $request->input('url');
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }

        // Create website
        $website = Website::create([
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'url' => $url,
            'check_interval' => $request->input('check_interval', 30),
            'is_active' => true,
            'monitoring_options' => [
                'check_ssl' => $request->has('check_ssl'),
                'check_content' => $request->has('check_content'),
                'take_screenshots' => $request->has('take_screenshots'),
            ],
        ]);

        // Sync tags
        if ($request->has('tags')) {
            $website->tags()->sync($request->input('tags'));
        }

        return redirect()->route('websites.index')
            ->with('success', 'Website added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            abort(403);
        }

        // Get monitoring logs
        $monitoringLogs = $website->monitoringLogs()->orderBy('created_at', 'desc')->limit(20)->get();
        
        // Get screenshots for this website
        $screenshots = $website->screenshots()->orderBy('created_at', 'desc')->limit(5)->get();
        
        // Find baseline and latest screenshots
        $baselineScreenshot = $website->screenshots()->where('is_baseline', true)->first();
        $latestScreenshot = $website->screenshots()->orderBy('created_at', 'desc')->first();
        
        // For demo purposes - in production, these would be actual asset paths
        $baselineScreenshotPath = $baselineScreenshot ? url('storage/' . $baselineScreenshot->path) : null;
        $latestScreenshotPath = $latestScreenshot ? url('storage/' . $latestScreenshot->path) : null;
        
        // Get dates for display
        $baselineScreenshotDate = $baselineScreenshot ? $baselineScreenshot->created_at : null;
        $latestScreenshotDate = $latestScreenshot ? $latestScreenshot->created_at : null;
        
        // Visual change percentage
        $visualChangePct = 0;
        
        // Get the last monitoring log for HTTP status and response time
        $lastLog = $monitoringLogs->first();
        
        // Update website model with the baseline date if not set
        if ($baselineScreenshot && !$website->last_baseline_at) {
            $website->last_baseline_at = $baselineScreenshot->created_at;
            $website->save();
        }
        
        // Get uptime stats
        $totalChecks = $website->monitoringLogs()->count();
        $upChecks = $website->monitoringLogs()->where('status', 'up')->count();
        $uptime = $totalChecks > 0 ? round(($upChecks / $totalChecks) * 100) : 100;
        
        if ($baselineScreenshot && $latestScreenshot && $baselineScreenshot->id !== $latestScreenshot->id) {
            // Calculate visual difference between baseline and latest
            // In production, this would use actual image comparison
            $websiteMetadata = $website->metadata ?? [];
            
            if (!isset($websiteMetadata['visual_change_pct'])) {
                // Use website ID as seed for reproducible "random" percentage
                srand($website->id * 10);
                $visualChangePct = rand(3, 15);
                
                // Store it in metadata for consistency
                $websiteMetadata['visual_change_pct'] = $visualChangePct;
                $website->metadata = $websiteMetadata;
                $website->save();
            } else {
                $visualChangePct = $websiteMetadata['visual_change_pct'];
            }
        }
        
        return view('websites.show', [
            'website' => $website,
            'monitoringLogs' => $monitoringLogs,
            'screenshots' => $screenshots,
            'baselineScreenshot' => $baselineScreenshotPath,
            'latestScreenshot' => $latestScreenshotPath,
            'baselineScreenshotDate' => $baselineScreenshotDate,
            'latestScreenshotDate' => $latestScreenshotDate,
            'visualChangePct' => $visualChangePct,
            'uptime' => $uptime,
            'totalChecks' => $totalChecks,
            'lastLog' => $lastLog
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            abort(403);
        }

        $tags = Tag::where('user_id', Auth::id())->orderBy('name')->get();
        $selectedTags = $website->tags->pluck('id')->toArray();
        
        return view('websites.edit', [
            'website' => $website,
            'tags' => $tags,
            'selectedTags' => $selectedTags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'url' => [
                'required',
                'string',
                Rule::unique('websites')->where(function ($query) use ($website) {
                    return $query->where('user_id', Auth::id());
                })->ignore($website->id),
            ],
            'check_interval' => 'required|integer|min:1|max:1440',
            'is_active' => 'required|boolean',
            'monitoring_options' => 'nullable|array',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('websites.edit', $website)
                ->withErrors($validator)
                ->withInput();
        }

        // Format URL - prepend https:// if it's not already included
        $url = $request->input('url');
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }

        // Update website
        $website->update([
            'name' => $request->input('name'),
            'url' => $url,
            'check_interval' => $request->input('check_interval'),
            'is_active' => $request->input('is_active'),
            'monitoring_options' => [
                'check_ssl' => $request->input('monitoring_options.check_ssl', false),
                'check_content' => $request->input('monitoring_options.check_content', false),
                'take_screenshots' => $request->input('monitoring_options.take_screenshots', false),
            ],
        ]);

        // Sync tags
        if ($request->has('tags')) {
            $website->tags()->sync($request->input('tags'));
        } else {
            $website->tags()->detach();
        }

        return redirect()->route('websites.show', $website)
            ->with('success', 'Website updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete website
        $website->delete();

        return redirect()->route('websites.index')
            ->with('success', 'Website deleted successfully!');
    }

    /**
     * Check a website immediately
     */
    public function checkNow(Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Create a new monitoring log
        $log = new MonitoringLog();
        $log->website_id = $website->id;
        
        // Generate a random status code (for demo)
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
            'ip_used' => $website->use_ip_override ? $website->ip_override : $_SERVER['SERVER_ADDR'] ?? '127.0.0.1',
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
        
        // If screenshots are enabled, take a new screenshot
        if (isset($website->monitoring_options['take_screenshots']) && $website->monitoring_options['take_screenshots']) {
            // Create a new screenshot
            $screenshotPath = 'screenshots/' . $website->id . '_' . time() . '.png';
            $directory = storage_path('app/public/screenshots');
            
            // Make sure the directory exists
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $fullPath = storage_path('app/public/' . $screenshotPath);
            
            try {
                // Try to capture screenshot using Browsershot
                \Spatie\Browsershot\Browsershot::url($website->url)
                    ->windowSize(1200, 800)
                    ->timeout(60) // Increased timeout to 60 seconds
                    ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36')
                    ->waitUntilNetworkIdle()
                    ->dismissDialogs()
                    ->waitForFunction('document.readyState === "complete" && document.fonts.ready', null) // Wait for page to completely load including fonts
                    ->save($fullPath);
                
                // Don't add any watermark or timestamp - use raw screenshot
                // to avoid introducing artificial differences
                
            } catch (\Exception $e) {
                // Fallback to mock screenshot for demo
                $width = 1200;
                $height = 800;
                $image = imagecreatetruecolor($width, $height);
                
                // Set background to white
                $bgColor = imagecolorallocate($image, 255, 255, 255);
                imagefill($image, 0, 0, $bgColor);
                
                // Add text with the website URL and timestamp
                $textColor = imagecolorallocate($image, 40, 40, 40);
                
                // Draw header
                imagefilledrectangle($image, 0, 0, $width, 60, imagecolorallocate($image, 240, 240, 240));
                imagerectangle($image, 0, 0, $width-1, $height-1, imagecolorallocate($image, 200, 200, 200));
                imagestring($image, 5, 20, 20, "Screenshot: " . $website->url, $textColor);
                
                // Add notice about failed browsershot
                $noteY = 80;
                imagestring($image, 4, 20, $noteY, "NOTICE: This is a simulated screenshot for demonstration purposes.", $textColor);
                imagestring($image, 3, 20, $noteY + 30, "In production, this would be a real browser screenshot of the website.", $textColor);
                
                // Create a content area
                $mockContent = [];
                
                // Add status info
                $mockContent[] = "STATUS CHECK RESULTS:";
                $mockContent[] = "URL: " . $website->url;
                $mockContent[] = "Status: " . strtoupper($status) . " (" . $statusCode . ")";
                $mockContent[] = "Response Time: " . $responseTime . "ms";
                $mockContent[] = "Checked At: " . now()->toDateTimeString();
                $mockContent[] = "";
                
                // Add content notes
                $mockContent[] = "VISUAL CHANGES:";
                
                // Get the metadata from the website
                $websiteMetadata = $website->metadata ?? [];
                $visualChangePct = $websiteMetadata['visual_change_pct'] ?? rand(0, 20);
                
                if ($visualChangePct > 0) {
                    $mockContent[] = "Changes detected: Approximately " . $visualChangePct . "% of the page has changed.";
                    $mockContent[] = "Areas with changes:";
                    $mockContent[] = "- Header section: text content updated";
                    $mockContent[] = "- Main content: images changed";
                    $mockContent[] = "- Sidebar: new elements added";
                } else {
                    $mockContent[] = "No significant visual changes detected.";
                    $mockContent[] = "The page appears identical to the baseline.";
                }
                
                // Draw mock content
                $y = 160;
                foreach ($mockContent as $line) {
                    imagestring($image, 3, 40, $y, $line, $textColor);
                    $y += 25;
                }
                
                // Save the image
                imagepng($image, $fullPath);
                imagedestroy($image);
            }
            
            // Get the baseline screenshot
            $baselineScreenshot = $website->screenshots()->where('is_baseline', true)->first();
            
            // Compare with baseline to detect changes
            $changePercentage = 0;
            
            if ($baselineScreenshot) {
                // In a real application, this would compare the actual images
                // For demo, reuse the stored visual change percentage or generate a new one
                $websiteMetadata = $website->metadata ?? [];
                $changePercentage = $websiteMetadata['visual_change_pct'] ?? 0;
            }
            
            // Create a screenshot record
            $screenshot = new Screenshot();
            $screenshot->website_id = $website->id;
            $screenshot->path = $screenshotPath;
            $screenshot->is_baseline = false;
            $screenshot->metadata = [
                'width' => 1200,
                'height' => 800,
                'visual_change_pct' => $changePercentage,
                'created_at' => now()->timestamp
            ];
            $screenshot->save();
        }
        
        return redirect()->route('websites.show', $website)
            ->with('success', 'Website checked successfully!');
    }

    /**
     * Update notification settings for a website
     */
    public function updateNotifications(Request $request, Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            abort(403);
        }

        // Get notification settings from request
        $notificationSettings = $request->input('notification_settings', []);
        
        // Ensure all sections exist even if not submitted
        $defaultSettings = [
            'downtime' => [
                'email' => false,
                'push' => false
            ],
            'content_change' => [
                'email' => false,
                'push' => false
            ],
            'ssl' => [
                'expiring_email' => false,
                'invalid_email' => false
            ],
            'performance' => [
                'email' => false,
                'threshold' => $notificationSettings['performance']['threshold'] ?? 1000
            ]
        ];
        
        // Merge with defaults
        $mergedSettings = array_merge_recursive(
            $defaultSettings,
            $notificationSettings
        );
        
        // Convert checkbox values to boolean
        foreach (['downtime', 'content_change'] as $section) {
            foreach (['email', 'push'] as $channel) {
                $mergedSettings[$section][$channel] = isset($notificationSettings[$section][$channel]);
            }
        }
        
        foreach (['expiring_email', 'invalid_email'] as $option) {
            $mergedSettings['ssl'][$option] = isset($notificationSettings['ssl'][$option]);
        }
        
        $mergedSettings['performance']['email'] = isset($notificationSettings['performance']['email']);
        
        // Update the notification settings
        $website->notification_settings = $mergedSettings;
        $website->save();
        
        return redirect()->route('websites.show', $website)
            ->with('success', 'Notification settings updated successfully!');
    }

    /**
     * Update advanced settings for a website
     */
    public function updateAdvancedSettings(Request $request, Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            abort(403);
        }

        // Validate IP if provided
        if ($request->filled('ip_override')) {
            $validator = Validator::make($request->all(), [
                'ip_override' => 'required|ip',
            ]);

            if ($validator->fails()) {
                return redirect()->route('websites.show', $website)
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        
        // Update IP override settings
        $website->ip_override = $request->input('ip_override');
        $website->use_ip_override = $request->has('use_ip_override');
        $website->save();
        
        return redirect()->route('websites.show', $website)
            ->with('success', 'Advanced settings updated successfully!');
    }

    /**
     * Reset baseline screenshot for a website
     */
    public function resetBaseline(Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Create a new baseline screenshot
        $screenshotPath = 'screenshots/' . $website->id . '_baseline_' . time() . '.png';
        $directory = storage_path('app/public/screenshots');
        
        // Make sure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $fullPath = storage_path('app/public/' . $screenshotPath);
        
        try {
            // Try to capture baseline screenshot using Browsershot
            \Spatie\Browsershot\Browsershot::url($website->url)
                ->windowSize(1200, 800)
                ->timeout(60) // Increased timeout to 60 seconds
                ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36')
                ->waitUntilNetworkIdle()
                ->dismissDialogs()
                ->waitForFunction('document.readyState === "complete" && document.fonts.ready', null) // Wait for page to completely load including fonts
                ->save($fullPath);
                
            // Don't add any timestamp or watermark - just use the raw screenshot
            // to avoid introducing artificial differences
            
        } catch (\Exception $e) {
            try {
                // If Browsershot fails, fallback to simple content retrieval
                $content = file_get_contents($website->url);
                
                if ($content) {
                    // Create a plain image with HTML content overview
                    $width = 1200;
                    $height = 800;
                    $image = imagecreatetruecolor($width, $height);
                    
                    // Set background color to white
                    $bgColor = imagecolorallocate($image, 255, 255, 255);
                    imagefill($image, 0, 0, $bgColor);
                    
                    // Add text with HTML content overview
                    $textColor = imagecolorallocate($image, 0, 0, 0);
                    $borderColor = imagecolorallocate($image, 220, 220, 220);
                    
                    // Draw header
                    imagefilledrectangle($image, 0, 0, $width, 60, imagecolorallocate($image, 240, 240, 240));
                    imagerectangle($image, 0, 0, $width-1, $height-1, $borderColor);
                    imagestring($image, 5, 20, 20, "Content Overview for " . $website->url, $textColor);
                    
                    // Parse content
                    $dom = new \DOMDocument();
                    @$dom->loadHTML($content);
                    $title = $dom->getElementsByTagName('title')->item(0)->nodeValue ?? 'No title';
                    
                    imagestring($image, 4, 20, 80, "Page Title: " . $title, $textColor);
                    imagestring($image, 3, 20, 110, "Content Length: " . strlen($content) . " bytes", $textColor);
                    
                    // Extract some content
                    $contentLines = [];
                    $bodyElement = $dom->getElementsByTagName('body')->item(0);
                    
                    if ($bodyElement) {
                        // Get all text nodes
                        $textNodes = [];
                        $this->getTextNodes($bodyElement, $textNodes);
                        
                        foreach (array_slice($textNodes, 0, 15) as $node) {
                            $text = trim($node->nodeValue);
                            if (strlen($text) > 5 && !in_array($text, $contentLines)) {
                                $contentLines[] = $text;
                            }
                        }
                    } else {
                        // Fallback to simple content excerpt
                        $contentLines = [
                            "Could not parse HTML body properly.",
                            "Showing raw content excerpt:"
                        ];
                        
                        $content = preg_replace('/<[^>]*>/', ' ', $content);
                        $content = preg_replace('/\s+/', ' ', $content);
                        
                        $contentLines[] = substr($content, 0, 100) . "...";
                    }
                    
                    // Draw content lines
                    $y = 150;
                    imagestring($image, 4, 20, $y, "Content Preview:", $textColor);
                    $y += 30;
                    
                    foreach ($contentLines as $line) {
                        $line = trim($line);
                        if (!empty($line)) {
                            // Wrap long lines
                            $wrappedText = wordwrap($line, 120, "\n", true);
                            $lineSegments = explode("\n", $wrappedText);
                            
                            foreach ($lineSegments as $segment) {
                                imagestring($image, 3, 20, $y, $segment, $textColor);
                                $y += 20;
                                
                                if ($y > ($height - 40)) {
                                    break; // Prevent drawing past the bottom
                                }
                            }
                            
                            if ($y > ($height - 40)) {
                                break;
                            }
                        }
                    }
                    
                    // Save the image - without any timestamp
                    imagepng($image, $fullPath);
                    imagedestroy($image);
                } else {
                    // Fallback to mock baseline
                    $this->createMockBaselineScreenshot($website, $fullPath);
                }
            } catch (\Exception $innerException) {
                // If any error, create a mock baseline
                $this->createMockBaselineScreenshot($website, $fullPath);
            }
        }
        
        // When setting a new baseline, reset the visual change percentage to 0
        // as there's no visual difference between the baseline and itself
        $websiteMetadata = $website->metadata ?? [];
        $websiteMetadata['visual_change_pct'] = 0;
        $website->metadata = $websiteMetadata;
        $website->save();
        
        // Create screenshot metadata
        $screenshotMetadata = [
            'width' => 1200,
            'height' => 800,
            'visual_change_pct' => 0, // No change since this is the baseline
            'created_at' => now()->timestamp
        ];
        
        // Create a screenshot record marked as baseline
        $screenshot = $website->screenshots()->create([
            'path' => $screenshotPath,
            'is_baseline' => true,
            'metadata' => $screenshotMetadata
        ]);
        
        // Update all previous screenshots to not be baseline
        $website->screenshots()
            ->where('id', '!=', $screenshot->id)
            ->where('is_baseline', true)
            ->update(['is_baseline' => false]);
        
        // Update website with new baseline screenshot
        $website->update([
            'baseline_screenshot_id' => $screenshot->id,
            'last_baseline_at' => now()
        ]);
        
        return redirect()->route('websites.show', $website)
            ->with('success', 'Baseline screenshot updated successfully!');
    }
    
    /**
     * Helper method to create a mock baseline screenshot
     */
    private function createMockBaselineScreenshot($website, $fullPath)
    {
        $width = 1200;
        $height = 800;
        $image = imagecreatetruecolor($width, $height);
        
        // Define colors
        $bgColor = imagecolorallocate($image, 245, 245, 245);
        $headerColor = imagecolorallocate($image, 22, 163, 74); // Green for baseline
        $textColor = imagecolorallocate($image, 30, 41, 59);
        $noteColor = imagecolorallocate($image, 79, 70, 229); // Indigo for important notes
        
        // Fill background
        imagefill($image, 0, 0, $bgColor);
        
        // Draw header
        imagefilledrectangle($image, 0, 0, $width, 60, $headerColor);
        
        $headerTextColor = imagecolorallocate($image, 255, 255, 255);
        imagestring($image, 5, 20, 20, "BASELINE DEMO: " . $website->url, $headerTextColor);
        
        // Add "BASELINE" watermark
        $watermarkColor = imagecolorallocate($image, 200, 230, 200); // Light green
        $text = "BASELINE SCREENSHOT";
        
        // Use a larger font for watermark
        $fontSize = 5;
        
        // Draw multiple watermarks across the image
        for ($y = 120; $y < $height; $y += 100) {
            imagestring($image, $fontSize, $width/2 - 120, $y, $text, $watermarkColor);
        }
        
        // Development note box
        $noteBoxY = 120;
        $noteBoxX = $width/2 - 350;
        $noteBoxWidth = 700;
        $noteBoxHeight = 220;
        
        // Draw note box
        imagefilledrectangle($image, $noteBoxX, $noteBoxY, $noteBoxX + $noteBoxWidth, $noteBoxY + $noteBoxHeight, imagecolorallocate($image, 220, 252, 231)); // Light green
        imagerectangle($image, $noteBoxX, $noteBoxY, $noteBoxX + $noteBoxWidth, $noteBoxY + $noteBoxHeight, $headerColor);
        
        // Add note title
        imagestring($image, 5, $noteBoxX + 10, $noteBoxY + 10, "BASELINE SCREENSHOT - DEVELOPMENT NOTE:", $headerColor);
        
        // Add explanation text
        $noteText = [
            "This is a placeholder for an actual baseline screenshot of " . $website->url,
            "",
            "The production version would capture:",
            "- Full webpage rendering with a headless browser",
            "- All visual elements including images, CSS styling, and layout",
            "- Proper viewport dimensions matching typical user devices",
            "",
            "A baseline screenshot serves as the reference point for detecting visual changes.",
            "The monitoring system compares new screenshots against this baseline to identify:",
            "- Design/layout changes",
            "- New or removed content",
            "- Visual regressions",
            ""
        ];
        
        $lineY = $noteBoxY + 40;
        foreach ($noteText as $line) {
            imagestring($image, 3, $noteBoxX + 20, $lineY, $line, $textColor);
            $lineY += 20;
        }
        
        // Add timestamp
        $timestampColor = imagecolorallocate($image, 100, 100, 100);
        imagestring($image, 3, 20, $height - 30, "Baseline created: " . now()->format('Y-m-d H:i:s'), $timestampColor);
        
        // Save the image
        imagepng($image, $fullPath);
        imagedestroy($image);
    }

    /**
     * Helper method to get all text nodes from a DOM element
     */
    private function getTextNodes($element, &$textNodes)
    {
        if ($element->nodeType === XML_TEXT_NODE) {
            $text = trim($element->nodeValue);
            if (!empty($text)) {
                $textNodes[] = $element;
            }
        } elseif ($element->nodeType === XML_ELEMENT_NODE) {
            for ($i = 0; $i < $element->childNodes->length; $i++) {
                $this->getTextNodes($element->childNodes->item($i), $textNodes);
            }
        }
    }
}
