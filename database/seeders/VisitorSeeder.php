<?php

namespace Database\Seeders;

use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class VisitorSeeder extends Seeder
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

        $visitors = [
            [
                'name' => 'Pedro Almeida',
                'mobile' => '11987654321',
                'age_group' => 'adulto',
                'gender' => 'masculino',
                'wants_contact' => true,
                'visit_date' => Carbon::now()->subDays(2),
                'notes' => 'Veio através do convite de um membro',
                'full_address' => 'Rua das Flores, 123 - Centro',
            ],
            [
                'name' => 'Ana Costa',
                'mobile' => '11912345678',
                'age_group' => 'jovem',
                'gender' => 'feminino',
                'wants_contact' => true,
                'visit_date' => Carbon::now()->subDays(1),
                'notes' => 'Conheceu a igreja pelas redes sociais',
                'full_address' => 'Avenida Principal, 456 - Jardim',
            ],
            [
                'name' => 'Carlos Santos',
                'mobile' => '11998765432',
                'age_group' => 'adulto',
                'gender' => 'masculino',
                'wants_contact' => false,
                'visit_date' => Carbon::now()->subWeeks(1),
                'notes' => 'Está em busca de uma nova igreja',
                'full_address' => 'Rua das Palmeiras, 789 - Bairro Novo',
            ],
        ];

        foreach ($visitors as $visitorData) {
            Visitor::updateOrCreate(
                [
                    'name' => $visitorData['name'],
                    'mobile' => $visitorData['mobile']
                ],
                $visitorData
            );
        }

        // Gera mais visitantes para ambiente local
        if (App::environment('local')) {
            $this->createRandomVisitors(10);
        } elseif (App::environment('staging')) {
            $this->createRandomVisitors(5);
        }
    }

    /**
     * Cria visitantes aleatórios para testes
     */
    private function createRandomVisitors(int $count): void
    {
        $ageGroups = ['crianca_adolescente', 'jovem', 'adulto', 'idoso'];
        $genders = ['masculino', 'feminino'];

        for ($i = 0; $i < $count; $i++) {
            Visitor::create([
                'name' => 'Visitante ' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'mobile' => '119' . rand(10000000, 99999999),
                'age_group' => $ageGroups[array_rand($ageGroups)],
                'gender' => $genders[array_rand($genders)],
                'wants_contact' => (bool) rand(0, 1),
                'visit_date' => Carbon::now()->subDays(rand(1, 30)),
                'notes' => 'Visitante gerado automaticamente para testes',
                'full_address' => 'Endereço de teste, ' . rand(100, 999),
            ]);
        }
    }
}
