<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Work extends Model
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
            'last_viewed' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function workSkills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'work_skill');
    }

    //

    public function scopeFilterQuery($query, $f_clientId, $f_freelancerId, $f_profileId, $f_experienceLevels, $f_jobTypes,
                                     $f_hourlyMinPrice, $f_hourlyMaxPrice, $f_fixedPrices, $f_fixedMinPrice, $f_fixedMaxPrice,
                                     $f_numberOfProposals, $f_projectTypes, $f_projectLengths, $f_hoursPerWeeks)
    {
        return $query
            ->when(isset($f_clientId), function ($query) use ($f_clientId) {
                return $query->where('client_id', $f_clientId);
            })
            ->when(isset($f_freelancerId), function ($query) use ($f_freelancerId) {
                return $f_freelancerId
                    ? $query->where('freelancer_id', $f_freelancerId)
                    : $query->whereNull('freelancer_id');
            })
            ->when(isset($f_profileId), function ($query) use ($f_profileId) {
                return $f_profileId
                    ? $query->where('profile_id', $f_profileId)
                    : $query->whereNull('profile_id');
            })
            ->when(isset($f_experienceLevels) and count($f_experienceLevels) > 0, function ($query) use ($f_experienceLevels) {
                return $query->whereIn('experience_level', $f_experienceLevels);
            })
            ->when(isset($f_jobTypes) and count($f_jobTypes) > 0 and in_array(0, $f_jobTypes), function ($query) use (
                $f_jobTypes,
                $f_hourlyMinPrice,
                $f_hourlyMaxPrice,
            ) {
                return $query->whereIn('job_type', $f_jobTypes)
                    ->when(isset($f_hourlyMinPrice), function ($query) use ($f_hourlyMinPrice) {
                        return $query->where('price', '>=', $f_hourlyMinPrice);
                    })
                    ->when(isset($f_hourlyMaxPrice), function ($query) use ($f_hourlyMaxPrice) {
                        return $query->where('price', '<=', $f_hourlyMaxPrice);
                    });
            })
            ->when(isset($f_jobTypes) and count($f_jobTypes) > 0 and in_array(1, $f_jobTypes), function ($query) use (
                $f_jobTypes,
                $f_fixedPrices,
                $f_fixedMinPrice,
                $f_fixedMaxPrice
            ) {
                return $query->whereIn('job_type', $f_jobTypes)
                    ->when(isset($f_fixedMinPrice), function ($query) use ($f_fixedMinPrice) {
                        return $query->where('price', '>=', $f_fixedMinPrice);
                    })
                    ->when(isset($f_fixedMaxPrice), function ($query) use ($f_fixedMaxPrice) {
                        return $query->where('price', '<=', $f_fixedMaxPrice);
                    })
                    ->when(isset($f_fixedPrices) and count($f_fixedPrices) > 0, function ($query) use ($f_fixedPrices) {
                        return $query->where(function ($query) use ($f_fixedPrices) {
                            foreach ($f_fixedPrices as $f_fixedPrice) {
                                $query->orWhere('price', '>=', [0, 100, 500, 1000, 5000][$f_fixedPrice]);
                                $query->when(isset([100, 500, 1000, 5000, null][$f_fixedPrice]), function ($query) use ($f_fixedPrice) {
                                    return $query->orWhere('price', '<=', [100, 500, 1000, 5000, null][$f_fixedPrice]);
                                });
                            }
                        });
                    });
            })
            ->when(isset($f_numberOfProposals) and count($f_numberOfProposals) > 0, function ($query) use ($f_numberOfProposals) {
                return $query->where(function ($query) use ($f_numberOfProposals) {
                    foreach ($f_numberOfProposals as $f_numberOfProposal) {
                        $query->orWhereBetween('number_of_proposals', [
                            [0, 5],
                            [5, 10],
                            [10, 15],
                            [15, 20],
                            [20, 50],
                        ][$f_numberOfProposal]);
                    }
                });
            })
            ->when(isset($f_projectTypes) and count($f_projectTypes) > 0, function ($query) use ($f_projectTypes) {
                return $query->whereIn('project_type', $f_projectTypes);
            })
            ->when(isset($f_projectLengths) and count($f_projectLengths) > 0, function ($query) use ($f_projectLengths) {
                return $query->whereIn('project_length', $f_projectLengths);
            })
            ->when(isset($f_hoursPerWeeks) and count($f_hoursPerWeeks) > 0, function ($query) use ($f_hoursPerWeeks) {
                return $query->whereIn('hours_per_week', $f_hoursPerWeeks);
            });
    }
}
