<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MartialArtsClass;

class GymExpansionSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            [
                'name' => 'Bumblebees (Ages 5–8)',
                'category' => 'Kids',
                'type' => 'Grappling',
                'age_group' => '5-8',
                'level' => 'Beginner',
                'status' => 'active',
                'description' => 'A fun and engaging introduction to martial arts for young children.',
            ],
            [
                'name' => 'Harness (Ages 9–11)',
                'category' => 'Youth',
                'type' => 'Grappling',
                'age_group' => '9-11',
                'level' => 'All Levels',
                'status' => 'active',
                'description' => 'Building discipline, strength, and technique for growing youths.',
            ],
            [
                'name' => 'Killer Bees (Ages 12–16)',
                'category' => 'Youth',
                'type' => 'Grappling',
                'age_group' => '12-16',
                'level' => 'All Levels',
                'status' => 'active',
                'description' => 'Advanced techniques and sparring for teens preparing for adult classes.',
            ]
        ];

        foreach ($classes as $class) {
            MartialArtsClass::firstOrCreate(['name' => $class['name']], $class);
        }
    }
}
