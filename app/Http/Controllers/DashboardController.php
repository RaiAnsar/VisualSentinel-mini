<?php

namespace App\Http\Controllers;

use App\Models\MonitoringLog;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        // Get website statistics
        $totalWebsites = Website::where('user_id', $userId)->count();
        $activeWebsites = Website::where('user_id', $userId)->where('is_active', true)->count();
        
        // Get websites by status
        $upWebsites = Website::where('user_id', $userId)
            ->where('is_active', true)
            ->where('last_status', 'up')
            ->count();
            
        $downWebsites = Website::where('user_id', $userId)
            ->where('is_active', true)
            ->where('last_status', 'down')
            ->count();
            
        $changedWebsites = Website::where('user_id', $userId)
            ->where('is_active', true)
            ->where('last_status', 'changed')
            ->count();
        
        // Get recent activity
        $recentLogs = MonitoringLog::whereHas('website', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with('website')
        ->orderBy('created_at', 'desc')
        ->take(15)
        ->get();
        
        // Get websites with issues
        $websitesWithIssues = Website::where('user_id', $userId)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('last_status', 'down')
                    ->orWhere('last_status', 'changed');
            })
            ->with('recentLogs')
            ->orderBy('last_checked_at', 'desc')
            ->take(5)
            ->get();
        
        // --- Start: Average Response Time Data --- 
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $avgResponseTimes = MonitoringLog::select(
                DB::raw('DATE(created_at) as date'), 
                DB::raw('AVG(response_time) as average_response_time')
            )
            ->whereHas('website', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        // Prepare data for the chart (last 7 days)
        $responseChartLabels = [];
        $responseChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $responseChartLabels[] = Carbon::parse($date)->format('M d');
            $responseChartData[] = $avgResponseTimes->get($date)->average_response_time ?? 0;
        }
        // --- End: Average Response Time Data --- 
        
        return view('dashboard', compact(
            'totalWebsites',
            'activeWebsites',
            'upWebsites',
            'downWebsites',
            'changedWebsites',
            'recentLogs',
            'websitesWithIssues',
            'responseChartLabels',
            'responseChartData'
        ));
    }
}
