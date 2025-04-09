<?php

namespace App\Http\Controllers;

use App\Models\MonitoringLog;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        // Get website statistics
        $totalWebsites = Website::where('user_id', $user->id)->count();
        $activeWebsites = Website::where('user_id', $user->id)->where('is_active', true)->count();
        
        // Get websites by status
        $upWebsites = Website::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('last_status', MonitoringLog::STATUS_UP)
            ->count();
            
        $downWebsites = Website::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('last_status', MonitoringLog::STATUS_DOWN)
            ->count();
            
        $changedWebsites = Website::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('last_status', MonitoringLog::STATUS_CHANGED)
            ->count();
        
        // Get recent activity
        $recentLogs = MonitoringLog::whereHas('website', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('website')
        ->orderBy('created_at', 'desc')
        ->take(15)
        ->get();
        
        // Get websites with issues
        $websitesWithIssues = Website::where('user_id', $user->id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('last_status', MonitoringLog::STATUS_DOWN)
                    ->orWhere('last_status', MonitoringLog::STATUS_CHANGED);
            })
            ->with('recentLogs')
            ->orderBy('last_checked_at', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboard', compact(
            'totalWebsites',
            'activeWebsites',
            'upWebsites',
            'downWebsites',
            'changedWebsites',
            'recentLogs',
            'websitesWithIssues'
        ));
    }
}
