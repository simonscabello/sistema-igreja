<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateWorshipFunctionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $worshipFunction = $this->route('worship_function');

        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('worship_functions', 'slug')->ignore($worshipFunction?->id),
            ],
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
            'members' => 'nullable|array',
            'members.*' => 'exists:members,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'slug' => 'identificador',
            'sort_order' => 'ordem',
            'is_active' => 'ativo',
            'members' => 'membros',
            'members.*' => 'membro',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da função é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'slug.unique' => 'Já existe uma função com este identificador.',
            'is_active.required' => 'O status ativo é obrigatório.',
            'is_active.boolean' => 'O status ativo deve ser verdadeiro ou falso.',
            'members.*.exists' => 'O membro selecionado não existe.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('slug') && $this->filled('name')) {
            $this->merge([
                'slug' => Str::slug($this->input('name')),
            ]);
        }
    }
}
