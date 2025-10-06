<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Freelancer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $guarded = [
        'id',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'previous_clients' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Freelancer $freelancer) {
            $freelancer->uuid = Str::orderedUuid();
            $freelancer->previous_clients = [];
        });
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function myReviews(): HasMany
    {
        return $this->hasMany(Review::class)
            ->where('from', 'freelancer');
    }

    public function clientReviews(): HasMany
    {
        return $this->hasMany(Review::class)
            ->where('from', 'client');
    }

    public function freelancerSkills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'freelancer_skill');
    }
}
