<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinancialTransactionRequest;
use App\Http\Requests\UpdateFinancialTransactionRequest;
use App\Models\Campaign;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use App\Services\FileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FinancialTransactionController extends Controller
{
    public function __construct(private readonly FileService $fileService) {}

    public function index(Request $request): Response
    {
        $this->authorize('visualizar_financeiro');

        $query = FinancialTransaction::with(['subcategory.financialCategory', 'campaign', 'files']);

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
        $categories = $this->serializeCategoriesWithSubcategories();
        $campaigns = Campaign::where('status', 'ativo')->get();

        return Inertia::render('Financial/Transactions/Index', compact('transactions', 'categories', 'campaigns'));
    }

    public function create(): Response
    {
        $this->authorize('criar_transacoes');

        $categories = $this->serializeCategoriesWithSubcategories();
        $campaigns = Campaign::where('status', 'ativo')->get();

        return Inertia::render('Financial/Transactions/Create', compact('categories', 'campaigns'));
    }

    public function show(FinancialTransaction $financialTransaction): Response
    {
        $this->authorize('visualizar_financeiro');

        $financialTransaction->load(['subcategory.financialCategory', 'campaign']);

        return Inertia::render('Financial/Transactions/Show', compact('financialTransaction'));
    }

    public function store(StoreFinancialTransactionRequest $request): RedirectResponse
    {
        $this->authorize('criar_transacoes');

        $transaction = FinancialTransaction::create($request->validated());

        if ($request->hasFile('attachment')) {
            $this->fileService->uploadFile(
                file: $request->file('attachment'),
                related: $transaction,
                collection: 'comprovantes'
            );
        }

        return redirect()->route('financial.transactions.index')->with('success', 'Transação criada com sucesso.');
    }

    public function edit(FinancialTransaction $financialTransaction): Response
    {
        $this->authorize('editar_transacoes');

        $categories = $this->serializeCategoriesWithSubcategories();
        $campaigns = Campaign::where('status', 'ativo')->get();

        return Inertia::render('Financial/Transactions/Edit', compact('financialTransaction', 'categories', 'campaigns'));
    }

    public function update(UpdateFinancialTransactionRequest $request, FinancialTransaction $financialTransaction): RedirectResponse
    {
        $this->authorize('editar_transacoes');

        $financialTransaction->update($request->validated());

        if ($request->hasFile('attachment')) {
            // Remove arquivo anterior se existir
            $existingFiles = $this->fileService->listFilesFor($financialTransaction, 'comprovantes');
            foreach ($existingFiles as $file) {
                $this->fileService->deleteFile($file);
            }

            // Upload do novo arquivo
            $this->fileService->uploadFile(
                file: $request->file('attachment'),
                related: $financialTransaction,
                collection: 'comprovantes'
            );
        }

        return redirect()->route('financial.transactions.index')
            ->with('success', 'Transação atualizada com sucesso.');
    }

    public function destroy(FinancialTransaction $financialTransaction): RedirectResponse
    {
        $this->authorize('excluir_transacoes');

        // Remove arquivos associados
        $files = $this->fileService->listFilesFor($financialTransaction, 'comprovantes');
        foreach ($files as $file) {
            $this->fileService->deleteFile($file);
        }

        $financialTransaction->delete();

        return redirect()->route('financial.transactions.index')
            ->with('success', 'Transação excluída com sucesso.');
    }

    private function serializeCategoriesWithSubcategories()
    {
        return FinancialCategory::with('subcategories')
            ->where('active', true)
            ->get()
            ->map(fn (FinancialCategory $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'active' => $category->active,
                'subcategories' => $category->subcategories->map(fn ($subcategory) => [
                    'id' => $subcategory->id,
                    'financial_category_id' => $subcategory->financial_category_id,
                    'name' => $subcategory->name,
                    'active' => $subcategory->active,
                ])->values(),
            ])
            ->values();
    }
}
