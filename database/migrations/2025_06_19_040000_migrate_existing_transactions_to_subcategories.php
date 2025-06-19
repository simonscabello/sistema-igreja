<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\FinancialTransaction;
use App\Models\FinancialCategory;
use App\Models\FinancialSubcategory;

return new class extends Migration
{
    public function up(): void
    {
        // Para cada transação sem subcategoria, criar uma subcategoria padrão
        $transactionsWithoutSubcategory = FinancialTransaction::whereNull('financial_subcategory_id')->get();

        foreach ($transactionsWithoutSubcategory as $transaction) {
            // Buscar a categoria original (se ainda existir)
            $category = FinancialCategory::find($transaction->getOriginal('financial_category_id'));

            if ($category) {
                // Criar uma subcategoria padrão para esta categoria
                $subcategory = FinancialSubcategory::firstOrCreate([
                    'financial_category_id' => $category->id,
                    'name' => 'Geral'
                ], [
                    'financial_category_id' => $category->id,
                    'name' => 'Geral'
                ]);

                // Atualizar a transação
                $transaction->update([
                    'financial_subcategory_id' => $subcategory->id
                ]);
            } else {
                // Se a categoria não existe mais, usar a primeira categoria disponível
                $firstCategory = FinancialCategory::first();
                if ($firstCategory) {
                    $subcategory = FinancialSubcategory::firstOrCreate([
                        'financial_category_id' => $firstCategory->id,
                        'name' => 'Migrado'
                    ], [
                        'financial_category_id' => $firstCategory->id,
                        'name' => 'Migrado'
                    ]);

                    $transaction->update([
                        'financial_subcategory_id' => $subcategory->id
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Não é possível reverter esta migração de forma segura
        // pois perdemos a referência original da categoria
    }
};
