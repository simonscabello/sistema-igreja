<?php

use App\Models\Member;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = userWithPermissions([
        'visualizar_membros',
        'criar_membros',
        'editar_membros',
        'excluir_membros',
    ]);
    $this->member = Member::factory()->create();
});

describe('MemberController', function () {
    describe('GET /members', function () {
        it('exibe lista de membros', function () {
            $response = $this->actingAs($this->user)->get(route('members.index'));

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page
                ->component('Members/Index')
                ->has('members'));
        });

        it('filtra membros por busca', function () {
            $member1 = Member::factory()->create(['full_name' => 'João Silva']);
            $member2 = Member::factory()->create(['full_name' => 'Maria Santos']);

            $response = $this->actingAs($this->user)
                ->get(route('members.index', ['search' => 'João']));

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page
                ->where('members.data', fn ($data) => collect($data)->pluck('id')->contains($member1->id)
                    && ! collect($data)->pluck('id')->contains($member2->id)));
        });

        it('busca por email', function () {
            $member = Member::factory()->create(['email' => 'teste@example.com']);

            $response = $this->actingAs($this->user)
                ->get(route('members.index', ['search' => 'teste@example.com']));

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page
                ->where('members.data', fn ($data) => collect($data)->pluck('id')->contains($member->id)));
        });

        it('busca por celular', function () {
            $member = Member::factory()->create(['mobile' => '11999999999']);

            $response = $this->actingAs($this->user)
                ->get(route('members.index', ['search' => '11999999999']));

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page
                ->where('members.data', fn ($data) => collect($data)->pluck('id')->contains($member->id)));
        });

        it('busca por cidade', function () {
            $member = Member::factory()->create(['city' => 'São Paulo']);

            $response = $this->actingAs($this->user)
                ->get(route('members.index', ['search' => 'São Paulo']));

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page
                ->where('members.data', fn ($data) => collect($data)->pluck('id')->contains($member->id)));
        });
    });

    describe('GET /members/create', function () {
        it('exibe formulário de criação', function () {
            $response = $this->actingAs($this->user)->get(route('members.create'));

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page->component('Members/Create'));
        });
    });

    describe('POST /members', function () {
        it('cria novo membro com dados válidos', function () {
            $memberData = [
                'full_name' => 'João Silva',
                'email' => 'joao@example.com',
                'mobile' => '(11) 99999-9999',
                'gender' => 'Masculino',
                'marital_status' => 'Casado',
                'birth_date' => '1990-01-01',
                'zip_code' => '01234-567',
            ];

            $response = $this->actingAs($this->user)
                ->post(route('members.store'), $memberData);

            $response->assertRedirect(route('members.index'));
            $response->assertSessionHas('success', 'Membro cadastrado com sucesso.');

            $this->assertDatabaseHas('members', [
                'full_name' => 'João Silva',
                'email' => 'joao@example.com',
                'mobile' => '(11) 99999-9999',
            ]);
        });

        it('cria membro com foto de perfil', function () {
            Storage::fake('public');

            $file = UploadedFile::fake()->image('foto.jpg');

            $memberData = [
                'full_name' => 'João Silva',
                'mobile' => '(11) 99999-9999',
                'gender' => 'Masculino',
                'birth_date' => '1990-01-01',
                'zip_code' => '01234-567',
                'foto_perfil' => $file,
            ];

            $response = $this->actingAs($this->user)
                ->post(route('members.store'), $memberData);

            $response->assertRedirect(route('members.index'));
            $response->assertSessionHas('success', 'Membro cadastrado com sucesso.');

            $member = Member::where('full_name', 'João Silva')->first();
            expect($member)->not->toBeNull();
        });

        it('valida dados obrigatórios', function () {
            $response = $this->actingAs($this->user)
                ->post(route('members.store'), []);

            $response->assertSessionHasErrors([
                'full_name',
                'mobile',
                'gender',
                'birth_date',
                'zip_code',
            ]);
        });

        it('valida formato de email', function () {
            $memberData = [
                'full_name' => 'João Silva',
                'email' => 'email-invalido',
                'mobile' => '(11) 99999-9999',
                'gender' => 'Masculino',
                'birth_date' => '1990-01-01',
                'zip_code' => '01234-567',
            ];

            $response = $this->actingAs($this->user)
                ->post(route('members.store'), $memberData);

            $response->assertSessionHasErrors(['email']);
        });

        it('valida valores de gênero', function () {
            $memberData = [
                'full_name' => 'João Silva',
                'mobile' => '(11) 99999-9999',
                'gender' => 'Invalido',
                'birth_date' => '1990-01-01',
                'zip_code' => '01234-567',
            ];

            $response = $this->actingAs($this->user)
                ->post(route('members.store'), $memberData);

            $response->assertSessionHasErrors(['gender']);
        });
    });

    describe('GET /members/{member}', function () {
        it('exibe detalhes do membro', function () {
            $response = $this->actingAs($this->user)
                ->get(route('members.show', $this->member));

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page
                ->component('Members/Show')
                ->where('member.id', $this->member->id));
        });
    });

    describe('GET /members/{member}/edit', function () {
        it('exibe formulário de edição', function () {
            $response = $this->actingAs($this->user)
                ->get(route('members.edit', $this->member));

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page
                ->component('Members/Edit')
                ->where('member.id', $this->member->id));
        });
    });

    describe('PUT /members/{member}', function () {
        it('atualiza membro com dados válidos', function () {
            $updateData = [
                'full_name' => 'João Silva Atualizado',
                'email' => 'joao.atualizado@example.com',
                'mobile' => '(11) 88888-8888',
                'gender' => 'Masculino',
                'marital_status' => 'Divorciado',
                'birth_date' => '1990-01-01',
                'zip_code' => '01234-567',
            ];

            $response = $this->actingAs($this->user)
                ->put(route('members.update', $this->member), $updateData);

            $response->assertRedirect(route('members.index'));
            $response->assertSessionHas('success', 'Membro atualizado com sucesso.');

            $this->assertDatabaseHas('members', [
                'id' => $this->member->id,
                'full_name' => 'João Silva Atualizado',
                'email' => 'joao.atualizado@example.com',
                'mobile' => '(11) 88888-8888',
                'marital_status' => 'Divorciado',
            ]);
        });

        it('atualiza foto de perfil', function () {
            Storage::fake('public');

            $file = UploadedFile::fake()->image('nova-foto.jpg');

            $updateData = [
                'full_name' => $this->member->full_name,
                'mobile' => $this->member->mobile,
                'gender' => $this->member->gender,
                'birth_date' => $this->member->birth_date,
                'zip_code' => '01234-567',
                'foto_perfil' => $file,
            ];

            $response = $this->actingAs($this->user)
                ->put(route('members.update', $this->member), $updateData);

            $response->assertRedirect(route('members.index'));
            $response->assertSessionHas('success', 'Membro atualizado com sucesso.');
        });

        it('valida dados obrigatórios na atualização', function () {
            $response = $this->actingAs($this->user)
                ->put(route('members.update', $this->member), []);

            $response->assertSessionHasErrors([
                'full_name',
                'mobile',
                'gender',
                'birth_date',
                'zip_code',
            ]);
        });
    });

    describe('DELETE /members/{member}', function () {
        it('exclui membro', function () {
            $response = $this->actingAs($this->user)
                ->delete(route('members.destroy', $this->member));

            $response->assertRedirect(route('members.index'));
            $response->assertSessionHas('success', 'Membro excluído com sucesso.');

            $this->assertDatabaseMissing('members', [
                'id' => $this->member->id,
            ]);
        });
    });
});
