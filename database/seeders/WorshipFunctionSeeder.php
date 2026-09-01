<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\WorshipFunction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WorshipFunctionSeeder extends Seeder
{
    public function run(): void
    {
        $functions = [
            ['name' => 'Vocal', 'sort_order' => 1],
            ['name' => 'Violão', 'sort_order' => 2],
            ['name' => 'Guitarra', 'sort_order' => 3],
            ['name' => 'Baixo', 'sort_order' => 4],
            ['name' => 'Teclado', 'sort_order' => 5],
            ['name' => 'Bateria', 'sort_order' => 6],
            ['name' => 'Som', 'sort_order' => 7],
            ['name' => 'Multimídia', 'sort_order' => 8],
            ['name' => 'Direção do culto', 'sort_order' => 9],
        ];

        $members = Member::query()->orderBy('id')->get();

        foreach ($functions as $functionData) {
            $worshipFunction = WorshipFunction::firstOrCreate(
                ['slug' => Str::slug($functionData['name'])],
                [
                    'name' => $functionData['name'],
                    'sort_order' => $functionData['sort_order'],
                    'is_active' => true,
                ]
            );

            if ($members->isEmpty()) {
                continue;
            }

            $memberIds = match ($functionData['name']) {
                'Vocal' => $members->take(3)->pluck('id')->all(),
                'Violão' => [$members->first()->id],
                'Guitarra' => $members->skip(1)->take(1)->pluck('id')->all(),
                'Baixo' => $members->skip(2)->take(1)->pluck('id')->all(),
                'Teclado' => $members->skip(3)->take(1)->pluck('id')->all(),
                'Bateria' => $members->skip(4)->take(1)->pluck('id')->all(),
                'Som' => $members->skip(5)->take(1)->pluck('id')->all(),
                'Multimídia' => $members->skip(6)->take(1)->pluck('id')->all(),
                'Direção do culto' => $members->skip(7)->take(1)->pluck('id')->all(),
                default => [],
            };

            if ($memberIds !== []) {
                $worshipFunction->members()->syncWithoutDetaching($memberIds);
            }
        }

        $this->command->info('Funções de louvor criadas com sucesso!');
    }
}
