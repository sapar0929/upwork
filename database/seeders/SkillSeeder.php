<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            'Laravel',
            'PHP',
            'JavaScript',
            'Vue.js',
            'React',
            'MySQL',
            'PostgreSQL',
            'REST API',
            'Git',
            'Docker',
        ];

        foreach ($skills as $skill) {
            Skill::create(['name' => $skill]);
        }
    }
}
