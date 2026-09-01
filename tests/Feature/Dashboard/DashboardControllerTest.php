<?php

use App\Models\Song;
use App\Models\WorshipSet;

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

    $set = WorshipSet::create([
        'singer' => 'João',
        'preacher' => 'Pastor',
        'date' => now()->addDays(3)->toDateString(),
        'period' => 'manha',
    ]);
    $set->songs()->attach($song->id, ['order' => 1, 'key_used' => 'G']);

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('proximoCulto.id', $set->id)
            ->where('proximoCulto.songs_count', 1));
});
