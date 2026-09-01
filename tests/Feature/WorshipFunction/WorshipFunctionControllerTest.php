<?php

use App\Models\Member;
use App\Models\WorshipFunction;
use App\Models\WorshipSet;
use App\Models\WorshipSetAssignment;

beforeEach(function () {
    $this->user = userWithPermissions(['visualizar_escalas_louvor', 'gerenciar_escalas_louvor']);
});

it('exibe a listagem de funções', function () {
    WorshipFunction::factory()->create(['name' => 'Vocal', 'slug' => 'vocal']);

    $this->actingAs($this->user)
        ->get(route('worship-functions.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('WorshipFunctions/Index')
            ->has('worshipFunctions'));
});

it('cadastra função com membros do roster', function () {
    $members = Member::factory()->count(2)->create();

    $response = $this->actingAs($this->user)->post(route('worship-functions.store'), [
        'name' => 'Vocal',
        'sort_order' => 1,
        'is_active' => true,
        'members' => $members->pluck('id')->all(),
    ]);

    $response->assertRedirect(route('worship-functions.index'));

    $function = WorshipFunction::where('slug', 'vocal')->first();
    expect($function)->not->toBeNull();
    expect($function->members->pluck('id')->sort()->values()->all())
        ->toBe($members->pluck('id')->sort()->values()->all());
});

it('bloqueia exclusão de função usada em culto', function () {
    $function = WorshipFunction::factory()->create(['name' => 'Vocal', 'slug' => 'vocal']);
    $member = Member::factory()->create();
    $function->members()->attach($member);

    $worshipSet = WorshipSet::create([
        'date' => '2026-09-01',
        'period' => 'manha',
    ]);

    WorshipSetAssignment::create([
        'worship_set_id' => $worshipSet->id,
        'worship_function_id' => $function->id,
        'member_id' => $member->id,
        'order' => 1,
    ]);

    $this->actingAs($this->user)
        ->delete(route('worship-functions.destroy', $function))
        ->assertRedirect(route('worship-functions.index'))
        ->assertSessionHas('error');

    expect(WorshipFunction::find($function->id))->not->toBeNull();
});

it('nega acesso sem permissão', function () {
    $user = userWithPermissions([]);

    $this->actingAs($user)
        ->get(route('worship-functions.index'))
        ->assertForbidden();
});
