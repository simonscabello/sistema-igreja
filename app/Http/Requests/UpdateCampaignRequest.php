<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateCampaignRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'goal_amount' => ['required', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:ativo,encerrado,cancelada'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'description' => 'descrição',
            'goal_amount' => 'meta',
            'start_date' => 'data de início',
            'end_date' => 'data de término',
            'status' => 'status',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'description.string' => 'A descrição deve ser um texto.',
            'goal_amount.required' => 'A meta é obrigatória.',
            'goal_amount.numeric' => 'A meta deve ser um número.',
            'goal_amount.min' => 'A meta deve ser maior ou igual a zero.',
            'start_date.date' => 'A data de início deve ser uma data válida.',
            'end_date.date' => 'A data de término deve ser uma data válida.',
            'end_date.after_or_equal' => 'A data de término deve ser igual ou posterior à data de início.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser ativo, encerrado ou cancelada.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('goal_amount')) {
            $valor = str_replace(['.', ','], ['', '.'], $this->goal_amount);
            $this->merge([
                'goal_amount' => floatval($valor)
            ]);
        }

        if ($this->has('start_date')) {
            $data = Carbon::createFromFormat('d/m/Y', $this->start_date)->format('Y-m-d');
            $this->merge([
                'start_date' => $data
            ]);
        }

        if ($this->has('end_date')) {
            $data = Carbon::createFromFormat('d/m/Y', $this->end_date)->format('Y-m-d');
            $this->merge([
                'end_date' => $data
            ]);
        }
    }
}
