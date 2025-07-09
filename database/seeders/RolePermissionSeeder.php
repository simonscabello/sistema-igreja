<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Limpar todas as tabelas relacionadas a roles e permissions
     */
    private function clearTables(): void
    {
        $this->command->info('Limpando tabelas de roles e permissions...');

        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('role_has_permissions')->delete();
        Permission::query()->delete();
        Role::query()->delete();

        $this->command->info('Tabelas limpas com sucesso!');
    }

    /**
     * Definir todas as permissões do sistema em português
     */
    private function createPermissions(): array
    {
        $this->command->info('Criando permissões...');

        $permissions = [
            // Gestão de Usuários e Sistema
            'gerenciar_roles' => 'Gerenciar Roles do Sistema',
            'gerenciar_permissoes' => 'Gerenciar Permissões do Sistema',
            'gerenciar_usuarios' => 'Gerenciar Usuários do Sistema',

            // Gestão de Membros
            'visualizar_membros' => 'Visualizar Membros',
            'criar_membros' => 'Criar Novos Membros',
            'editar_membros' => 'Editar Dados dos Membros',
            'excluir_membros' => 'Excluir Membros',

            // Gestão de Visitantes
            'visualizar_visitantes' => 'Visualizar Visitantes',
            'criar_visitantes' => 'Criar Novos Visitantes',
            'editar_visitantes' => 'Editar Dados dos Visitantes',
            'excluir_visitantes' => 'Excluir Visitantes',

            // Gestão Financeira
            'visualizar_financeiro' => 'Visualizar Dados Financeiros',
            'criar_transacoes' => 'Criar Transações Financeiras',
            'editar_transacoes' => 'Editar Transações Financeiras',
            'excluir_transacoes' => 'Excluir Transações Financeiras',
            'gerenciar_categorias_financeiras' => 'Gerenciar Categorias Financeiras',
            'gerenciar_campanhas' => 'Gerenciar Campanhas Financeiras',
            'exportar_relatorios_financeiros' => 'Exportar Relatórios Financeiros',

            // Gestão de Louvor
            'visualizar_musicas' => 'Visualizar Repertório Musical',
            'gerenciar_musicas' => 'Gerenciar Repertório Musical',
            'visualizar_escalas_louvor' => 'Visualizar Escalas de Louvor',
            'gerenciar_escalas_louvor' => 'Gerenciar Escalas de Louvor',

            // Gestão de Departamentos
            'visualizar_departamentos' => 'Visualizar Departamentos',
            'gerenciar_departamentos' => 'Gerenciar Departamentos',

            // Relatórios Gerais
            'visualizar_relatorios' => 'Visualizar Relatórios do Sistema',
            'exportar_relatorios' => 'Exportar Relatórios do Sistema',
        ];

        foreach ($permissions as $name => $displayName) {
            Permission::create([
                'name' => $name,
                'display_name' => $displayName,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('Permissões criadas: ' . count($permissions));
        return array_keys($permissions);
    }

    /**
     * Criar roles básicas com suas permissões
     */
    private function createRoles(array $allPermissions): void
    {
        $this->command->info('Criando roles...');

        // 1. Administrador - Acesso total ao sistema
        $adminRole = Role::create([
            'name' => 'administrador',
            'display_name' => 'Administrador',
            'guard_name' => 'web'
        ]);
        $adminRole->syncPermissions($allPermissions);

        // 2. Pastor - Gestão de membros, visitantes, departamentos e relatórios
        $pastorRole = Role::create([
            'name' => 'pastor',
            'display_name' => 'Pastor',
            'guard_name' => 'web'
        ]);
        $pastorRole->syncPermissions([
            'visualizar_membros', 'criar_membros', 'editar_membros', 'excluir_membros',
            'visualizar_visitantes', 'criar_visitantes', 'editar_visitantes', 'excluir_visitantes',
            'visualizar_departamentos', 'gerenciar_departamentos',
            'visualizar_escalas_louvor', 'gerenciar_escalas_louvor',
            'visualizar_relatorios', 'exportar_relatorios',
            'visualizar_financeiro'
        ]);

        // 3. Tesoureiro - Gestão financeira completa
        $tesoureiroRole = Role::create([
            'name' => 'tesoureiro',
            'display_name' => 'Tesoureiro',
            'guard_name' => 'web'
        ]);
        $tesoureiroRole->syncPermissions([
            'visualizar_financeiro', 'criar_transacoes', 'editar_transacoes', 'excluir_transacoes',
            'gerenciar_categorias_financeiras', 'gerenciar_campanhas',
            'exportar_relatorios_financeiros', 'visualizar_relatorios',
            'visualizar_membros'
        ]);

        // 4. Líder de Louvor - Gestão do ministério de música
        $liderLouvorRole = Role::create([
            'name' => 'lider_louvor',
            'display_name' => 'Líder de Louvor',
            'guard_name' => 'web'
        ]);
        $liderLouvorRole->syncPermissions([
            'visualizar_musicas', 'gerenciar_musicas',
            'visualizar_escalas_louvor', 'gerenciar_escalas_louvor',
            'visualizar_membros'
        ]);

        // 5. Secretário - Gestão de membros e visitantes
        $secretarioRole = Role::create([
            'name' => 'secretario',
            'display_name' => 'Secretário',
            'guard_name' => 'web'
        ]);
        $secretarioRole->syncPermissions([
            'visualizar_membros', 'criar_membros', 'editar_membros',
            'visualizar_visitantes', 'criar_visitantes', 'editar_visitantes',
            'visualizar_departamentos'
        ]);

        // 6. Líder de Departamento - Gestão específica de departamento
        $liderDepartamentoRole = Role::create([
            'name' => 'lider_departamento',
            'display_name' => 'Líder de Departamento',
            'guard_name' => 'web'
        ]);
        $liderDepartamentoRole->syncPermissions([
            'visualizar_membros', 'visualizar_departamentos',
            'visualizar_escalas_louvor'
        ]);

        // 7. Visualizador - Apenas visualização geral
        $visualizadorRole = Role::create([
            'name' => 'visualizador',
            'display_name' => 'Visualizador',
            'guard_name' => 'web'
        ]);
        $visualizadorRole->syncPermissions([
            'visualizar_membros', 'visualizar_visitantes',
            'visualizar_departamentos', 'visualizar_musicas',
            'visualizar_escalas_louvor', 'visualizar_relatorios'
        ]);

        $this->command->info('Roles criadas: 7');
    }

    /**
     * Criar usuário administrador padrão
     */
    private function createAdminUser(): void
    {
        $this->command->info('Criando usuário administrador...');

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@igreja.com'],
            [
                'name' => 'Administrador do Sistema',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Remover roles existentes e atribuir role de administrador
        $adminUser->syncRoles(['administrador']);

        $this->command->info('Usuário administrador criado/atualizado!');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('=== INICIALIZANDO SISTEMA DE ROLES E PERMISSIONS ===');

        // Limpar tabelas existentes
        $this->clearTables();

        // Criar permissões
        $allPermissions = $this->createPermissions();

        // Criar roles
        $this->createRoles($allPermissions);

        // Criar usuário administrador
        $this->createAdminUser();

        $this->command->info('=== SISTEMA DE ROLES E PERMISSIONS CONFIGURADO COM SUCESSO ===');
        $this->command->newLine();
        $this->command->info('📧 Email do Administrador: admin@igreja.com');
        $this->command->info('🔑 Senha: admin123');
        $this->command->newLine();
        $this->command->info('Roles disponíveis:');
        $this->command->info('• Administrador - Acesso total');
        $this->command->info('• Pastor - Gestão pastoral');
        $this->command->info('• Tesoureiro - Gestão financeira');
        $this->command->info('• Líder de Louvor - Gestão musical');
        $this->command->info('• Secretário - Gestão de membros');
        $this->command->info('• Líder de Departamento - Gestão departamental');
        $this->command->info('• Visualizador - Apenas visualização');
    }
}
