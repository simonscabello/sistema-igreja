<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class TagSeeder extends Seeder
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

        $tags = [
            'Adoração',
            'Louvor',
            'Comunhão',
            'Gratidão',
            'Arrependimento',
            'Consagração',
            'Evangelismo',
            'Missões',
            'Família',
            'Jovens',
            'Crianças',
            'Natal',
            'Páscoa',
            'Batismo',
            'Casamento',
        ];

        foreach ($tags as $tagName) {
            Tag::firstOrCreate(['name' => $tagName]);
        }
    }
}
