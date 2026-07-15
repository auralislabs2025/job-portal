<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationDrivingLicense extends Model
{
    protected $table = 'application_driving_license';

    protected $fillable = [
        'application_id', 'license_number', 'license_country',
        'license_type', 'license_expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'license_expiry_date' => 'date',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
