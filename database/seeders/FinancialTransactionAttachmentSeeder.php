<?php

namespace Database\Seeders;

use App\Models\FinancialTransaction;
use App\Models\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class FinancialTransactionAttachmentSeeder extends Seeder
{
    public function run(): void
    {
        if (App::environment('production')) {
            $this->command->error('Atenção: Seeders não devem ser executados no ambiente de produção!');
            return;
        }

        // Buscar algumas transações para adicionar anexos
        $transactions = FinancialTransaction::take(5)->get();

        if ($transactions->isEmpty()) {
            $this->command->info('Nenhuma transação encontrada. Execute o FinancialTransactionSeeder primeiro.');
            return;
        }

        $attachments = [
            [
                'original_name' => 'comprovante_pagamento.pdf',
                'extension' => 'pdf',
                'size' => 1024 * 150, // 150KB
                'mime_type' => 'application/pdf',
                'path' => 'uploads/comprovante_pagamento.pdf',
                'disk' => 'public',
                'url' => '/storage/uploads/comprovante_pagamento.pdf',
            ],
            [
                'original_name' => 'nota_fiscal.jpg',
                'extension' => 'jpg',
                'size' => 1024 * 80, // 80KB
                'mime_type' => 'image/jpeg',
                'path' => 'uploads/nota_fiscal.jpg',
                'disk' => 'public',
                'url' => '/storage/uploads/nota_fiscal.jpg',
            ],
            [
                'original_name' => 'recibo_dizimo.pdf',
                'extension' => 'pdf',
                'size' => 1024 * 120, // 120KB
                'mime_type' => 'application/pdf',
                'path' => 'uploads/recibo_dizimo.pdf',
                'disk' => 'public',
                'url' => '/storage/uploads/recibo_dizimo.pdf',
            ],
        ];

        foreach ($transactions as $index => $transaction) {
            $attachment = $attachments[$index % count($attachments)];
            
            $file = File::create($attachment);
            
            // Associar o arquivo à transação
            $transaction->files('comprovantes')->attach($file->id, ['collection' => 'comprovantes']);
        }

        $this->command->info('Anexos de exemplo adicionados às transações financeiras!');
    }
}
