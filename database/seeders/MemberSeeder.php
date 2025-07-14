<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class MemberSeeder extends Seeder
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

        // Membros de teste
        $members = [
            [
                'full_name' => 'João Teste',
                'email' => 'joao@teste.com',
                'mobile' => '11999999999',
                'birth_date' => Carbon::now()->subYears(30),
                'gender' => 'Masculino',
                'zip_code' => '01001-000',
                'street' => 'Praça da Sé',
                'neighborhood' => 'Sé',
                'city' => 'São Paulo',
                'state' => 'SP',
                'number' => '1',
                'marital_status' => 'Casado',
            ],
            [
                'full_name' => 'Maria Teste',
                'email' => 'maria@teste.com',
                'mobile' => '11988888888',
                'birth_date' => Carbon::now()->subYears(25),
                'gender' => 'Feminino',
                'zip_code' => '01002-000',
                'street' => 'Avenida Paulista',
                'neighborhood' => 'Bela Vista',
                'city' => 'São Paulo',
                'state' => 'SP',
                'number' => '1000',
                'marital_status' => 'Solteiro',
            ],
        ];

        foreach ($members as $member) {
            Member::firstOrCreate(['email' => $member['email']], $member);
        }

        // Gera mais membros fake
        if (App::environment('local')) {
            Member::factory()->count(15)->create();
        } elseif (App::environment('staging')) {
            Member::factory()->count(5)->create();
        }
    }
}
