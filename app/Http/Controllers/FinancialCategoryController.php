<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinancialCategory;
use App\Http\Requests\StoreFinancialCategoryRequest;
use App\Http\Requests\UpdateFinancialCategoryRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FinancialCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = FinancialCategory::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->latest()->paginate(10);

        return view('financial-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('financial-categories.create');
    }

    public function store(StoreFinancialCategoryRequest $request): RedirectResponse
    {
        FinancialCategory::create($request->validated());

        return redirect()->route('financial-categories.index')
            ->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(FinancialCategory $financialCategory): View
    {
        return view('financial-categories.edit', compact('financialCategory'));
    }

    public function update(UpdateFinancialCategoryRequest $request, FinancialCategory $financialCategory): RedirectResponse
    {
        $financialCategory->update($request->validated());

        return redirect()->route('financial-categories.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(FinancialCategory $financialCategory): RedirectResponse
    {
        $financialCategory->delete();

        return redirect()->route('financial-categories.index')
            ->with('success', 'Categoria excluída com sucesso.');
    }
}
