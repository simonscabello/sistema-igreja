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

    public function messages(): array
    {
        return [
            'full_name.required' => 'O nome completo é obrigatório.',
            'full_name.string' => 'O nome completo deve ser um texto.',
            'full_name.max' => 'O nome completo não pode ter mais de 255 caracteres.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            'phone.string' => 'O telefone deve ser um texto.',
            'phone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'mobile.required' => 'O celular é obrigatório.',
            'mobile.string' => 'O celular deve ser um texto.',
            'mobile.max' => 'O celular não pode ter mais de 20 caracteres.',
            'gender.required' => 'O gênero é obrigatório.',
            'gender.in' => 'O gênero deve ser Masculino, Feminino ou Outro.',
            'marital_status.in' => 'O estado civil deve ser Solteiro, Casado, Divorciado ou Viúvo.',
            'birth_date.required' => 'A data de nascimento é obrigatória.',
            'birth_date.date' => 'A data de nascimento deve ser uma data válida.',
            'baptism_date.date' => 'A data de batismo deve ser uma data válida.',
            'admission_date.date' => 'A data de admissão deve ser uma data válida.',
            'wedding_date.date' => 'A data de casamento deve ser uma data válida.',
            'zip_code.required' => 'O CEP é obrigatório.',
            'zip_code.string' => 'O CEP deve ser um texto.',
            'zip_code.max' => 'O CEP não pode ter mais de 9 caracteres.',
            'street.string' => 'A rua deve ser um texto.',
            'street.max' => 'A rua não pode ter mais de 255 caracteres.',
            'neighborhood.string' => 'O bairro deve ser um texto.',
            'neighborhood.max' => 'O bairro não pode ter mais de 255 caracteres.',
            'city.string' => 'A cidade deve ser um texto.',
            'city.max' => 'A cidade não pode ter mais de 255 caracteres.',
            'state.string' => 'O estado deve ser um texto.',
            'state.max' => 'O estado não pode ter mais de 2 caracteres.',
            'number.string' => 'O número deve ser um texto.',
            'number.max' => 'O número não pode ter mais de 20 caracteres.',
            'complement.string' => 'O complemento deve ser um texto.',
            'complement.max' => 'O complemento não pode ter mais de 255 caracteres.',
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
