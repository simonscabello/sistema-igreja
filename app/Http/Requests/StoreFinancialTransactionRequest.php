<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreFinancialTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'financial_subcategory_id' => ['required', 'exists:financial_subcategories,id'],
            'type' => ['required', 'in:entrada,saida'],
            'amount' => ['required', 'numeric', 'min:0'],
            'action_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'financial_subcategory_id' => 'subcategoria',
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

        if ($this->has('action_date')) {
            $data = Carbon::createFromFormat('d/m/Y', $this->action_date)->format('Y-m-d');
            $this->merge([
                'action_date' => $data
            ]);
        }
    }
}
