<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorshipSet;
use App\Models\Song;

class WorshipSetSeeder extends Seeder
{
    public function run(): void
    {
        $songs = Song::all();
        
        if ($songs->count() < 3) {
            $this->command->info('É necessário ter pelo menos 3 músicas cadastradas para criar repertórios de exemplo.');
            return;
        }

        $worshipSets = [
            [
                'singer' => 'João Silva',
                'preacher' => 'Pastor Carlos',
                'date' => now()->subDays(7),
                'period' => 'manha',
                'order_notes' => '1. Grande é o Senhor (abertura)\n2. Santo, Santo, Santo (adoração)\n3. Amazing Grace (momento de reflexão)',
                'observations' => 'Culto muito abençoado. Congregação participou ativamente.',
                'song_ids' => $songs->take(3)->pluck('id')->toArray(),
                'song_keys' => ['C', 'D', 'G']
            ],
            [
                'singer' => 'Maria Santos',
                'preacher' => 'Pastor Roberto',
                'date' => now()->subDays(3),
                'period' => 'noite',
                'order_notes' => '1. Como é Grande o Meu Deus (louvor)\n2. Aos Pés da Cruz (adoração)\n3. Grande é o Senhor (encerramento)',
                'observations' => 'Culto de jovens. Muita energia e participação.',
                'song_ids' => $songs->take(3)->pluck('id')->toArray(),
                'song_keys' => ['Am', 'G', 'C']
            ],
            [
                'singer' => 'Pedro Costa',
                'preacher' => 'Pastor Ana',
                'date' => now()->subDay(),
                'period' => 'manha',
                'order_notes' => '1. Santo, Santo, Santo (abertura)\n2. Como é Grande o Meu Deus (louvor)\n3. Amazing Grace (momento especial)',
                'observations' => 'Culto de domingo. Presença de visitantes.',
                'song_ids' => $songs->take(3)->pluck('id')->toArray(),
                'song_keys' => ['D', 'Em', 'A']
            ]
        ];

        foreach ($worshipSets as $worshipSetData) {
            $songIds = $worshipSetData['song_ids'];
            $songKeys = $worshipSetData['song_keys'];
            unset($worshipSetData['song_ids'], $worshipSetData['song_keys']);
            
            $worshipSet = WorshipSet::create($worshipSetData);
            
            $songData = [];
            foreach ($songIds as $index => $songId) {
                $songData[$songId] = [
                    'order' => $index + 1,
                    'key_used' => $songKeys[$index] ?? null
                ];
            }
            
            $worshipSet->songs()->attach($songData);
        }

        $this->command->info('Repertórios de exemplo criados com sucesso!');
    }
} 