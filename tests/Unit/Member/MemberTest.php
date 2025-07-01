<?php

use App\Models\Member;

describe('Member Model', function () {
    it('define fillable attributes corretamente', function () {
        $member = new Member();

        expect($member->getFillable())->toContain('full_name');
        expect($member->getFillable())->toContain('email');
        expect($member->getFillable())->toContain('phone');
        expect($member->getFillable())->toContain('mobile');
        expect($member->getFillable())->toContain('gender');
        expect($member->getFillable())->toContain('marital_status');
        expect($member->getFillable())->toContain('birth_date');
        expect($member->getFillable())->toContain('baptism_date');
        expect($member->getFillable())->toContain('admission_date');
        expect($member->getFillable())->toContain('wedding_date');
        expect($member->getFillable())->toContain('zip_code');
        expect($member->getFillable())->toContain('street');
        expect($member->getFillable())->toContain('neighborhood');
        expect($member->getFillable())->toContain('city');
        expect($member->getFillable())->toContain('state');
        expect($member->getFillable())->toContain('number');
        expect($member->getFillable())->toContain('complement');
    });

    it('define casts corretamente', function () {
        $member = new Member();
        $casts = $member->getCasts();

        expect($casts['birth_date'])->toBe('date');
        expect($casts['baptism_date'])->toBe('date');
        expect($casts['admission_date'])->toBe('date');
        expect($casts['wedding_date'])->toBe('date');
    });

    it('usa o trait HasFiles', function () {
        $member = new Member();

        expect(method_exists($member, 'files'))->toBeTrue();
    });

    it('usa o trait HasFactory', function () {
        $member = new Member();

        expect(method_exists($member, 'factory'))->toBeTrue();
    });

    it('tem método getAllDepartmentsAttribute definido', function () {
        $member = new Member();

        expect(method_exists($member, 'getAllDepartmentsAttribute'))->toBeTrue();
    });

    it('tem método foto definido', function () {
        $member = new Member();

        expect(method_exists($member, 'foto'))->toBeTrue();
    });
});
