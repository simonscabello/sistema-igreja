<?php

use App\Models\Song;
use App\Models\WorshipSet;

beforeEach(function () {
    $this->user = userWithPermissions(['visualizar_escalas_louvor', 'gerenciar_escalas_louvor']);
});

it('exibe o formulário de criação com select avançado de músicas', function () {
    Song::create(['name' => 'Ao Único', 'key' => 'G']);

    $response = $this->actingAs($this->user)->get(route('worship-sets.create'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('WorshipSets/Create')
        ->has('songs'));
});

it('cadastra repertório preservando a ordem das músicas enviadas', function () {
    $firstSong = Song::create(['name' => 'Primeira']);
    $secondSong = Song::create(['name' => 'Segunda']);

    $response = $this->actingAs($this->user)->post(route('worship-sets.store'), [
        'singer' => 'João',
        'preacher' => 'Pedro',
        'date' => '2026-09-01',
        'period' => 'manha',
        'songs' => [$secondSong->id, $firstSong->id],
        'song_keys' => [
            $secondSong->id => 'A',
            $firstSong->id => 'C',
        ],
    ]);

    $response->assertRedirect(route('worship-sets.index'));

    $worshipSet = WorshipSet::first();
    $orderedIds = $worshipSet->songs->pluck('id')->all();
    expect($orderedIds)->toBe([$secondSong->id, $firstSong->id]);
    expect($worshipSet->songs->first()->pivot->key_used)->toBe('A');
});

it('exige pelo menos uma música no repertório', function () {
    $response = $this->actingAs($this->user)->post(route('worship-sets.store'), [
        'singer' => 'João',
        'preacher' => 'Pedro',
        'date' => '2026-09-01',
        'period' => 'manha',
    ]);

    $response->assertSessionHasErrors('songs');
});
