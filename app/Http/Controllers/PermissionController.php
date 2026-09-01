<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('gerenciar_permissoes');

        $query = Permission::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $permissions = $query->orderBy('name')->paginate(10);

        return Inertia::render('Permissions/Index', compact('permissions'));
    }

    public function create(): Response
    {
        $this->authorize('gerenciar_permissoes');

        return Inertia::render('Permissions/Create');
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_permissoes');

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permissão criada com sucesso.');
    }

    public function show(Permission $permission): Response
    {
        $this->authorize('gerenciar_permissoes');

        return Inertia::render('Permissions/Show', compact('permission'));
    }

    public function edit(Permission $permission): Response
    {
        $this->authorize('gerenciar_permissoes');

        return Inertia::render('Permissions/Edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $this->authorize('gerenciar_permissoes');

        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permissão atualizada com sucesso.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $this->authorize('gerenciar_permissoes');

        if ($permission->roles()->exists()) {
            return redirect()->route('permissions.index')->with('error', 'Não é possível excluir uma permissão que está associada a roles.');
        }

        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permissão excluída com sucesso.');
    }
}
