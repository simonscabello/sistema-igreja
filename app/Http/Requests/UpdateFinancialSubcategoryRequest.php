<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinancialSubcategoryRequest extends FormRequest
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
            'financial_category_id' => 'required|exists:financial_categories,id',
            'name' => 'required|string|max:255',
            'active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'financial_category_id.required' => 'A categoria é obrigatória.',
            'financial_category_id.exists' => 'A categoria selecionada não existe.',
            'name.required' => 'O nome da subcategoria é obrigatório.',
            'name.string' => 'O nome da subcategoria deve ser um texto.',
            'name.max' => 'O nome da subcategoria não pode ter mais de 255 caracteres.',
        ];
    }
}
