<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateVisitorRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'age_group' => 'nullable|in:crianca_adolescente,jovem,adulto,idoso',
            'gender' => 'nullable|in:feminino,masculino',
            'wants_contact' => 'nullable|boolean',
            'visit_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'full_address' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'mobile' => 'celular',
            'age_group' => 'faixa etária',
            'gender' => 'gênero',
            'wants_contact' => 'deseja ser contactado',
            'visit_date' => 'data da visita',
            'notes' => 'observações',
            'full_address' => 'endereço completo',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'mobile.string' => 'O celular deve ser um texto.',
            'mobile.max' => 'O celular não pode ter mais de 20 caracteres.',
            'age_group.in' => 'A faixa etária deve ser criança / adolescente, jovem, adulto ou idoso.',
            'gender.in' => 'O gênero deve ser feminino ou masculino.',
            'wants_contact.boolean' => 'O campo deseja ser contactado deve ser verdadeiro ou falso.',
            'visit_date.date' => 'A data da visita deve ser uma data válida.',
            'notes.string' => 'As observações devem ser um texto.',
            'full_address.string' => 'O endereço completo deve ser um texto.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('visit_date') && $this->get('visit_date')) {
            try {
                $data = Carbon::createFromFormat('d/m/Y', $this->get('visit_date'))->format('Y-m-d');
                $this->merge(['visit_date' => $data]);
            } catch (\Exception $e) {
                // Se não conseguir converter, mantém o valor original
            }
        }

        if ($this->has('wants_contact')) {
            $this->merge(['wants_contact' => $this->boolean('wants_contact')]);
        }
    }
}
