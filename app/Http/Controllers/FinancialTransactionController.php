<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use App\Models\FinancialCategory;
use App\Http\Requests\StoreFinancialTransactionRequest;
use App\Http\Requests\UpdateFinancialTransactionRequest;
use Illuminate\Http\Request;

class FinancialTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialTransaction::with('category');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('category')) {
            $query->where('financial_category_id', $request->input('category'));
        }

        $transactions = $query->latest('action_date')->paginate(10);
        $categories = FinancialCategory::where('active', true)->get();

        return view('financial-transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $categories = FinancialCategory::where('active', true)->get();
        return view('financial-transactions.create', compact('categories'));
    }

    public function store(StoreFinancialTransactionRequest $request)
    {
        FinancialTransaction::create($request->validated());
        return redirect()->route('financial-transactions.index')->with('success', 'Transação criada com sucesso.');
    }

    public function edit(FinancialTransaction $financialTransaction)
    {
        $categories = FinancialCategory::where('active', true)->get();
        return view('financial-transactions.edit', compact('financialTransaction', 'categories'));
    }

    public function update(UpdateFinancialTransactionRequest $request, FinancialTransaction $financialTransaction)
    {
        $financialTransaction->update($request->validated());
        return redirect()->route('financial-transactions.index')->with('success', 'Transação atualizada com sucesso.');
    }

    public function destroy(FinancialTransaction $financialTransaction)
    {
        $financialTransaction->delete();
        return redirect()->route('financial-transactions.index')->with('success', 'Transação excluída com sucesso.');
    }
}
