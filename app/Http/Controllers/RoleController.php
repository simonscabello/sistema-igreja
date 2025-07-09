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

        return view('roles.create', compact('permissions'));
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

        return view('roles.show', compact('role'));
    }

    public function edit(Role $role): View
    {
        $this->authorize('gerenciar_roles');

        $permissions = Permission::orderBy('name')->get();
        $role->load('permissions');

        return view('roles.edit', compact('role', 'permissions'));
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
}
