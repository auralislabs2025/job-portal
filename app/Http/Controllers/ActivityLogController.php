<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->hasPermission('view_activity_logs'), 403);
        $logs = ActivityLog::with('user')
            ->latest('created_at')
            ->limit(100)
            ->get();

        return view('activity-logs.index', compact('logs'));
    }
}
