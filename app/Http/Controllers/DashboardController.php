<?php

namespace App\Http\Controllers;

use App\Services\DashboardStatsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(DashboardStatsService $service): View
    {
        abort_unless(auth()->user()->hasPermission('view_dashboard'), 403);
        $stats = $service->stats();
        $applicationsTrend = $service->applicationsTrend();
        $funnelData = $service->hiringFunnel();
        $recentActivity = $service->recentActivity();
        $recentApplications = $service->recentApplications();

        return view('dashboard', compact(
            'stats', 'applicationsTrend', 'funnelData', 'recentActivity', 'recentApplications'
        ));
    }
}
