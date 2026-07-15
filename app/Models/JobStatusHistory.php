<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobStatusHistory extends Model
{
    protected $table = 'job_status_history';

    public $timestamps = false;

    protected $fillable = [
        'job_posting_id', 'previous_status', 'current_status',
        'remarks', 'changed_by', 'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function changedByUser()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
