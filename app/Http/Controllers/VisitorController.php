<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitorRequest;
use App\Http\Requests\UpdateVisitorRequest;
use App\Models\Visitor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VisitorController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('visualizar_visitantes');

        $query = Visitor::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('full_address', 'like', "%{$search}%");
            });
        }

        $visitors = $query->orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Visitors/Index', compact('visitors'));
    }

    public function create(): Response
    {
        $this->authorize('criar_visitantes');

        return Inertia::render('Visitors/Create');
    }

    public function store(StoreVisitorRequest $request): RedirectResponse
    {
        $this->authorize('criar_visitantes');

        Visitor::create($request->validated());

        return redirect()->route('visitors.index')
            ->with('success', 'Visitante cadastrado com sucesso.');
    }

    public function show(Visitor $visitor): Response
    {
        $this->authorize('visualizar_visitantes');

        return Inertia::render('Visitors/Show', compact('visitor'));
    }

    public function edit(Visitor $visitor): Response
    {
        $this->authorize('editar_visitantes');

        return Inertia::render('Visitors/Edit', compact('visitor'));
    }

    public function update(UpdateVisitorRequest $request, Visitor $visitor): RedirectResponse
    {
        $this->authorize('editar_visitantes');

        $visitor->update($request->validated());

        return redirect()->route('visitors.index')
            ->with('success', 'Visitante atualizado com sucesso.');
    }

    public function destroy(Visitor $visitor): RedirectResponse
    {
        $this->authorize('excluir_visitantes');

        $visitor->delete();

        return redirect()->route('visitors.index')
            ->with('success', 'Visitante excluído com sucesso.');
    }
}
