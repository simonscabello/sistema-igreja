<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinancialSubcategoryRequest;
use App\Http\Requests\UpdateFinancialSubcategoryRequest;
use App\Models\FinancialCategory;
use App\Models\FinancialSubcategory;
use Illuminate\Http\Request;

class FinancialSubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = FinancialCategory::where('active', true)->get();
        return view('financial-subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFinancialSubcategoryRequest $request)
    {
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialSubcategory $financialSubcategory)
    {
        $categories = FinancialCategory::where('active', true)->get();
        return view('financial-subcategories.edit', compact('financialSubcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFinancialSubcategoryRequest $request, FinancialSubcategory $financialSubcategory)
    {
        $data = $request->validated();

        $financialSubcategory->update($data);

        return redirect()->route('subcategories.index')
            ->with('success', 'Subcategoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialSubcategory $financialSubcategory)
    {
        if ($financialSubcategory->transactions()->exists()) {
            return redirect()->route('subcategories.index')
                ->with('error', 'Não é possível excluir uma subcategoria que possui transações.');
        }

        $financialSubcategory->delete();

        return redirect()->route('subcategories.index')
            ->with('success', 'Subcategoria excluída com sucesso!');
    }
}
