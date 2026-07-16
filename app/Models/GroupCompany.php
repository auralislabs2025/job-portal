<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupCompany extends Model
{
    use SoftDeletes;

    public function scopeForUser($query, User $user)
    {
        if ($user->group_company_id === null) {
            return $query;
        }
        return $query->where('id', $user->group_company_id);
    }

    protected $fillable = [
        'name', 'code', 'email', 'phone', 'address', 'city',
        'country', 'description', 'logo', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class);
    }

    public function notificationGroups()
    {
        return $this->hasMany(NotificationGroup::class);
    }
}
