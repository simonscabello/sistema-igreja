<?php

use App\Models\Member;
use App\Models\Department;

describe('Member Model - Feature Tests', function () {
    it('pode ser criado com dados válidos', function () {
        $memberData = [
            'full_name' => 'João Silva',
            'email' => 'joao@example.com',
            'mobile' => '(11) 99999-9999',
            'gender' => 'Masculino',
            'marital_status' => 'Casado',
            'birth_date' => '1990-01-01',
            'zip_code' => '01234-567',
        ];

        $member = Member::create($memberData);

        expect($member->full_name)->toBe('João Silva');
        expect($member->email)->toBe('joao@example.com');
        expect($member->gender)->toBe('Masculino');
        expect($member->marital_status)->toBe('Casado');
    });

    it('converte datas para Carbon instances', function () {
        $member = Member::factory()->create([
            'birth_date' => '1990-01-01',
            'baptism_date' => '2000-01-01',
            'admission_date' => '2010-01-01',
            'wedding_date' => '2015-01-01',
        ]);

        expect($member->birth_date)->toBeInstanceOf(Carbon\Carbon::class);
        expect($member->baptism_date)->toBeInstanceOf(Carbon\Carbon::class);
        expect($member->admission_date)->toBeInstanceOf(Carbon\Carbon::class);
        expect($member->wedding_date)->toBeInstanceOf(Carbon\Carbon::class);
    });

    it('pode ter relacionamento com departamentos responsável', function () {
        $member = Member::factory()->create();
        $department = Department::factory()->create();

        $member->responsibleDepartments()->attach($department);

        expect($member->responsibleDepartments)->toHaveCount(1);
        expect($member->responsibleDepartments->first())->toBeInstanceOf(Department::class);
        expect($member->responsibleDepartments->first()->id)->toBe($department->id);
    });

    it('pode ter relacionamento com departamentos membro', function () {
        $member = Member::factory()->create();
        $department = Department::factory()->create();

        $member->departments()->attach($department);

        expect($member->departments)->toHaveCount(1);
        expect($member->departments->first())->toBeInstanceOf(Department::class);
        expect($member->departments->first()->id)->toBe($department->id);
    });

    it('retorna todos os departamentos através do atributo getAllDepartments', function () {
        $member = Member::factory()->create();
        $responsibleDept = Department::factory()->create(['title' => 'Responsável']);
        $memberDept = Department::factory()->create(['title' => 'Membro']);

        $member->responsibleDepartments()->attach($responsibleDept);
        $member->departments()->attach($memberDept);

        $allDepartments = $member->getAllDepartmentsAttribute();

        expect($allDepartments)->toHaveCount(2);
        expect($allDepartments->pluck('title')->toArray())->toContain('Responsável');
        expect($allDepartments->pluck('title')->toArray())->toContain('Membro');
    });
});

describe('Member Factory', function () {
    it('cria membros com dados válidos', function () {
        $member = Member::factory()->create();

        expect($member->full_name)->not->toBeEmpty();
        expect($member->mobile)->not->toBeEmpty();
        expect($member->gender)->toBeIn(['Masculino', 'Feminino', 'Outro']);
        expect($member->zip_code)->not->toBeEmpty();
    });

    it('cria membros masculinos', function () {
        $member = Member::factory()->male()->create();

        expect($member->gender)->toBe('Masculino');
    });

    it('cria membros femininos', function () {
        $member = Member::factory()->female()->create();

        expect($member->gender)->toBe('Feminino');
    });

    it('cria membros casados', function () {
        $member = Member::factory()->married()->create();

        expect($member->marital_status)->toBe('Casado');
    });

    it('cria membros solteiros', function () {
        $member = Member::factory()->single()->create();

        expect($member->marital_status)->toBe('Solteiro');
    });
});
