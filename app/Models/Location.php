<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function freelancers(): HasMany
    {
        return $this->hasMany(Freelancer::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}
