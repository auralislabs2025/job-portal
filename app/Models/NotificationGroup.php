<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationGroup extends Model
{
    use SoftDeletes;

    public function scopeForUser($query, User $user)
    {
        if ($user->group_company_id === null) {
            return $query;
        }
        return $query->where('group_company_id', $user->group_company_id);
    }

    protected $fillable = [
        'group_company_id', 'name', 'description',
    ];

    public function groupCompany()
    {
        return $this->belongsTo(GroupCompany::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_group_user');
    }

    public function jobPostings()
    {
        return $this->belongsToMany(JobPosting::class, 'job_posting_notification_group');
    }
}
