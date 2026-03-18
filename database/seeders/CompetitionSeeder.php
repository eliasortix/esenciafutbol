<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competition;
use Illuminate\Support\Str;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        $competitions = [
            ['name' => 'La Liga', 'type' => 'league'],
            ['name' => 'Premier League', 'type' => 'league'],
            ['name' => 'Serie A', 'type' => 'league'],
            ['name' => 'Bundesliga', 'type' => 'league'],
            ['name' => 'Ligue 1', 'type' => 'league'],
            ['name' => 'Liga Portugal', 'type' => 'league'],
            ['name' => 'Eredivisie', 'type' => 'league'],
            ['name' => 'Süper Lig', 'type' => 'league'],
            ['name' => 'MLS', 'type' => 'league'],
            ['name' => 'Saudi Pro League', 'type' => 'league'],
            ['name' => 'National Teams', 'type' => 'national'],
            ['name' => 'Retro', 'type' => 'league'],
        ];

        foreach ($competitions as $competition) {
            Competition::updateOrCreate(
                ['slug' => Str::slug($competition['name'])],
                [
                    'name' => $competition['name'],
                    'type' => $competition['type'],
                    'active' => true,
                ]
            );
        }
    }
}