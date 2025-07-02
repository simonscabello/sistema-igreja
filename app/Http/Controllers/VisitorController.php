<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Http\Requests\StoreVisitorRequest;
use App\Http\Requests\UpdateVisitorRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class VisitorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Visitor::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('full_address', 'like', "%{$search}%");
            });
        }

        $visitors = $query->latest()->paginate(10);

        return view('visitors.index', compact('visitors'));
    }

    public function create(): View
    {
        return view('visitors.create');
    }

    public function store(StoreVisitorRequest $request): RedirectResponse
    {
        Visitor::create($request->validated());

        return redirect()->route('visitors.index')
            ->with('success', 'Visitante cadastrado com sucesso.');
    }

    public function show(Visitor $visitor): View
    {
        return view('visitors.show', compact('visitor'));
    }

    public function edit(Visitor $visitor): View
    {
        return view('visitors.edit', compact('visitor'));
    }

    public function update(UpdateVisitorRequest $request, Visitor $visitor): RedirectResponse
    {
        $visitor->update($request->validated());

        return redirect()->route('visitors.index')
            ->with('success', 'Visitante atualizado com sucesso.');
    }

    public function destroy(Visitor $visitor): RedirectResponse
    {
        $visitor->delete();

        return redirect()->route('visitors.index')
            ->with('success', 'Visitante excluído com sucesso.');
    }
}
