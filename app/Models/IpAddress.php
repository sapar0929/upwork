<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IpAddress extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function authAttempts(): HasMany
    {
        return $this->hasMany(AuthAttempt::class);
    }

    public function visitors(): HasMany
    {
        return $this->hasMany(Visitor::class);
    }

    //

    public function getIp()
    {
        $ip = array_filter([$this->country_code, $this->country_name, $this->city_name]);
        return $ip ? implode(', ', $ip) : trans('Not found');
    }
}
