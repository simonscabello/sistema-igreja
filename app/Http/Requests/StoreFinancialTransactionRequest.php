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
            'campaign_id' => ['nullable', 'exists:campaigns,id'],
            'type' => ['required', 'in:entrada,saida'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'action_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB max
        ];
    }

    public function attributes(): array
    {
        return [
            'financial_subcategory_id' => 'subcategoria',
            'campaign_id' => 'campanha',
            'type' => 'tipo',
            'amount' => 'valor',
            'action_date' => 'data da ação',
            'description' => 'descrição',
            'attachment' => 'anexo',
        ];
    }

    public function messages(): array
    {
        return [
            'financial_subcategory_id.required' => 'A subcategoria é obrigatória.',
            'financial_subcategory_id.exists' => 'A subcategoria selecionada não existe.',
            'campaign_id.exists' => 'A campanha selecionada não existe.',
            'type.required' => 'O tipo é obrigatório.',
            'type.in' => 'O tipo deve ser entrada ou saida.',
            'amount.required' => 'O valor é obrigatório.',
            'amount.numeric' => 'O valor deve ser um número válido.',
            'amount.min' => 'O valor deve ser maior que zero.',
            'action_date.required' => 'A data da ação é obrigatória.',
            'action_date.date' => 'A data da ação deve ser uma data válida.',
            'description.string' => 'A descrição deve ser um texto.',
            'attachment.file' => 'O anexo deve ser um arquivo válido.',
            'attachment.mimes' => 'O anexo deve ser um arquivo PDF, JPG, JPEG ou PNG.',
            'attachment.max' => 'O anexo não pode ter mais de 10MB.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('amount')) {
            $valor = $this->amount;
            
            // Remove caracteres não numéricos exceto vírgula e ponto
            $valor = preg_replace('/[^0-9,.]/', '', $valor);
            
            // Se há múltiplas vírgulas ou pontos, mantém apenas o último
            $valor = preg_replace('/[,.]/', '.', $valor);
            $valor = preg_replace('/\.(?=.*\.)/', '', $valor);
            
            // Converte para float
            $valor = floatval($valor);
            
            $this->merge([
                'amount' => $valor
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
