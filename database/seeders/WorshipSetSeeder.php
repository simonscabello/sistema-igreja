<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Song;
use App\Models\WorshipFunction;
use App\Models\WorshipSet;
use App\Models\WorshipSetAssignment;
use Illuminate\Database\Seeder;

class WorshipSetSeeder extends Seeder
{
    public function run(): void
    {
        $songs = Song::all();

        if ($songs->count() < 3) {
            $this->command->info('É necessário ter pelo menos 3 músicas cadastradas para criar repertórios de exemplo.');

            return;
        }

        $vocalFunction = WorshipFunction::where('slug', 'vocal')->first();
        $direcaoFunction = WorshipFunction::where('slug', 'direcao-do-culto')->first();
        $violaoFunction = WorshipFunction::where('slug', 'violao')->first();
        $members = Member::query()->orderBy('id')->get();

        $worshipSets = [
            [
                'date' => now()->subDays(7),
                'period' => 'manha',
                'order_notes' => "1. Grande é o Senhor (abertura)\n2. Santo, Santo, Santo (adoração)\n3. Amazing Grace (momento de reflexão)",
                'observations' => 'Culto muito abençoado. Congregação participou ativamente.',
                'song_ids' => $songs->take(3)->pluck('id')->toArray(),
                'song_keys' => ['C', 'D', 'G'],
                'assignments' => [
                    'vocal' => $members->take(2)->pluck('id')->all(),
                    'violao' => $members->skip(2)->take(1)->pluck('id')->all(),
                    'direcao' => $members->skip(3)->take(1)->pluck('id')->all(),
                ],
            ],
            [
                'date' => now()->subDays(3),
                'period' => 'noite',
                'order_notes' => "1. Como é Grande o Meu Deus (louvor)\n2. Aos Pés da Cruz (adoração)\n3. Grande é o Senhor (encerramento)",
                'observations' => 'Culto de jovens. Muita energia e participação.',
                'song_ids' => $songs->take(3)->pluck('id')->toArray(),
                'song_keys' => ['Am', 'G', 'C'],
                'assignments' => [
                    'vocal' => $members->skip(1)->take(2)->pluck('id')->all(),
                    'violao' => $members->skip(4)->take(1)->pluck('id')->all(),
                ],
            ],
            [
                'date' => now()->subDay(),
                'period' => 'manha',
                'order_notes' => "1. Santo, Santo, Santo (abertura)\n2. Como é Grande o Meu Deus (louvor)\n3. Amazing Grace (momento especial)",
                'observations' => 'Culto de domingo. Presença de visitantes.',
                'song_ids' => $songs->take(3)->pluck('id')->toArray(),
                'song_keys' => ['D', 'Em', 'A'],
                'assignments' => [
                    'vocal' => $members->take(1)->pluck('id')->all(),
                    'direcao' => $members->skip(5)->take(1)->pluck('id')->all(),
                ],
            ],
            [
                'date' => now()->next('Sunday'),
                'period' => 'manha',
                'order_notes' => "1. Grande é o Senhor (abertura)\n2. Santo, Santo, Santo (adoração)\n3. Amazing Grace (encerramento)",
                'observations' => 'Próximo culto de domingo.',
                'song_ids' => $songs->take(3)->pluck('id')->toArray(),
                'song_keys' => ['G', 'C', 'D'],
                'assignments' => [
                    'vocal' => $members->skip(2)->take(3)->pluck('id')->all(),
                    'violao' => $members->first()?->id ? [$members->first()->id] : [],
                    'direcao' => $members->skip(7)->take(1)->pluck('id')->all(),
                ],
            ],
        ];

        $functionMap = [
            'vocal' => $vocalFunction,
            'violao' => $violaoFunction,
            'direcao' => $direcaoFunction,
        ];

        foreach ($worshipSets as $worshipSetData) {
            $songIds = $worshipSetData['song_ids'];
            $songKeys = $worshipSetData['song_keys'];
            $assignmentData = $worshipSetData['assignments'];
            unset($worshipSetData['song_ids'], $worshipSetData['song_keys'], $worshipSetData['assignments']);

            $worshipSet = WorshipSet::create($worshipSetData);

            $songData = [];
            foreach ($songIds as $index => $songId) {
                $songData[$songId] = [
                    'order' => $index + 1,
                    'key_used' => $songKeys[$index] ?? null,
                ];
            }

            $worshipSet->songs()->attach($songData);

            foreach ($assignmentData as $functionKey => $memberIds) {
                $function = $functionMap[$functionKey] ?? null;

                if (! $function || $memberIds === []) {
                    continue;
                }

                foreach (array_values($memberIds) as $index => $memberId) {
                    WorshipSetAssignment::create([
                        'worship_set_id' => $worshipSet->id,
                        'worship_function_id' => $function->id,
                        'member_id' => $memberId,
                        'order' => $index + 1,
                    ]);
                }
            }
        }

        $this->command->info('Repertórios de exemplo criados com sucesso!');
    }
}
