<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

abstract class WorshipSetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function worshipSetRules(): array
    {
        return [
            'songs' => 'required|array|min:1',
            'songs.*' => 'exists:songs,id',
            'date' => 'required|date',
            'period' => 'required|in:manha,noite',
            'order_notes' => 'nullable|string',
            'observations' => 'nullable|string',
            'assignments' => 'nullable|array',
            'assignments.*' => 'nullable|array',
            'assignments.*.*' => 'exists:members,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'songs' => 'músicas',
            'songs.*' => 'música',
            'date' => 'data',
            'period' => 'período',
            'order_notes' => 'notas de ordem',
            'observations' => 'observações',
            'assignments' => 'escala',
            'assignments.*' => 'função da escala',
            'assignments.*.*' => 'membro da escala',
        ];
    }

    public function messages(): array
    {
        return [
            'songs.required' => 'Pelo menos uma música deve ser selecionada.',
            'songs.min' => 'Pelo menos uma música deve ser selecionada.',
            'songs.*.exists' => 'Uma das músicas selecionadas não existe.',
            'date.required' => 'A data é obrigatória.',
            'date.date' => 'A data deve ser válida.',
            'period.required' => 'O período é obrigatório.',
            'period.in' => 'O período deve ser manhã ou noite.',
            'assignments.*.*.exists' => 'O membro selecionado não existe.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $assignments = $this->input('assignments', []);

            if (! is_array($assignments)) {
                return;
            }

            foreach ($assignments as $functionId => $memberIds) {
                if (! is_array($memberIds) || $memberIds === []) {
                    continue;
                }

                $rosterMemberIds = DB::table('member_worship_function')
                    ->where('worship_function_id', $functionId)
                    ->pluck('member_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                foreach ($memberIds as $memberId) {
                    if (! in_array((int) $memberId, $rosterMemberIds, true)) {
                        $validator->errors()->add(
                            "assignments.{$functionId}",
                            'Um dos membros selecionados não está cadastrado nesta função.'
                        );

                        return;
                    }
                }
            }
        });
    }
}
