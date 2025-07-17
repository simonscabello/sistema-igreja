<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSongRequest extends FormRequest
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
            'youtube_link' => 'nullable|url|max:255',
            'spotify_link' => 'nullable|url|max:255',
            'key' => 'nullable|string|max:10',
            'lyrics_link' => 'nullable|url|max:255',
            'chords_link' => 'nullable|url|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome da música',
            'youtube_link' => 'link do YouTube',
            'spotify_link' => 'link do Spotify',
            'key' => 'tonalidade',
            'lyrics_link' => 'link da letra',
            'chords_link' => 'link da cifra',
            'tags' => 'tags',
            'tags.*' => 'tag',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da música é obrigatório.',
            'youtube_link.url' => 'O link do YouTube deve ser uma URL válida.',
            'spotify_link.url' => 'O link do Spotify deve ser uma URL válida.',
            'lyrics_link.url' => 'O link da letra deve ser uma URL válida.',
            'chords_link.url' => 'O link da cifra deve ser uma URL válida.',
            'key.max' => 'A tonalidade deve ter no máximo 10 caracteres.',
        ];
    }
}
