<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAgent extends Model
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

    public function getUa()
    {
        $ua = array_filter([$this->device, $this->platform, $this->browser, $this->robot ? '(' . $this->robot . ')' : null]);
        return $ua ? implode(', ', $ua) : trans('app.notFound');
    }
}
