<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationJobDetail extends Model
{
    protected $fillable = [
        'application_id', 'position_applying_for', 'preferred_job_category',
        'current_job_title', 'current_employer', 'employment_status',
        'notice_period', 'available_to_join_from',
    ];

    protected function casts(): array
    {
        return [
            'available_to_join_from' => 'date',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
