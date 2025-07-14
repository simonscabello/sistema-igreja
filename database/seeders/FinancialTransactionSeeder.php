<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\FinancialSubcategory;
use App\Models\FinancialTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class FinancialTransactionSeeder extends Seeder
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

        // Obtém as subcategorias já cadastradas
        $subcategories = FinancialSubcategory::all();
        if ($subcategories->isEmpty()) {
            $this->command->info('Não existem subcategorias financeiras cadastradas. O seeder será ignorado.');
            return;
        }

        // Obtém as campanhas já cadastradas
        $campaigns = Campaign::all();

        // Transações de exemplo
        $transactions = [
            [
                'type' => 'entrada',
                'amount' => 1500.00,
                'action_date' => Carbon::now()->subDays(5),
                'description' => 'Dízimos do culto de domingo',
                'subcategory_name' => 'Dízimos',
            ],
            [
                'type' => 'entrada',
                'amount' => 800.00,
                'action_date' => Carbon::now()->subDays(5),
                'description' => 'Ofertas do culto de domingo',
                'subcategory_name' => 'Ofertas',
            ],
            [
                'type' => 'saida',
                'amount' => 350.00,
                'action_date' => Carbon::now()->subDays(2),
                'description' => 'Pagamento da conta de energia',
                'subcategory_name' => 'Energia Elétrica',
            ],
            [
                'type' => 'saida',
                'amount' => 120.00,
                'action_date' => Carbon::now()->subDays(2),
                'description' => 'Pagamento da conta de água',
                'subcategory_name' => 'Água e Esgoto',
            ],
            [
                'type' => 'saida',
                'amount' => 200.00,
                'action_date' => Carbon::now()->subDays(1),
                'description' => 'Pagamento da internet',
                'subcategory_name' => 'Internet e Telefone',
            ],
            [
                'type' => 'entrada',
                'amount' => 2500.00,
                'action_date' => Carbon::now()->subDays(15),
                'description' => 'Doação para reforma',
                'subcategory_name' => 'Doações',
            ],
        ];

        // Cria as transações
        foreach ($transactions as $transactionData) {
            // Encontra a subcategoria correspondente
            $subcategory = $subcategories->where('name', $transactionData['subcategory_name'])->first();

            if ($subcategory) {
                // Opcionalmente associa uma campanha (apenas para algumas transações)
                $campaignId = null;
                if (isset($transactionData['campaign_name']) && $campaigns->isNotEmpty()) {
                    $campaign = $campaigns->where('name', $transactionData['campaign_name'])->first();
                    if ($campaign) {
                        $campaignId = $campaign->id;
                    }
                } elseif (mt_rand(1, 10) > 7 && $campaigns->isNotEmpty()) {
                    // Associa aleatoriamente algumas transações a campanhas
                    $campaignId = $campaigns->random()->id;
                }

                FinancialTransaction::updateOrCreate(
                    [
                        'financial_subcategory_id' => $subcategory->id,
                        'action_date' => $transactionData['action_date'],
                        'amount' => $transactionData['amount'],
                        'type' => $transactionData['type'],
                    ],
                    [
                        'financial_subcategory_id' => $subcategory->id,
                        'campaign_id' => $campaignId,
                        'type' => $transactionData['type'],
                        'amount' => $transactionData['amount'],
                        'action_date' => $transactionData['action_date'],
                        'description' => $transactionData['description'],
                    ]
                );
            }
        }

        // Gera mais transações para ambiente local
        if (App::environment('local')) {
            $this->createRandomTransactions(20, $subcategories, $campaigns);
        } elseif (App::environment('staging')) {
            $this->createRandomTransactions(10, $subcategories, $campaigns);
        }
    }

    /**
     * Cria transações aleatórias para testes
     */
    private function createRandomTransactions(int $count, $subcategories, $campaigns): void
    {
        for ($i = 0; $i < $count; $i++) {
            // Seleciona uma subcategoria aleatória
            $subcategory = $subcategories->random();

            // Determina se a transação será de entrada ou saída
            $type = (mt_rand(0, 1)) ? 'entrada' : 'saida';

            // Determina o valor da transação
            $amount = mt_rand(50, 3000) + (mt_rand(0, 99) / 100);

            // Define a data da transação
            $date = Carbon::now()->subDays(mt_rand(1, 90));

            // Determina se associa uma campanha
            $campaignId = null;
            if (mt_rand(0, 10) > 7 && $campaigns->isNotEmpty()) {
                $campaignId = $campaigns->random()->id;
            }

            FinancialTransaction::create([
                'financial_subcategory_id' => $subcategory->id,
                'campaign_id' => $campaignId,
                'type' => $type,
                'amount' => $amount,
                'action_date' => $date,
                'description' => 'Transação gerada automaticamente para testes',
            ]);
        }
    }
}
