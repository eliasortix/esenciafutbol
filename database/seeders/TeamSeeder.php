<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    public function run(): void
    {

        $teamsByCompetition = [

            'La Liga' => [
                'Real Madrid',
                'FC Barcelona',
                'Atlético de Madrid',
                'Athletic Club',
                'Valencia CF',
                'Real Sociedad',
                'Real Betis',
                'Sevilla FC',
                'Granada CF',
                'Cádiz CF',
                'RC Celta de Vigo',
                'Rayo Vallecano',
                'UD Las Palmas',
                'Villarreal CF',
                'Málaga CF',
                'Real Valladolid',
                'Deportivo La Coruña',
                'CA Osasuna',
                'RCD Espanyol',
                'Real Zaragoza',
            ],

            'Premier League' => [
                'Manchester United',
                'Arsenal',
                'Chelsea',
                'Tottenham Hotspur',
                'Liverpool',
                'Manchester City',
                'Newcastle United',
                'West Ham United',
                'Everton',
                'Leicester City',
                'Aston Villa',
                'Nottingham Forest',
                'Southampton',
            ],

            'Serie A' => [
                'AC Milan',
                'Juventus',
                'Internazionale',
                'SSC Napoli',
                'SS Lazio',
                'AS Roma',
                'Atalanta BC',
                'ACF Fiorentina',
                'Parma Calcio',
            ],

            'Ligue 1' => [
                'Paris Saint-Germain',
                'Olympique de Marseille',
                'Olympique Lyonnais',
                'AS Monaco FC',
            ],

            'Bundesliga' => [
                'Bayern München',
                'Bayer 04 Leverkusen',
                'Borussia Dortmund',
                'RB Leipzig',
            ],

            'Eredivisie' => [
                'AFC Ajax',
                'Feyenoord Rotterdam',
                'PSV Eindhoven',
            ],

            'Primeira Liga' => [
                'Sporting CP',
                'FC Porto',
                'SL Benfica',
                'SC Braga',
            ],

            'Saudi Professional League' => [
                'Al Ahli Saudi',
                'Al Hilal Saudi',
                'Al Nassr',
                'Al Ittihad Saudi',
            ],

            'Other leagues' => [
                'Celtic',
                'Rangers',
                'Beşiktaş',
                'Galatasaray',
                'Boca Juniors',
                'River Plate',
            ],

            'MLS' => [
                'Inter Miami CF',
                'New York Red Bulls',
                'LA Galaxy',
            ],

            'Brasileiro Serie A' => [
                'CR Flamengo',
                'SC Corinthians Paulista',
                'SE Palmeiras',
                'São Paulo FC',
                'CR Vasco da Gama',
                'Santos FC',
            ],

            'Liga MX' => [
                'CD Guadalajara',
                'Club América',
                'Tigres UANL',
                'Cruz Azul',
            ],

            'National Teams' => [
                'Brazil',
                'Argentina',
                'Chile',
                'England',
                'France',
                'Spain',
                'Germany',
                'Portugal',
                'Italy',
                'Belgium',
                'Croatia',
                'Netherlands',
                'Turkey',
                'Poland',
                'Scotland',
                'Wales',
                'Ireland',
                'Denmark',
                'Albania',
                'Mexico',
                'Colombia',
                'Japan',
                'Nigeria',
                'Morocco',
                'Cameroon',
                'Senegal',
                "Côte d'Ivoire",
                'Algeria'
            ]

        ];

        foreach ($teamsByCompetition as $competitionName => $teams) {

            $competition = Competition::where('name', $competitionName)->first();

            if (!$competition) continue;

            foreach ($teams as $teamName) {

                Team::updateOrCreate(
                    ['slug' => Str::slug($teamName)],
                    [
                        'competition_id' => $competition->id,
                        'name' => $teamName,
                        'active' => true
                    ]
                );

            }
        }

    }
}