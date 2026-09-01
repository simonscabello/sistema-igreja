<?php

use App\Models\Department;
use App\Models\Member;

beforeEach(function () {
    $this->user = userWithPermissions(['visualizar_departamentos', 'gerenciar_departamentos']);
});

it('exibe o formulário de criação com select avançado de membros', function () {
    Member::factory()->create(['full_name' => 'Ana Silva']);

    $response = $this->actingAs($this->user)->get(route('departments.create'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Departments/Create')
        ->has('memberOptions')
        ->where('memberOptions', fn ($options) => collect($options)->contains(fn ($option) => $option['name'] === 'Ana Silva')));
});

it('cadastra departamento com líderes e membros distintos', function () {
    $leader = Member::factory()->create();
    $member = Member::factory()->create();

    $response = $this->actingAs($this->user)->post(route('departments.store'), [
        'title' => 'Louvor',
        'description' => 'Ministério de louvor',
        'is_active' => '1',
        'responsible_members' => [$leader->id],
        'members' => [$member->id],
    ]);

    $response->assertRedirect(route('departments.index'));
    $this->assertDatabaseHas('departments', ['title' => 'Louvor']);

    $department = Department::where('title', 'Louvor')->first();
    expect($department->responsibleMembers->pluck('id')->all())->toContain($leader->id);
    expect($department->members->pluck('id')->all())->toContain($member->id);
});

it('rejeita o mesmo membro como líder e membro', function () {
    $member = Member::factory()->create();

    $response = $this->actingAs($this->user)->post(route('departments.store'), [
        'title' => 'Infantil',
        'is_active' => '1',
        'responsible_members' => [$member->id],
        'members' => [$member->id],
    ]);

    $response->assertSessionHasErrors('members');
    $this->assertDatabaseMissing('departments', ['title' => 'Infantil']);
});

it('atualiza líderes e membros do departamento', function () {
    $department = Department::factory()->create(['title' => 'Mídia']);
    $oldLeader = Member::factory()->create();
    $newLeader = Member::factory()->create();
    $member = Member::factory()->create();
    $department->responsibleMembers()->attach($oldLeader);

    $response = $this->actingAs($this->user)->put(route('departments.update', $department), [
        'title' => 'Mídia',
        'is_active' => '1',
        'responsible_members' => [$newLeader->id],
        'members' => [$member->id],
    ]);

    $response->assertRedirect(route('departments.index'));
    $department->refresh();
    expect($department->responsibleMembers->pluck('id')->all())->toBe([$newLeader->id]);
    expect($department->members->pluck('id')->all())->toBe([$member->id]);
});
