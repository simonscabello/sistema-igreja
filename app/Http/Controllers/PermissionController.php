<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller
{

    public function index(Request $request): View
    {
        $this->authorize('gerenciar_permissoes');

        $query = Permission::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $permissions = $query->orderBy('name')->paginate(10);

        return view('permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        $this->authorize('gerenciar_permissoes');

        return view('permissions.create');
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_permissoes');

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permissão criada com sucesso.');
    }

    public function show(Permission $permission): View
    {
        $this->authorize('gerenciar_permissoes');

        return view('permissions.show', compact('permission'));
    }

    public function edit(Permission $permission): View
    {
        $this->authorize('gerenciar_permissoes');

        return view('permissions.edit', compact('permission'));
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
