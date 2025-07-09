<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinancialSubcategoryRequest;
use App\Http\Requests\UpdateFinancialSubcategoryRequest;
use App\Models\FinancialCategory;
use App\Models\FinancialSubcategory;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FinancialSubcategoryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('gerenciar_categorias_financeiras');

        $query = FinancialSubcategory::with('financialCategory');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('financialCategory', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $subcategories = $query->orderBy('name')->paginate(10);

        return view('financial-subcategories.index', compact('subcategories'));
    }

    public function create(): View
    {
        $this->authorize('gerenciar_categorias_financeiras');

        $categories = FinancialCategory::where('active', true)->get();

        return view('financial-subcategories.create', compact('categories'));
    }

    public function store(StoreFinancialSubcategoryRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_categorias_financeiras');

        $data = $request->validated();

        if (isset($data['subcategories']) && is_array($data['subcategories'])) {
            foreach ($data['subcategories'] as $subcategoryData) {
                if (!empty($subcategoryData['name'])) {
                    FinancialSubcategory::create([
                        'financial_category_id' => $data['financial_category_id'],
                        'name' => $subcategoryData['name'],
                        'active' => $subcategoryData['active'] ?? true,
                    ]);
                }
            }
        }

        return redirect()->route('subcategories.index')
            ->with('success', 'Subcategorias criadas com sucesso!');
    }

    public function edit(FinancialSubcategory $financialSubcategory): View
    {
        $this->authorize('gerenciar_categorias_financeiras');

        $categories = FinancialCategory::where('active', true)->get();

        return view('financial-subcategories.edit', compact('financialSubcategory', 'categories'));
    }

    public function update(UpdateFinancialSubcategoryRequest $request, FinancialSubcategory $financialSubcategory): RedirectResponse
    {
        $this->authorize('gerenciar_categorias_financeiras');

        $data = $request->validated();

        $financialSubcategory->update($data);

        return redirect()->route('subcategories.index')
            ->with('success', 'Subcategoria atualizada com sucesso!');
    }

    public function destroy(FinancialSubcategory $financialSubcategory): RedirectResponse
    {
        $this->authorize('gerenciar_categorias_financeiras');

        if ($financialSubcategory->transactions()->exists()) {
            return redirect()->route('subcategories.index')
                ->with('error', 'Não é possível excluir uma subcategoria que possui transações.');
        }

        $financialSubcategory->delete();

        return redirect()->route('subcategories.index')
            ->with('success', 'Subcategoria excluída com sucesso!');
    }
}
