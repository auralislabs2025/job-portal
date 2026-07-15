<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationPersonalDetail extends Model
{
    protected $fillable = [
        'application_id', 'photo_path', 'first_name', 'last_name',
        'full_name', 'gender', 'date_of_birth', 'nationality',
        'marital_status', 'email', 'mobile', 'alternate_contact',
        'current_address', 'permanent_address', 'current_country',
        'current_city', 'postal_code',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
