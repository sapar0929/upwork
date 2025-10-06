<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function freelancerSkills(): BelongsToMany
    {
        return $this->belongsToMany(Freelancer::class, 'freelancer_skill');
    }

    public function workSkills(): BelongsToMany
    {
        return $this->belongsToMany(Work::class, 'work_skill');
    }
}
