<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (App::environment('production')) {
            $this->command->error('Atenção: Seeders não devem ser executados no ambiente de produção!');
            return;
        }

        $this->command->info('Iniciando seeders para ambiente: ' . App::environment());

        $this->call([
            // Seeders de dados base
            RolePermissionSeeder::class,
            MemberSeeder::class,
            DepartmentSeeder::class,
            TagSeeder::class,
            FileSeeder::class,

            // Seeders financeiros
            FinancialCategorySeeder::class,
            FinancialSubcategorySeeder::class,
            CampaignSeeder::class,
            FinancialTransactionSeeder::class,
            FinancialTransactionAttachmentSeeder::class,

            // Seeders de conteúdo
            SongSeeder::class,
            WorshipSetSeeder::class,

            // Outros seeders
            VisitorSeeder::class,
            TransactionReportSeeder::class,
        ]);

        $this->command->info('Seeders concluídos com sucesso!');
    }
}
