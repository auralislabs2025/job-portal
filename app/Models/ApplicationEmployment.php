<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationEmployment extends Model
{
    protected $table = 'application_employment';

    protected $fillable = [
        'application_id', 'total_years_experience', 'current_designation',
        'industry', 'key_responsibilities', 'relevant_experience',
        'last_working_date',
    ];

    protected function casts(): array
    {
        return [
            'total_years_experience' => 'decimal:1',
            'last_working_date' => 'date',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
