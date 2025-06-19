<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'required|string|max:20',
            'gender' => 'required|in:Masculino,Feminino,Outro',
            'marital_status' => 'nullable|in:Solteiro,Casado,Divorciado,Viúvo',
            'birth_date' => 'required|date',
            'baptism_date' => 'nullable|date',
            'admission_date' => 'nullable|date',
            'wedding_date' => 'nullable|date',
            'zip_code' => 'required|string|max:9',
            'street' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'nome completo',
            'email' => 'email',
            'phone' => 'telefone',
            'mobile' => 'celular',
            'gender' => 'gênero',
            'marital_status' => 'estado civil',
            'birth_date' => 'data de nascimento',
            'baptism_date' => 'data de batismo',
            'admission_date' => 'data de admissão',
            'wedding_date' => 'data de casamento',
            'zip_code' => 'CEP',
            'street' => 'rua',
            'neighborhood' => 'bairro',
            'city' => 'cidade',
            'state' => 'estado',
            'number' => 'número',
            'complement' => 'complemento',
        ];
    }

    protected function prepareForValidation()
    {
        $dateFields = ['birth_date', 'baptism_date', 'admission_date', 'wedding_date'];

        foreach ($dateFields as $field) {
            if ($this->has($field) && $this->get($field)) {
                try {
                    $data = Carbon::createFromFormat('d/m/Y', $this->get($field))->format('Y-m-d');
                    $this->merge([$field => $data]);
                } catch (\Exception $e) {
                    // Se não conseguir converter, mantém o valor original
                }
            }
        }
    }
}
