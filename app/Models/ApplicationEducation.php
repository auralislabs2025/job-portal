<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationEducation extends Model
{
    protected $table = 'application_educations';

    protected $fillable = [
        'application_id', 'is_highest', 'qualification_level',
        'degree_diploma', 'specialization', 'institution',
        'graduation_year', 'grade_percentage',
    ];

    protected function casts(): array
    {
        return [
            'is_highest' => 'boolean',
            'graduation_year' => 'integer',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
