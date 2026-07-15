<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\JobPosting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DashboardStatsService
{
    public function stats(): array
    {
        return [
            'open_jobs' => JobPosting::where('status', 'published')->count(),
            'pending_approval' => JobPosting::where('status', 'pending')->count(),
            'published_jobs' => JobPosting::where('status', 'published')->count(),
            'applications' => Application::count(),
            'interviews' => Application::where('status', 'interview')->count(),
            'offers' => Application::where('status', 'offer')->count(),
            'hired' => Application::where('status', 'hired')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];
    }

    public function applicationsTrend(): array
    {
        $months = collect();
        for ($i = 6; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }

        $raw = Application::selectRaw("to_char(created_at, 'YYYY-MM') as month, count(*) as total")
            ->where('created_at', '>=', now()->subMonths(7))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $labels = $months->map(fn ($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M'));
        $data = $months->map(fn ($m) => (int) ($raw[$m] ?? 0));

        return [
            'labels' => $labels->values()->toArray(),
            'data' => $data->values()->toArray(),
        ];
    }

    public function hiringFunnel(): array
    {
        $statuses = ['pending', 'screening', 'interview', 'offer', 'hired'];
        $counts = Application::selectRaw('status, count(*) as total')
            ->whereIn('status', $statuses)
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = array_map(fn ($s) => ucfirst($s), $statuses);
        $data = array_map(fn ($s) => (int) ($counts[$s] ?? 0), $statuses);

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function recentApplications(): Collection
    {
        return Application::with(['documents', 'jobPosting'])
            ->latest('submitted_at')
            ->take(5)
            ->get()
            ->map(fn ($app) => [
                'id' => $app->id,
                'name' => $app->candidate_display_name,
                'job_title' => $app->jobPosting?->title,
                'status' => $app->status,
                'submitted_at' => $app->submitted_at?->format('M d, Y'),
                'documents' => $app->documents->map(fn ($d) => [
                    'id' => $d->id,
                    'name' => $d->original_filename,
                    'type' => $d->document_type,
                    'size' => $d->file_size_bytes ? round($d->file_size_bytes / 1024) . ' KB' : null,
                    'mime' => $d->mime_type,
                ]),
            ]);
    }

    public function recentActivity()
    {
        return ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($log) => [
                'id' => $log->id,
                'action' => $log->action,
                'details' => $log->new_values['title'] ?? $log->action,
                'user' => $log->user?->name ?? 'System',
                'timestamp' => $log->created_at->format('Y-m-d H:i'),
                'module' => $log->module,
            ]);
    }
}
