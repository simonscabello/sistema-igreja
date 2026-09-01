<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorshipFunctionRequest;
use App\Http\Requests\UpdateWorshipFunctionRequest;
use App\Models\Member;
use App\Models\WorshipFunction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorshipFunctionController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('visualizar_escalas_louvor');

        $query = WorshipFunction::withCount('members');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $worshipFunctions = $query->orderBy('sort_order')->orderBy('name')->paginate(10);

        return Inertia::render('WorshipFunctions/Index', compact('worshipFunctions'));
    }

    public function create(): Response
    {
        $this->authorize('gerenciar_escalas_louvor');

        $memberOptions = $this->memberSelectOptions();

        return Inertia::render('WorshipFunctions/Create', compact('memberOptions'));
    }

    public function store(StoreWorshipFunctionRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_escalas_louvor');

        $worshipFunction = WorshipFunction::create($request->safe()->except('members'));

        if ($request->filled('members')) {
            $worshipFunction->members()->attach($request->members);
        }

        return redirect()->route('worship-functions.index')
            ->with('success', 'Função cadastrada com sucesso.');
    }

    public function edit(WorshipFunction $worshipFunction): Response
    {
        $this->authorize('gerenciar_escalas_louvor');

        $memberOptions = $this->memberSelectOptions();
        $worshipFunction->load('members');

        return Inertia::render('WorshipFunctions/Edit', compact('worshipFunction', 'memberOptions'));
    }

    public function update(UpdateWorshipFunctionRequest $request, WorshipFunction $worshipFunction): RedirectResponse
    {
        $this->authorize('gerenciar_escalas_louvor');

        $worshipFunction->update($request->safe()->except('members'));
        $worshipFunction->members()->sync($request->members ?? []);

        return redirect()->route('worship-functions.index')
            ->with('success', 'Função atualizada com sucesso.');
    }

    public function destroy(WorshipFunction $worshipFunction): RedirectResponse
    {
        $this->authorize('gerenciar_escalas_louvor');

        if ($worshipFunction->assignments()->exists()) {
            return redirect()->route('worship-functions.index')
                ->with('error', 'Não é possível excluir uma função que já foi usada em cultos.');
        }

        $worshipFunction->delete();

        return redirect()->route('worship-functions.index')
            ->with('success', 'Função excluída com sucesso.');
    }

    private function memberSelectOptions(): array
    {
        return Member::query()
            ->with('foto')
            ->orderBy('full_name')
            ->get()
            ->mapWithKeys(function (Member $member) {
                $foto = $member->foto->first();
                $image = null;
                if ($foto) {
                    $image = filled($foto->url) ? $foto->url : asset('storage/'.$foto->path);
                }

                return [$member->id => [
                    'name' => $member->full_name,
                    'image' => $image,
                ]];
            })
            ->all();
    }
}
