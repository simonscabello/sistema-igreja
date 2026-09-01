<?php

use App\Models\Member;
use App\Models\Song;
use App\Models\WorshipFunction;
use App\Models\WorshipSet;
use App\Models\WorshipSetAssignment;

beforeEach(function () {
    $this->user = userWithPermissions(['visualizar_membros', 'visualizar_visitantes', 'visualizar_financeiro', 'visualizar_escalas_louvor']);
});

it('exibe o dashboard autenticado', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->has('aniversariantesDoMes')
            ->has('ultimosVisitantes')
            ->has('visitantesQuerendoContato')
            ->has('proximoCulto')
            ->has('saldoAtual'));
});

it('inclui o próximo culto futuro no dashboard', function () {
    $song = Song::create(['name' => 'Grande é o Senhor', 'key' => 'G']);
    $vocalFunction = WorshipFunction::factory()->create(['name' => 'Vocal', 'slug' => 'vocal']);
    $member = Member::factory()->create(['full_name' => 'Maria Vocal']);
    $vocalFunction->members()->attach($member);

    $set = WorshipSet::create([
        'date' => now()->addDays(3)->toDateString(),
        'period' => 'manha',
    ]);
    $set->songs()->attach($song->id, ['order' => 1, 'key_used' => 'G']);
    WorshipSetAssignment::create([
        'worship_set_id' => $set->id,
        'worship_function_id' => $vocalFunction->id,
        'member_id' => $member->id,
        'order' => 1,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('proximoCulto.id', $set->id)
            ->where('proximoCulto.songs_count', 1)
            ->where('proximoCulto.vocal', 'Maria Vocal')
            ->where('proximoCulto.escala_definida', true));
});
