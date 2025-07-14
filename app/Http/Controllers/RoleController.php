<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{

    public function index(Request $request): View
    {
        $this->authorize('gerenciar_roles');

        $query = Role::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $roles = $query->orderBy('name')->paginate(10);

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        $this->authorize('gerenciar_roles');

        $permissions = Permission::orderBy('name')->get();
        $groupedPermissions = $this->groupPermissionsByFeature($permissions);

        return view('roles.create', compact('groupedPermissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_roles');

        $role = Role::create(['name' => $request->name]);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role criado com sucesso.');
    }

    public function show(Role $role): View
    {
        $this->authorize('gerenciar_roles');

        $role->load('permissions');
        $groupedPermissions = $this->groupPermissionsByFeature($role->permissions);

        return view('roles.show', compact('role', 'groupedPermissions'));
    }

    public function edit(Role $role): View
    {
        $this->authorize('gerenciar_roles');

        $permissions = Permission::orderBy('name')->get();
        $groupedPermissions = $this->groupPermissionsByFeature($permissions);
        $role->load('permissions');

        return view('roles.edit', compact('role', 'groupedPermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->authorize('gerenciar_roles');

        $role->update(['name' => $request->name]);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', 'Role atualizado com sucesso.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('gerenciar_roles');

        if ($role->users()->exists()) {
            return redirect()->route('roles.index')->with('error', 'Não é possível excluir um role que possui usuários associados.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role excluído com sucesso.');
    }

    private function groupPermissionsByFeature($permissions): array
    {
        $groups = [
            'Sistema e Usuários' => [
                'permissions' => [],
                'description' => 'Gerenciamento de usuários, roles e permissões do sistema'
            ],
            'Membros' => [
                'permissions' => [],
                'description' => 'Gestão de membros da igreja'
            ],
            'Visitantes' => [
                'permissions' => [],
                'description' => 'Gestão de visitantes da igreja'
            ],
            'Financeiro' => [
                'permissions' => [],
                'description' => 'Gestão financeira e transações'
            ],
            'Louvor' => [
                'permissions' => [],
                'description' => 'Gestão de músicas e escalas de louvor'
            ],
            'Departamentos' => [
                'permissions' => [],
                'description' => 'Gestão de departamentos da igreja'
            ],
            'Relatórios' => [
                'permissions' => [],
                'description' => 'Visualização e exportação de relatórios'
            ]
        ];

        foreach ($permissions as $permission) {
            $permissionName = $permission->name;
            
            // Sistema e Usuários
            if (in_array($permissionName, ['gerenciar_roles', 'gerenciar_permissoes', 'gerenciar_usuarios'])) {
                $groups['Sistema e Usuários']['permissions'][] = $permission;
            }
            // Membros
            elseif (str_contains($permissionName, 'membros')) {
                $groups['Membros']['permissions'][] = $permission;
            }
            // Visitantes
            elseif (str_contains($permissionName, 'visitantes')) {
                $groups['Visitantes']['permissions'][] = $permission;
            }
            // Financeiro
            elseif (in_array($permissionName, [
                'visualizar_financeiro', 'criar_transacoes', 'editar_transacoes', 'excluir_transacoes',
                'gerenciar_categorias_financeiras', 'gerenciar_campanhas', 'exportar_relatorios_financeiros'
            ])) {
                $groups['Financeiro']['permissions'][] = $permission;
            }
            // Louvor
            elseif (str_contains($permissionName, 'musicas') || str_contains($permissionName, 'escalas_louvor')) {
                $groups['Louvor']['permissions'][] = $permission;
            }
            // Departamentos
            elseif (str_contains($permissionName, 'departamentos')) {
                $groups['Departamentos']['permissions'][] = $permission;
            }
            // Relatórios
            elseif (in_array($permissionName, ['visualizar_relatorios', 'exportar_relatorios'])) {
                $groups['Relatórios']['permissions'][] = $permission;
            }
        }

        // Remove grupos vazios
        return array_filter($groups, function($group) {
            return !empty($group['permissions']);
        });
    }
}
