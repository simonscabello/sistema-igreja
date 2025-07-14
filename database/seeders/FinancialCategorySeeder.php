<?php

namespace Database\Seeders;

use App\Models\FinancialCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class FinancialCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment('production')) {
            $this->command->error('Atenção: Seeders não devem ser executados no ambiente de produção!');
            return;
        }

        $categories = [
            [
                'name' => 'Receitas',
                'description' => 'Categorias de receitas da igreja',
                'active' => true,
            ],
            [
                'name' => 'Despesas',
                'description' => 'Categorias de despesas da igreja',
                'active' => true,
            ],
            [
                'name' => 'Transferências',
                'description' => 'Transferências entre contas',
                'active' => true,
            ],
            [
                'name' => 'Investimentos',
                'description' => 'Aplicações financeiras',
                'active' => true,
            ],
            [
                'name' => 'Outras',
                'description' => 'Outras categorias',
                'active' => false,
            ],
        ];

        foreach ($categories as $categoryData) {
            FinancialCategory::updateOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }
    }
}
