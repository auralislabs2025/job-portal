<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationAdditionalInfo extends Model
{
    protected $table = 'application_additional_info';

    protected $fillable = [
        'application_id', 'cover_letter', 'willing_to_relocate',
        'willing_to_travel', 'interview_availability', 'linkedin_url',
        'portfolio_url', 'reference_name', 'reference_contact',
        'additional_comments',
    ];

    protected function casts(): array
    {
        return [
            'willing_to_relocate' => 'boolean',
            'willing_to_travel' => 'boolean',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
