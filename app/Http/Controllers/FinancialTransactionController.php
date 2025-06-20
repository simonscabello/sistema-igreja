<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use App\Models\FinancialCategory;
use App\Models\FinancialSubcategory;
use App\Models\Campaign;
use App\Http\Requests\StoreFinancialTransactionRequest;
use App\Http\Requests\UpdateFinancialTransactionRequest;
use Illuminate\Http\Request;

class FinancialTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialTransaction::with(['subcategory.financialCategory', 'campaign']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('subcategory', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subcategory.financialCategory', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('campaign', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('subcategory')) {
            $query->where('financial_subcategory_id', $request->input('subcategory'));
        }

        if ($request->filled('campaign')) {
            $query->where('campaign_id', $request->input('campaign'));
        }

        $transactions = $query->latest('action_date')->paginate(10);
        $categories = FinancialCategory::with('subcategories')->where('active', true)->get();
        $campaigns = Campaign::where('status', 'ativo')->get();

        return view('financial-transactions.index', compact('transactions', 'categories', 'campaigns'));
    }

    public function create()
    {
        $categories = FinancialCategory::with('subcategories')->where('active', true)->get();
        $campaigns = Campaign::where('status', 'ativo')->get();
        return view('financial-transactions.create', compact('categories', 'campaigns'));
    }

    public function store(StoreFinancialTransactionRequest $request)
    {
        FinancialTransaction::create($request->validated());
        return redirect()->route('financial-transactions.index')->with('success', 'Transação criada com sucesso.');
    }

    public function edit(FinancialTransaction $financialTransaction)
    {
        $categories = FinancialCategory::with('subcategories')->where('active', true)->get();
        $campaigns = Campaign::where('status', 'ativo')->get();
        return view('financial-transactions.edit', compact('financialTransaction', 'categories', 'campaigns'));
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
