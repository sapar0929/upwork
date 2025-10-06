<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthAttempt extends Model
{
    protected $guarded = [
        'id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    const UPDATED_AT = null;

    public function ipAddress(): BelongsTo
    {
        return $this->belongsTo(IpAddress::class);
    }

    public function userAgent(): BelongsTo
    {
        return $this->belongsTo(UserAgent::class);
    }
}
