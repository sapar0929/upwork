<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            'Argentina',
            'Australia',
            'Bangladesh',
            'Brazil',
            'Canada',
            'Chile',
            'China',
            'Colombia',
            'Egypt',
            'France',
            'Germany',
            'India',
            'Italy',
            'Japan',
            'Mexico',
            'Netherlands',
            'Nigeria',
            'Pakistan',
            'Philippines',
            'Poland',
            'Saudi Arabia',
            'Singapore',
            'South Africa',
            'South Korea',
            'Spain',
            'Turkey',
            'Ukraine',
            'United Arab Emirates',
            'United Kingdom',
            'United States',
        ];

        foreach ($countries as $country) {
            Location::create(['name' => $country]);
        }
    }
}
