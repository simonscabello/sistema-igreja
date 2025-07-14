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
     * Definir todas as permissões do sistema em português
     */
    private function createPermissions(): array
    {
        $this->command->info('Verificando e criando permissões...');

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

        $createdCount = 0;
        $updatedCount = 0;

        foreach ($permissions as $name => $displayName) {
            $permission = Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['display_name' => $displayName]
            );

            if ($permission->wasRecentlyCreated) {
                $createdCount++;
                $this->command->info("✓ Permissão criada: {$displayName}");
            } else {
                // Atualizar display_name se necessário
                if ($permission->display_name !== $displayName) {
                    $permission->update(['display_name' => $displayName]);
                    $updatedCount++;
                    $this->command->info("✓ Permissão atualizada: {$displayName}");
                }
            }
        }

        $this->command->info("Permissões processadas - Criadas: {$createdCount}, Atualizadas: {$updatedCount}");
        return array_keys($permissions);
    }

    /**
     * Criar roles básicas com suas permissões
     */
    private function createRoles(array $allPermissions): void
    {
        $this->command->info('Verificando e criando roles...');

        $rolesConfig = [
            'administrador' => [
                'display_name' => 'Administrador',
                'permissions' => $allPermissions,
                'description' => 'Acesso total ao sistema'
            ],
            'pastor' => [
                'display_name' => 'Pastor',
                'permissions' => [
                    'visualizar_membros', 'criar_membros', 'editar_membros', 'excluir_membros',
                    'visualizar_visitantes', 'criar_visitantes', 'editar_visitantes', 'excluir_visitantes',
                    'visualizar_departamentos', 'gerenciar_departamentos',
                    'visualizar_escalas_louvor', 'gerenciar_escalas_louvor',
                    'visualizar_relatorios', 'exportar_relatorios',
                    'visualizar_financeiro'
                ],
                'description' => 'Gestão pastoral'
            ],
            'tesoureiro' => [
                'display_name' => 'Tesoureiro',
                'permissions' => [
                    'visualizar_financeiro', 'criar_transacoes', 'editar_transacoes', 'excluir_transacoes',
                    'gerenciar_categorias_financeiras', 'gerenciar_campanhas',
                    'exportar_relatorios_financeiros', 'visualizar_relatorios',
                    'visualizar_membros'
                ],
                'description' => 'Gestão financeira'
            ],
            'lider_louvor' => [
                'display_name' => 'Líder de Louvor',
                'permissions' => [
                    'visualizar_musicas', 'gerenciar_musicas',
                    'visualizar_escalas_louvor', 'gerenciar_escalas_louvor',
                    'visualizar_membros'
                ],
                'description' => 'Gestão musical'
            ],
            'secretario' => [
                'display_name' => 'Secretário',
                'permissions' => [
                    'visualizar_membros', 'criar_membros', 'editar_membros',
                    'visualizar_visitantes', 'criar_visitantes', 'editar_visitantes',
                    'visualizar_departamentos'
                ],
                'description' => 'Gestão de membros'
            ],
            'lider_departamento' => [
                'display_name' => 'Líder de Departamento',
                'permissions' => [
                    'visualizar_membros', 'visualizar_departamentos',
                    'visualizar_escalas_louvor'
                ],
                'description' => 'Gestão departamental'
            ],
            'visualizador' => [
                'display_name' => 'Visualizador',
                'permissions' => [
                    'visualizar_membros', 'visualizar_visitantes',
                    'visualizar_departamentos', 'visualizar_musicas',
                    'visualizar_escalas_louvor', 'visualizar_relatorios'
                ],
                'description' => 'Apenas visualização'
            ]
        ];

        $createdCount = 0;
        $updatedCount = 0;

        foreach ($rolesConfig as $roleName => $config) {
            $role = Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web'],
                ['display_name' => $config['display_name']]
            );

            if ($role->wasRecentlyCreated) {
                $createdCount++;
                $this->command->info("✓ Role criada: {$config['display_name']}");
            } else {
                // Atualizar display_name se necessário
                if ($role->display_name !== $config['display_name']) {
                    $role->update(['display_name' => $config['display_name']]);
                    $updatedCount++;
                    $this->command->info("✓ Role atualizada: {$config['display_name']}");
                }
            }

            // Sincronizar permissões sempre para garantir estado correto
            $role->syncPermissions($config['permissions']);
            $this->command->info("  → Permissões sincronizadas para {$config['display_name']} ({$config['description']})");
        }

        $this->command->info("Roles processadas - Criadas: {$createdCount}, Atualizadas: {$updatedCount}");
    }

    /**
     * Criar usuário administrador padrão
     */
    private function createAdminUser(): void
    {
        $this->command->info('Verificando usuário administrador...');

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@igreja.com'],
            [
                'name' => 'Administrador do Sistema',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        if ($adminUser->wasRecentlyCreated) {
            $this->command->info('✓ Usuário administrador criado!');
        } else {
            $this->command->info('✓ Usuário administrador já existe!');
        }

        // Sincronizar role de administrador sempre para garantir estado correto
        $adminUser->syncRoles(['administrador']);
        $this->command->info('✓ Role de administrador sincronizada!');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('=== INICIALIZANDO SISTEMA DE ROLES E PERMISSIONS (MODO SEGURO) ===');

        try {
            DB::transaction(function () {
                // Criar permissões
                $allPermissions = $this->createPermissions();

                // Criar roles
                $this->createRoles($allPermissions);

                // Criar usuário administrador
                $this->createAdminUser();
            });

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
            $this->command->newLine();
            $this->command->info('✅ Seeder executado com segurança - nenhuma tabela foi limpa!');

        } catch (\Exception $e) {
            $this->command->error('❌ Erro durante a execução do seeder: ' . $e->getMessage());
            throw $e;
        }
    }
}
