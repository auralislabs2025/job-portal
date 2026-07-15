<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosting extends Model
{
    use SoftDeletes;

    public function scopeForUser($query, User $user)
    {
        if ($user->group_company_id === null) {
            return $query;
        }
        return $query->where('group_company_id', $user->group_company_id);
    }

    protected $table = 'job_postings';

    protected $fillable = [
        'code', 'group_company_id', 'created_by', 'approved_by',
        'title', 'employment_type', 'location', 'salary',
        'deadline', 'description', 'status',
        'approved_at', 'rejected_reason', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'approved_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function groupCompany()
    {
        return $this->belongsTo(GroupCompany::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function notificationGroups()
    {
        return $this->belongsToMany(NotificationGroup::class, 'job_posting_notification_group');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function jobStatusHistories()
    {
        return $this->hasMany(JobStatusHistory::class);
    }
}
