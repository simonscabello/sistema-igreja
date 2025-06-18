<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinancialTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'financial_category_id' => ['required', 'exists:financial_categories,id'],
            'type' => ['required', 'in:entrada,saida'],
            'amount' => ['required', 'numeric', 'min:0'],
            'action_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'financial_category_id' => 'categoria',
            'type' => 'tipo',
            'amount' => 'valor',
            'action_date' => 'data da ação',
            'description' => 'descrição',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('amount')) {
            $valor = str_replace(['.', ','], ['', '.'], $this->amount);
            $this->merge([
                'amount' => floatval($valor)
            ]);
        }
    }
}
