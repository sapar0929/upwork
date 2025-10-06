<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function getMethod()
    {
        return ['Phone', 'E-mail'][$this->method];
    }

    public function getStatus()
    {
        return ['Pending', 'Completed', 'Canceled'][$this->status];
    }
}
