<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinancialCategory;
use App\Http\Requests\StoreFinancialCategoryRequest;
use App\Http\Requests\UpdateFinancialCategoryRequest;

class FinancialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financial-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFinancialCategoryRequest $request)
    {
        FinancialCategory::create($request->validated());
        return redirect()->route('financial-categories.index')->with('success', 'Categoria criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialCategory $financialCategory)
    {
        return view('financial-categories.edit', compact('financialCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFinancialCategoryRequest $request, FinancialCategory $financialCategory)
    {
        $financialCategory->update($request->validated());
        return redirect()->route('financial-categories.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialCategory $financialCategory)
    {
        $financialCategory->delete();
        return redirect()->route('financial-categories.index')->with('success', 'Categoria excluída com sucesso.');
    }
}
