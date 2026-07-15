<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationPreviousEmployer extends Model
{
    protected $fillable = [
        'application_id', 'employer_name', 'designation',
        'industry', 'responsibilities', 'started_on', 'ended_on',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'started_on' => 'date',
            'ended_on' => 'date',
            'sort_order' => 'integer',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
