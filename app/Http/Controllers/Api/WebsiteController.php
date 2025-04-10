<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonitoringLog;
use App\Models\Screenshot;
use App\Models\Tag;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the user's websites.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $tag = $request->input('tag');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15);

        // Base query for all user's websites
        $websitesQuery = Website::where('user_id', Auth::id());

        // Filter by status if provided
        if ($status) {
            $websitesQuery->where('last_status', $status);
        }

        // Filter by tag if provided
        if ($tag) {
            $websitesQuery->whereHas('tags', function ($query) use ($tag) {
                $query->where('slug', $tag)->orWhere('id', $tag);
            });
        }

        // Search by name or URL if provided
        if ($search) {
            $websitesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // Get websites with their tags
        $websites = $websitesQuery->with('tags')->orderBy('name')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $websites
        ]);
    }

    /**
     * Get website stats (counts by status)
     */
    public function stats()
    {
        $userWebsites = Website::where('user_id', Auth::id());
        
        // Get counts by status
        $totalWebsites = (clone $userWebsites)->count();
        $activeWebsites = (clone $userWebsites)->where('is_active', true)->count();
        $upWebsitesCount = (clone $userWebsites)->where('last_status', 'up')->count();
        $downWebsitesCount = (clone $userWebsites)->where('last_status', 'down')->count();
        $changedWebsitesCount = (clone $userWebsites)->where('last_status', 'changed')->count();
        $unknownWebsitesCount = (clone $userWebsites)->whereNull('last_status')->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total' => $totalWebsites,
                'active' => $activeWebsites,
                'up' => $upWebsitesCount,
                'down' => $downWebsitesCount,
                'changed' => $changedWebsitesCount,
                'unknown' => $unknownWebsitesCount
            ]
        ]);
    }

    /**
     * Store a newly created website.
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
            'is_active' => 'nullable|boolean',
            'monitoring_options' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
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
            'is_active' => $request->input('is_active', true),
            'monitoring_options' => [
                'check_ssl' => $request->input('monitoring_options.check_ssl', false),
                'check_content' => $request->input('monitoring_options.check_content', false),
                'take_screenshots' => $request->input('monitoring_options.take_screenshots', false),
            ],
        ]);

        // Sync tags
        if ($request->has('tags')) {
            $website->tags()->sync($request->input('tags'));
        }

        // Load tags relationship
        $website->load('tags');

        return response()->json([
            'status' => 'success',
            'message' => 'Website created successfully',
            'data' => $website
        ], 201);
    }

    /**
     * Display the specified website.
     */
    public function show(Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        // Load relationships
        $website->load('tags');
        
        // Get recent monitoring logs
        $monitoringLogs = $website->monitoringLogs()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        // Get content change logs
        $contentChangeLogs = $website->monitoringLogs()
            ->where('status', 'changed')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get recent screenshots
        $screenshots = $website->screenshots()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($screenshot) {
                return [
                    'id' => $screenshot->id,
                    'created_at' => $screenshot->created_at,
                    'is_baseline' => $screenshot->is_baseline,
                    'url' => url('storage/' . $screenshot->path),
                    'metadata' => $screenshot->metadata
                ];
            });
        
        // Calculate uptime
        $totalChecks = $website->monitoringLogs()->count();
        $upChecks = $website->monitoringLogs()->where('status', 'up')->count();
        $uptime = $totalChecks > 0 ? round(($upChecks / $totalChecks) * 100, 2) : 100;

        return response()->json([
            'status' => 'success',
            'data' => [
                'website' => $website,
                'monitoring_logs' => $monitoringLogs,
                'content_change_logs' => $contentChangeLogs,
                'screenshots' => $screenshots,
                'stats' => [
                    'uptime' => $uptime,
                    'total_checks' => $totalChecks,
                    'content_changes' => $contentChangeLogs->count()
                ]
            ]
        ]);
    }

    /**
     * Update the specified website.
     */
    public function update(Request $request, Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'url' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('websites')->where(function ($query) use ($website) {
                    return $query->where('user_id', Auth::id());
                })->ignore($website->id),
            ],
            'check_interval' => 'sometimes|required|integer|min:1|max:1440',
            'is_active' => 'sometimes|required|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'monitoring_options' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Format URL if provided
        if ($request->has('url')) {
            $url = $request->input('url');
            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $url = "https://" . $url;
            }
            $website->url = $url;
        }

        // Update website fields if provided
        if ($request->has('name')) {
            $website->name = $request->input('name');
        }
        
        if ($request->has('check_interval')) {
            $website->check_interval = $request->input('check_interval');
        }
        
        if ($request->has('is_active')) {
            $website->is_active = $request->input('is_active');
        }
        
        // Update monitoring options if provided
        if ($request->has('monitoring_options')) {
            $website->monitoring_options = [
                'check_ssl' => $request->input('monitoring_options.check_ssl', 
                    $website->monitoring_options['check_ssl'] ?? false),
                'check_content' => $request->input('monitoring_options.check_content', 
                    $website->monitoring_options['check_content'] ?? false),
                'take_screenshots' => $request->input('monitoring_options.take_screenshots', 
                    $website->monitoring_options['take_screenshots'] ?? false),
            ];
        }
        
        $website->save();

        // Sync tags if provided
        if ($request->has('tags')) {
            $website->tags()->sync($request->input('tags'));
        }
        
        // Load tags relationship
        $website->load('tags');

        return response()->json([
            'status' => 'success',
            'message' => 'Website updated successfully',
            'data' => $website
        ]);
    }

    /**
     * Remove the specified website.
     */
    public function destroy(Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete related data
        $website->monitoringLogs()->delete();
        $website->screenshots()->delete();
        
        // Delete the website
        $website->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Website deleted successfully'
        ]);
    }

    /**
     * Get website monitoring logs.
     */
    public function logs(Request $request, Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $limit = $request->input('limit', 50);
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $logsQuery = $website->monitoringLogs()->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($status) {
            $logsQuery->where('status', $status);
        }

        // Filter by date range if provided
        if ($startDate) {
            $logsQuery->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $logsQuery->where('created_at', '<=', $endDate);
        }

        $logs = $logsQuery->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $logs
        ]);
    }

    /**
     * Get the screenshots for a website
     */
    public function screenshots(Request $request, Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $perPage = $request->input('per_page', 10);
        $screenshots = $website->screenshots()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        // Transform the screenshots to include URLs and comparison data
        $screenshots->getCollection()->transform(function ($screenshot) {
            $data = [
                'id' => $screenshot->id,
                'created_at' => $screenshot->created_at,
                'is_baseline' => $screenshot->is_baseline,
                'url' => url('storage/' . $screenshot->path),
                'metadata' => $screenshot->metadata
            ];
            
            // Add comparison data if available
            if ($screenshot->hasComparison()) {
                $data['comparison'] = [
                    'diff_url' => $screenshot->getDiffUrl(),
                    'overlay_url' => $screenshot->getOverlayUrl(),
                    'diff_percentage' => $screenshot->getDiffPercentage(),
                    'baseline_id' => $screenshot->metadata['comparison']['baseline_id'] ?? null,
                    'compared_at' => $screenshot->metadata['comparison']['compared_at'] ?? null
                ];
            }
            
            return $data;
        });

        return response()->json([
            'status' => 'success',
            'data' => $screenshots
        ]);
    }

    /**
     * Check a website immediately.
     */
    public function checkNow(Website $website)
    {
        // Make sure the website belongs to the authenticated user
        if ($website->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        // Create a new monitoring log
        $log = new MonitoringLog();
        $log->website_id = $website->id;
        
        // For demo purposes, generate random status and response time
        // In production, this would make an actual HTTP request
        $rand = mt_rand(1, 100);
        $status = ($rand <= 80) ? 'up' : ($rand <= 95 ? 'down' : 'changed');
        $statusCode = ($status === 'up') ? 200 : ($status === 'down' ? 500 : 200);
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
        
        // Update website with the latest status
        $website->last_checked_at = now();
        $website->last_status = $status;
        $website->last_status_code = $statusCode;
        $website->last_response_time = $responseTime;
        $website->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Website checked successfully',
            'data' => [
                'log' => $log,
                'website' => $website
            ]
        ]);
    }

    /**
     * Get all user tags.
     */
    public function tags()
    {
        $tags = Tag::where('user_id', Auth::id())->orderBy('name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $tags
        ]);
    }
}
