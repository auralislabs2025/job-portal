<?php

namespace App\Services;

use App\Models\JobPosting;

class JobStatusService
{
    protected array $transitions = [
        'draft' => ['pending', 'published'],
        'pending' => ['published', 'rejected'],
        'published' => ['pending', 'rejected'],
    ];

    public function canTransition(string $from, string $to): bool
    {
        return in_array($to, $this->transitions[$from] ?? []);
    }

    public function transition(JobPosting $job, string $action, ?string $rejectedReason = null): JobPosting
    {
        $targetStatus = match ($action) {
            'submit', 'revert' => 'pending',
            'approve', 'publish' => 'published',
            'reject' => 'rejected',
            default => $action,
        };

        if (!$this->canTransition($job->status, $targetStatus)) {
            abort(422, "Cannot transition from {$job->status} to {$targetStatus}");
        }

        $oldStatus = $job->status;

        if ($action === 'approve' || $action === 'publish') {
            $job->approved_by = auth()->id();
            $job->approved_at = now();
            $job->published_at = now();
        } elseif ($action === 'reject') {
            $job->rejected_reason = $rejectedReason;
        }

        $job->status = $targetStatus;
        $job->save();

        $job->jobStatusHistories()->create([
            'previous_status' => $oldStatus,
            'current_status' => $targetStatus,
            'remarks' => $rejectedReason,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        ActivityLogger::log(
            action: $targetStatus === 'rejected' ? 'rejected' : $targetStatus,
            module: 'job_postings',
            recordId: $job->id,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => $targetStatus, 'rejected_reason' => $rejectedReason],
        );

        return $job;
    }
}
