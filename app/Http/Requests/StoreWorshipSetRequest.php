<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorshipSetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'singer' => 'required|string|max:255',
            'preacher' => 'required|string|max:255',
            'songs' => 'required|array|min:1',
            'songs.*' => 'exists:songs,id',
            'date' => 'required|date',
            'period' => 'required|in:manha,noite',
            'order_notes' => 'nullable|string',
            'observations' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'singer' => 'cantor',
            'preacher' => 'ministro',
            'songs' => 'músicas',
            'songs.*' => 'música',
            'date' => 'data',
            'period' => 'período',
            'order_notes' => 'notas de ordem',
            'observations' => 'observações',
        ];
    }

    public function messages(): array
    {
        return [
            'singer.required' => 'O nome do cantor é obrigatório.',
            'preacher.required' => 'O nome do ministro é obrigatório.',
            'songs.required' => 'Pelo menos uma música deve ser selecionada.',
            'songs.min' => 'Pelo menos uma música deve ser selecionada.',
            'songs.*.exists' => 'Uma das músicas selecionadas não existe.',
            'date.required' => 'A data é obrigatória.',
            'date.date' => 'A data deve ser válida.',
            'period.required' => 'O período é obrigatório.',
            'period.in' => 'O período deve ser manhã ou noite.',
        ];
    }
}
