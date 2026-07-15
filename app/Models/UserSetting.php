<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id', 'notify_new_applications', 'notify_job_approvals',
        'notify_weekly_reports', 'dark_mode',
    ];

    protected function casts(): array
    {
        return [
            'notify_new_applications' => 'boolean',
            'notify_job_approvals' => 'boolean',
            'notify_weekly_reports' => 'boolean',
            'dark_mode' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
