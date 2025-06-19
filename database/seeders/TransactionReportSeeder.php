<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancialCategory;
use App\Models\FinancialSubcategory;
use App\Models\FinancialTransaction;
use Carbon\Carbon;

class TransactionReportSeeder extends Seeder
{
    public function run(): void
    {
        $this->createCategoriesAndSubcategories();
        $this->createTransactions();
    }

    private function createCategoriesAndSubcategories(): void
    {
        $categories = [
            'Administração Patrimonial' => [
                'Energia',
                'Internet/Telefone',
                'Cópias e Impressões'
            ],
            'Ação Social' => [
                'Fraldas Geriátricas',
                'Ajuda Cristolândia'
            ],
            'Educação Cristã' => [
                'Revista Lições Bíblicas Adolescentes',
                'Revista Lições Bíblicas Adultos'
            ],
            'Evangelismo e Missões' => [
                'Viagem Missionária'
            ],
            'Administração Geral' => [
                'Ajuda de Custo Membros',
                'Limpeza',
                'Água e Esgoto'
            ]
        ];

        foreach ($categories as $categoryName => $subcategories) {
            $category = FinancialCategory::firstOrCreate(
                ['name' => $categoryName],
                [
                    'description' => 'Categoria: ' . $categoryName,
                    'active' => true
                ]
            );

            foreach ($subcategories as $subcategoryName) {
                FinancialSubcategory::firstOrCreate(
                    [
                        'financial_category_id' => $category->id,
                        'name' => $subcategoryName
                    ],
                    ['active' => true]
                );
            }
        }
    }

    private function createTransactions(): void
    {
        $transactions = [
            // Administração Patrimonial
            ['Energia', 344.35, '2024-07-05'],
            ['Internet/Telefone', 99.69, '2024-07-10'],
            ['Cópias e Impressões', 3.00, '2024-07-15'],

            // Ação Social
            ['Fraldas Geriátricas', 251.60, '2024-07-08'],
            ['Ajuda Cristolândia', 53.91, '2024-07-12'],

            // Educação Cristã
            ['Revista Lições Bíblicas Adolescentes', 47.95, '2024-07-03'],
            ['Revista Lições Bíblicas Adultos', 71.91, '2024-07-03'],

            // Evangelismo e Missões
            ['Viagem Missionária', 135.00, '2024-07-20'],

            // Administração Geral
            ['Ajuda de Custo Membros', 180.00, '2024-07-25'],
            ['Limpeza', 70.00, '2024-07-18'],
            ['Água e Esgoto', 89.22, '2024-07-22']
        ];

        foreach ($transactions as [$subcategoryName, $amount, $date]) {
            $subcategory = FinancialSubcategory::where('name', $subcategoryName)->first();
            
            if ($subcategory) {
                FinancialTransaction::create([
                    'financial_subcategory_id' => $subcategory->id,
                    'type' => 'saida',
                    'amount' => $amount,
                    'action_date' => $date,
                    'description' => "Pagamento referente a {$subcategoryName} - Julho/2024"
                ]);
            }
        }
    }
} 