<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {
        for ($startYear = 2000; $startYear <= 2024; $startYear++) {
            $endYear = $startYear + 1;

            $seasonName = substr((string) $startYear, -2) . '/' . substr((string) $endYear, -2);
            $sortOrder = ((int) substr((string) $startYear, -2) * 100) + (int) substr((string) $endYear, -2);

            Season::updateOrCreate(
                ['name' => $seasonName],
                [
                    'sort_order' => $sortOrder,
                    'active' => true,
                ]
            );
        }
    }
}