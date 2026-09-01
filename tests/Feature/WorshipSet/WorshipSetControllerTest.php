<?php

use App\Models\Member;
use App\Models\Song;
use App\Models\WorshipFunction;
use App\Models\WorshipSet;

beforeEach(function () {
    $this->user = userWithPermissions(['visualizar_escalas_louvor', 'gerenciar_escalas_louvor']);
});

it('exibe o formulário de criação com funções e músicas', function () {
    WorshipFunction::factory()->create(['name' => 'Vocal', 'slug' => 'vocal']);
    Song::create(['name' => 'Ao Único', 'key' => 'G']);

    $response = $this->actingAs($this->user)->get(route('worship-sets.create'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('WorshipSets/Create')
        ->has('songs')
        ->has('functions'));
});

it('cadastra culto preservando a ordem das músicas e a escala', function () {
    $firstSong = Song::create(['name' => 'Primeira']);
    $secondSong = Song::create(['name' => 'Segunda']);
    $vocalFunction = WorshipFunction::factory()->create(['name' => 'Vocal', 'slug' => 'vocal']);
    $memberOne = Member::factory()->create();
    $memberTwo = Member::factory()->create();
    $vocalFunction->members()->attach([$memberOne->id, $memberTwo->id]);

    $response = $this->actingAs($this->user)->post(route('worship-sets.store'), [
        'date' => '2026-09-01',
        'period' => 'manha',
        'songs' => [$secondSong->id, $firstSong->id],
        'song_keys' => [
            $secondSong->id => 'A',
            $firstSong->id => 'C',
        ],
        'assignments' => [
            $vocalFunction->id => [$memberTwo->id, $memberOne->id],
        ],
    ]);

    $response->assertRedirect(route('worship-sets.index'));

    $worshipSet = WorshipSet::first();
    $orderedIds = $worshipSet->songs->pluck('id')->all();
    expect($orderedIds)->toBe([$secondSong->id, $firstSong->id]);
    expect($worshipSet->songs->first()->pivot->key_used)->toBe('A');

    $assignmentMemberIds = $worshipSet->assignments()
        ->where('worship_function_id', $vocalFunction->id)
        ->orderBy('order')
        ->pluck('member_id')
        ->all();

    expect($assignmentMemberIds)->toBe([$memberTwo->id, $memberOne->id]);
});

it('rejeita membro fora do roster da função', function () {
    $song = Song::create(['name' => 'Primeira']);
    $vocalFunction = WorshipFunction::factory()->create(['name' => 'Vocal', 'slug' => 'vocal']);
    $rosterMember = Member::factory()->create();
    $outsiderMember = Member::factory()->create();
    $vocalFunction->members()->attach($rosterMember);

    $response = $this->actingAs($this->user)->post(route('worship-sets.store'), [
        'date' => '2026-09-01',
        'period' => 'manha',
        'songs' => [$song->id],
        'assignments' => [
            $vocalFunction->id => [$outsiderMember->id],
        ],
    ]);

    $response->assertSessionHasErrors("assignments.{$vocalFunction->id}");
});

it('exige pelo menos uma música no culto', function () {
    $response = $this->actingAs($this->user)->post(route('worship-sets.store'), [
        'date' => '2026-09-01',
        'period' => 'manha',
    ]);

    $response->assertSessionHasErrors('songs');
});
