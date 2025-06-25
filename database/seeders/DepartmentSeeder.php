<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'title' => 'Louvor e Adoração',
                'description' => 'Responsável pela música e adoração durante os cultos',
                'is_active' => true,
            ],
            [
                'title' => 'Infantil',
                'description' => 'Ministério voltado para crianças e suas atividades',
                'is_active' => true,
            ],
            [
                'title' => 'Jovens',
                'description' => 'Ministério voltado para jovens e adolescentes',
                'is_active' => true,
            ],
            [
                'title' => 'Evangelismo',
                'description' => 'Responsável pela evangelização e alcance de vidas',
                'is_active' => true,
            ],
            [
                'title' => 'Ação Social',
                'description' => 'Desenvolvimento de projetos sociais e assistenciais',
                'is_active' => true,
            ],
            [
                'title' => 'Administração',
                'description' => 'Gestão administrativa e financeira da igreja',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
