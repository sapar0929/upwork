<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'clientId' => ['nullable', 'integer', 'min:1'],
            'freelancerId' => ['nullable', 'integer', 'min:0'],
            'profileId' => ['nullable', 'integer', 'min:0'],
            'experienceLevels' => ['nullable', 'array'],
            'experienceLevels.*' => ['nullable', 'integer', 'between:0,2'],
            'jobTypes' => ['nullable', 'array'],
            'jobTypes.*' => ['nullable', 'integer', 'between:0,1'],
            'hourlyMinPrice' => ['nullable', 'integer', 'min:0'],
            'hourlyMaxPrice' => ['nullable', 'integer', 'min:0'],
            'fixedPrices' => ['nullable', 'array'],
            'fixedPrices.*' => ['nullable', 'integer', 'between:0,4'],
            'fixedMinPrice' => ['nullable', 'integer', 'min:0'],
            'fixedMaxPrice' => ['nullable', 'integer', 'min:0'],
            'numberOfProposals' => ['nullable', 'array'],
            'numberOfProposals.*' => ['nullable', 'integer', 'between:0,4'],
            'projectTypes' => ['nullable', 'array'],
            'projectTypes.*' => ['nullable', 'integer', 'between:0,1'],
            'projectLengths' => ['nullable', 'array'],
            'projectLengths.*' => ['nullable', 'integer', 'between:0,3'],
            'hoursPerWeeks' => ['nullable', 'array'],
            'hoursPerWeeks.*' => ['nullable', 'integer', 'between:0,1'],
        ]);
        $f_clientId = $request->has('clientId') ? $request->clientId : null;
        $f_freelancerId = $request->has('freelancerId') ? $request->freelancerId : null;
        $f_profileId = $request->has('profileId') ? $request->profileId : null;
        $f_experienceLevels = $request->has('experienceLevels') ? $request->experienceLevels : null;
        $f_jobTypes = $request->has('jobTypes') ? $request->jobTypes : null;
        $f_hourlyMinPrice = $request->has('hourlyMinPrice') ? $request->hourlyMinPrice : null;
        $f_hourlyMaxPrice = $request->has('hourlyMaxPrice') ? $request->hourlyMaxPrice : null;
        $f_fixedPrices = $request->has('fixedPrices') ? $request->fixedPrices : null;
        $f_fixedMinPrice = $request->has('fixedMinPrice') ? $request->fixedMinPrice : null;
        $f_fixedMaxPrice = $request->has('fixedMaxPrice') ? $request->fixedMaxPrice : null;
        $f_numberOfProposals = $request->has('numberOfProposals') ? $request->numberOfProposals : null;
        $f_projectTypes = $request->has('projectTypes') ? $request->projectTypes : null;
        $f_projectLengths = $request->has('projectLengths') ? $request->projectLengths : null;
        $f_hoursPerWeeks = $request->has('hoursPerWeeks') ? $request->hoursPerWeeks : null;

        $works = Work::filterQuery(
            $f_clientId, // clientId
            $f_freelancerId, // freelancerId
            $f_profileId, // profileId
            $f_experienceLevels, // experienceLevels
            $f_jobTypes, // jobTypes
            $f_hourlyMinPrice, // hourlyMinPrice
            $f_hourlyMaxPrice, // hourlyMaxPrice
            $f_fixedPrices, // fixedPrices
            $f_fixedMinPrice, // fixedMinPrice
            $f_fixedMaxPrice, // fixedMaxPrice
            $f_numberOfProposals, // numberOfProposals
            $f_projectTypes, // projectTypes
            $f_projectLengths, // projectLengths
            $f_hoursPerWeeks, // hoursPerWeeks
        )
            ->orderBy('id', 'desc')
            ->get();

        return view('home.index')
            ->with([
                'works' => $works,
            ]);
    }
}
