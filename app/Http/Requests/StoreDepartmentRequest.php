<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Member;

class StoreDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'responsible_members' => 'nullable|array',
            'responsible_members.*' => 'exists:members,id',
            'members' => 'nullable|array',
            'members.*' => 'exists:members,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'description' => 'descrição',
            'is_active' => 'ativo',
            'responsible_members' => 'membros responsáveis',
            'responsible_members.*' => 'membro responsável',
            'members' => 'membros',
            'members.*' => 'membro',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.string' => 'O título deve ser um texto.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'description.string' => 'A descrição deve ser um texto.',
            'is_active.required' => 'O status ativo é obrigatório.',
            'is_active.boolean' => 'O status ativo deve ser verdadeiro ou falso.',
            'responsible_members.array' => 'Os membros responsáveis devem ser uma lista.',
            'responsible_members.*.exists' => 'O membro responsável selecionado não existe.',
            'members.array' => 'Os membros devem ser uma lista.',
            'members.*.exists' => 'O membro selecionado não existe.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $responsibleMembers = $this->input('responsible_members', []);
            $members = $this->input('members', []);
            
            $intersection = array_intersect($responsibleMembers, $members);
            
            if (!empty($intersection)) {
                $validator->errors()->add('members', 'Os membros não podem estar na lista de responsáveis e na lista de membros ao mesmo tempo.');
            }
        });
    }
}
