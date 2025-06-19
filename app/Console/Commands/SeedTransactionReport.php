<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\TransactionReportSeeder;

class SeedTransactionReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:transaction-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa o seeder de relatório de transações financeiras de Julho/2024';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando seed do relatório de transações...');
        
        try {
            $seeder = new TransactionReportSeeder();
            $seeder->run();
            
            $this->info('✅ Seeder executado com sucesso!');
            $this->info('📊 Transações do relatório de Julho/2024 foram criadas.');
            
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Erro ao executar o seeder: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
