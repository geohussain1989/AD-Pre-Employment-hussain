<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $interests = [
            [
                'title' => 'reading',

            ],
            [
                'title' => 'video games',

            ],
            [
                'title' => 'sports',

            ],
            [
                'title' => 'traveling',

            ]
        ];

        foreach ($interests as $interest) {
            Interest::factory()->create([
                'title' => $interest['title']
            ]);
        }
    }
}
