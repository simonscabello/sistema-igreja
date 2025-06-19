<?php

namespace Database\Seeders;

use App\Models\FinancialCategory;
use App\Models\FinancialSubcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinancialSubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = FinancialCategory::all();

        if ($categories->isEmpty()) {
            return;
        }

        $subcategories = [
            // Despesas Operacionais
            'Despesas Operacionais' => [
                'Energia Elétrica',
                'Água e Esgoto',
                'Internet e Telefone',
                'Limpeza',
                'Manutenção',
                'Seguros',
            ],
            // Receitas
            'Receitas' => [
                'Dízimos',
                'Ofertas',
                'Eventos',
                'Doações',
                'Aluguel de Salão',
            ],
            // Investimentos
            'Investimentos' => [
                'Equipamentos',
                'Reformas',
                'Tecnologia',
                'Móveis',
            ],
        ];

        foreach ($subcategories as $categoryName => $subcategoryNames) {
            $category = $categories->where('name', $categoryName)->first();

            if ($category) {
                foreach ($subcategoryNames as $subcategoryName) {
                    FinancialSubcategory::create([
                        'financial_category_id' => $category->id,
                        'name' => $subcategoryName,
                    ]);
                }
            }
        }
    }
}
