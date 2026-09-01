<?php

use App\Models\Song;
use App\Models\Tag;

beforeEach(function () {
    $this->user = userWithPermissions(['visualizar_musicas', 'gerenciar_musicas']);
});

it('exibe o formulário de criação com select avançado de tags', function () {
    Tag::create(['name' => 'adoração']);

    $response = $this->actingAs($this->user)->get(route('songs.create'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Songs/Create')
        ->has('tags'));
});

it('cadastra música com tags em array, incluindo tag nova', function () {
    Tag::create(['name' => 'adoração']);

    $response = $this->actingAs($this->user)->post(route('songs.store'), [
        'name' => 'Grande é o Senhor',
        'tags' => ['adoração', 'louvor'],
    ]);

    $response->assertRedirect(route('songs.index'));
    $this->assertDatabaseHas('songs', ['name' => 'Grande é o Senhor']);
    $this->assertDatabaseHas('tags', ['name' => 'louvor']);

    $song = Song::where('name', 'Grande é o Senhor')->first();
    expect($song->tags->pluck('name')->all())->toEqualCanonicalizing(['adoração', 'louvor']);
});
