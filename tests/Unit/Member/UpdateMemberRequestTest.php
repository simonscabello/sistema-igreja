<?php

use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;

describe('UpdateMemberRequest', function () {
    it('autoriza acesso por padrão', function () {
        $request = new UpdateMemberRequest();

        expect($request->authorize())->toBeTrue();
    });

    it('valida dados obrigatórios', function () {
        $request = new UpdateMemberRequest();
        $rules = $request->rules();

        expect($rules['full_name'])->toContain('required');
        expect($rules['mobile'])->toContain('required');
        expect($rules['gender'])->toContain('required');
        expect($rules['birth_date'])->toContain('required');
        expect($rules['zip_code'])->toContain('required');
    });

    it('valida formato de email', function () {
        $request = new UpdateMemberRequest();
        $rules = $request->rules();

        expect($rules['email'])->toContain('nullable');
        expect($rules['email'])->toContain('email');
        expect($rules['email'])->toContain('max:255');
    });

    it('valida valores de gênero', function () {
        $request = new UpdateMemberRequest();
        $rules = $request->rules();

        expect($rules['gender'])->toContain('in:Masculino,Feminino,Outro');
    });

    it('valida valores de estado civil', function () {
        $request = new UpdateMemberRequest();
        $rules = $request->rules();

        expect($rules['marital_status'])->toContain('nullable');
        expect($rules['marital_status'])->toContain('in:Solteiro,Casado,Divorciado,Viúvo');
    });

    it('valida formato de datas', function () {
        $request = new UpdateMemberRequest();
        $rules = $request->rules();

        expect($rules['birth_date'])->toContain('date');
        expect($rules['baptism_date'])->toContain('nullable');
        expect($rules['baptism_date'])->toContain('date');
        expect($rules['admission_date'])->toContain('nullable');
        expect($rules['admission_date'])->toContain('date');
        expect($rules['wedding_date'])->toContain('nullable');
        expect($rules['wedding_date'])->toContain('date');
    });

    it('valida foto de perfil', function () {
        $request = new UpdateMemberRequest();
        $rules = $request->rules();

        expect($rules['foto_perfil'])->toContain('nullable');
        expect($rules['foto_perfil'])->toContain('image');
        expect($rules['foto_perfil'])->toContain('mimes:jpg,jpeg,png');
        expect($rules['foto_perfil'])->toContain('max:5120');
    });

    it('define atributos personalizados', function () {
        $request = new UpdateMemberRequest();
        $attributes = $request->attributes();

        expect($attributes['full_name'])->toBe('nome completo');
        expect($attributes['email'])->toBe('email');
        expect($attributes['phone'])->toBe('telefone');
        expect($attributes['mobile'])->toBe('celular');
        expect($attributes['gender'])->toBe('gênero');
        expect($attributes['marital_status'])->toBe('estado civil');
        expect($attributes['birth_date'])->toBe('data de nascimento');
        expect($attributes['baptism_date'])->toBe('data de batismo');
        expect($attributes['admission_date'])->toBe('data de admissão');
        expect($attributes['wedding_date'])->toBe('data de casamento');
        expect($attributes['zip_code'])->toBe('CEP');
        expect($attributes['street'])->toBe('rua');
        expect($attributes['neighborhood'])->toBe('bairro');
        expect($attributes['city'])->toBe('cidade');
        expect($attributes['state'])->toBe('estado');
        expect($attributes['number'])->toBe('número');
        expect($attributes['complement'])->toBe('complemento');
    });

    it('define mensagens personalizadas', function () {
        $request = new UpdateMemberRequest();
        $messages = $request->messages();

        expect($messages['full_name.required'])->toBe('O nome completo é obrigatório.');
        expect($messages['full_name.string'])->toBe('O nome completo deve ser um texto.');
        expect($messages['full_name.max'])->toBe('O nome completo não pode ter mais de 255 caracteres.');
        expect($messages['email.email'])->toBe('O email deve ser um endereço válido.');
        expect($messages['mobile.required'])->toBe('O celular é obrigatório.');
        expect($messages['gender.required'])->toBe('O gênero é obrigatório.');
        expect($messages['gender.in'])->toBe('O gênero deve ser Masculino, Feminino ou Outro.');
        expect($messages['marital_status.in'])->toBe('O estado civil deve ser Solteiro, Casado, Divorciado ou Viúvo.');
        expect($messages['birth_date.required'])->toBe('A data de nascimento é obrigatória.');
        expect($messages['birth_date.date'])->toBe('A data de nascimento deve ser uma data válida.');
        expect($messages['zip_code.required'])->toBe('O CEP é obrigatório.');
    });

    it('tem método prepareForValidation', function () {
        $request = new UpdateMemberRequest();

        expect(method_exists($request, 'prepareForValidation'))->toBeTrue();
    });

    it('tem as mesmas regras que StoreMemberRequest', function () {
        $storeRequest = new \App\Http\Requests\StoreMemberRequest();
        $updateRequest = new UpdateMemberRequest();

        expect($updateRequest->rules())->toBe($storeRequest->rules());
    });

    it('tem as mesmas mensagens que StoreMemberRequest', function () {
        $storeRequest = new \App\Http\Requests\StoreMemberRequest();
        $updateRequest = new UpdateMemberRequest();

        expect($updateRequest->messages())->toBe($storeRequest->messages());
    });

    it('tem os mesmos atributos que StoreMemberRequest', function () {
        $storeRequest = new \App\Http\Requests\StoreMemberRequest();
        $updateRequest = new UpdateMemberRequest();

        expect($updateRequest->attributes())->toBe($storeRequest->attributes());
    });
});
