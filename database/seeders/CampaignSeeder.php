<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Campaign;
use Carbon\Carbon;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = [
            [
                'name' => 'Reforma do Templo',
                'description' => 'Campanha para arrecadar fundos para a reforma do templo principal',
                'goal_amount' => 50000.00,
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(4),
                'status' => 'ativo',
            ],
            [
                'name' => 'Ação Social - Natal',
                'description' => 'Campanha para arrecadar alimentos e brinquedos para famílias carentes no Natal',
                'goal_amount' => 15000.00,
                'start_date' => Carbon::now()->subMonth(),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'ativo',
            ],
            [
                'name' => 'Compra de Instrumentos Musicais',
                'description' => 'Campanha para comprar novos instrumentos para o grupo de louvor',
                'goal_amount' => 8000.00,
                'start_date' => Carbon::now()->subMonths(3),
                'end_date' => Carbon::now()->subMonth(),
                'status' => 'encerrado',
            ],
            [
                'name' => 'Missão Internacional',
                'description' => 'Campanha para apoiar missionários em campo internacional',
                'goal_amount' => 25000.00,
                'start_date' => Carbon::now()->subMonths(6),
                'end_date' => Carbon::now()->subMonths(2),
                'status' => 'cancelada',
            ],
        ];

        foreach ($campaigns as $campaignData) {
            Campaign::create($campaignData);
        }
    }
}
