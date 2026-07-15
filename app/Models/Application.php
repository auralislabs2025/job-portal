<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    public function scopeForUser($query, User $user)
    {
        if ($user->group_company_id === null) {
            return $query;
        }
        return $query->whereHas('jobPosting', fn ($q) =>
            $q->where('group_company_id', $user->group_company_id)
        );
    }

    protected $fillable = [
        'code', 'job_posting_id', 'candidate_display_name',
        'email', 'mobile', 'assigned_to', 'reviewed_by',
        'status', 'notes', 'submitted_at',
        'declared_information_accurate', 'declared_authorize_verification',
        'declared_data_consent', 'declared_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'declared_at' => 'datetime',
            'declared_information_accurate' => 'boolean',
            'declared_authorize_verification' => 'boolean',
            'declared_data_consent' => 'boolean',
        ];
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function personalDetails()
    {
        return $this->hasOne(ApplicationPersonalDetail::class);
    }

    public function jobDetails()
    {
        return $this->hasOne(ApplicationJobDetail::class);
    }

    public function educations()
    {
        return $this->hasMany(ApplicationEducation::class);
    }

    public function employment()
    {
        return $this->hasOne(ApplicationEmployment::class);
    }

    public function previousEmployers()
    {
        return $this->hasMany(ApplicationPreviousEmployer::class);
    }

    public function passportVisa()
    {
        return $this->hasOne(ApplicationPassportVisa::class);
    }

    public function drivingLicense()
    {
        return $this->hasOne(ApplicationDrivingLicense::class);
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function additionalInfo()
    {
        return $this->hasOne(ApplicationAdditionalInfo::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(ApplicationStatusHistory::class);
    }

    public function notes()
    {
        return $this->hasMany(ApplicationNote::class);
    }
}
