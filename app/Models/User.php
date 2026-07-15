<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role_id',
        'group_company_id', 'status', 'last_login_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeForUser($query, User $user)
    {
        if ($user->group_company_id === null) {
            return $query;
        }
        return $query->where('group_company_id', $user->group_company_id);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function groupCompany()
    {
        return $this->belongsTo(GroupCompany::class);
    }

    public function userSetting()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function notificationGroups()
    {
        return $this->belongsToMany(NotificationGroup::class, 'notification_group_user');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function jobPostingsCreated()
    {
        return $this->hasMany(JobPosting::class, 'created_by');
    }

    public function applicationsAssigned()
    {
        return $this->hasMany(Application::class, 'assigned_to');
    }

    public function applicationNotes()
    {
        return $this->hasMany(ApplicationNote::class);
    }

    public function hasPermission(string $slug): bool
    {
        return $this->role?->permissions()
            ->where('slug', $slug)
            ->exists() ?? false;
    }
}
