<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorshipSetRequest extends FormRequest
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
            'used_keys' => 'nullable|string|max:255',
            'date' => 'required|date',
            'period' => 'required|in:manha,noite',
            'order_notes' => 'nullable|string',
            'observations' => 'nullable|string',
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