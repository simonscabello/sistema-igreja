<?php

namespace Database\Seeders;

use App\Models\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class FileSeeder extends Seeder
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

        $files = [
            [
                'original_name' => 'documento_exemplo.pdf',
                'extension' => 'pdf',
                'size' => 1024 * 100, // 100KB
                'mime_type' => 'application/pdf',
                'path' => 'files/documento_exemplo.pdf',
                'disk' => 'public',
                'url' => '/storage/files/documento_exemplo.pdf',
            ],
            [
                'original_name' => 'imagem_exemplo.jpg',
                'extension' => 'jpg',
                'size' => 1024 * 50, // 50KB
                'mime_type' => 'image/jpeg',
                'path' => 'files/imagem_exemplo.jpg',
                'disk' => 'public',
                'url' => '/storage/files/imagem_exemplo.jpg',
            ],
            [
                'original_name' => 'partitura_exemplo.pdf',
                'extension' => 'pdf',
                'size' => 1024 * 200, // 200KB
                'mime_type' => 'application/pdf',
                'path' => 'files/partitura_exemplo.pdf',
                'disk' => 'public',
                'url' => '/storage/files/partitura_exemplo.pdf',
            ],
        ];

        foreach ($files as $fileData) {
            File::firstOrCreate(
                [
                    'original_name' => $fileData['original_name'],
                    'path' => $fileData['path']
                ],
                $fileData
            );
        }
    }
}
