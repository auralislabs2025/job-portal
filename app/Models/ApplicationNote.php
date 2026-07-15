<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationNote extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'application_id', 'user_id', 'note', 'visibility',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
