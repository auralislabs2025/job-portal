<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationPassportVisa extends Model
{
    protected $table = 'application_passport_visa';

    protected $fillable = [
        'application_id', 'passport_number', 'passport_expiry_date',
        'passport_issuing_country', 'visa_status', 'visa_type',
        'visa_expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'passport_expiry_date' => 'date',
            'visa_expiry_date' => 'date',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
