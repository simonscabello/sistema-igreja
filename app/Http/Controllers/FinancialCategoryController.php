<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinancialCategoryRequest;
use App\Http\Requests\UpdateFinancialCategoryRequest;
use App\Models\FinancialCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FinancialCategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('gerenciar_categorias_financeiras');

        $query = FinancialCategory::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->latest()->paginate(10);

        return Inertia::render('Financial/Categories/Index', compact('categories'));
    }

    public function create(): Response
    {
        $this->authorize('gerenciar_categorias_financeiras');

        return Inertia::render('Financial/Categories/Create');
    }

    public function store(StoreFinancialCategoryRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_categorias_financeiras');

        FinancialCategory::create($request->validated());

        return redirect()->route('financial.categories.index')
            ->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(FinancialCategory $financialCategory): Response
    {
        $this->authorize('gerenciar_categorias_financeiras');

        return Inertia::render('Financial/Categories/Edit', compact('financialCategory'));
    }

    public function update(UpdateFinancialCategoryRequest $request, FinancialCategory $financialCategory): RedirectResponse
    {
        $this->authorize('gerenciar_categorias_financeiras');

        $financialCategory->update($request->validated());

        return redirect()->route('financial.categories.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(FinancialCategory $financialCategory): RedirectResponse
    {
        $this->authorize('gerenciar_categorias_financeiras');

        $financialCategory->delete();

        return redirect()->route('financial.categories.index')
            ->with('success', 'Categoria excluída com sucesso.');
    }

    public function getSubcategories(Request $request)
    {
        $this->authorize('visualizar_financeiro');

        $categoryId = $request->input('category_id');

        if (! $categoryId) {
            return response()->json([]);
        }

        $category = FinancialCategory::with('subcategories')
            ->where('active', true)
            ->find($categoryId);

        if (! $category) {
            return response()->json([]);
        }

        return response()->json($category->subcategories->where('active', true)->values());
    }
}
