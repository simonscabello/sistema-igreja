<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Member;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('visualizar_departamentos');

        $query = Department::with(['responsibleMembers', 'members']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $departments = $query->latest()->paginate(10);

        return view('departments.index', compact('departments'));
    }

    public function create(): View
    {
        $this->authorize('gerenciar_departamentos');

        $members = Member::orderBy('full_name')->get();
        return view('departments.create', compact('members'));
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_departamentos');

        $department = Department::create($request->validated());

        if ($request->filled('responsible_members')) {
            $department->responsibleMembers()->attach($request->responsible_members);
        }

        if ($request->filled('members')) {
            $department->members()->attach($request->members);
        }

        return redirect()->route('departments.index')
            ->with('success', 'Departamento cadastrado com sucesso.');
    }

    public function show(Department $departments): View
    {
        $this->authorize('visualizar_departamentos');

        $departments->load(['responsibleMembers', 'members']);
        return view('departments.show', compact('departments'));
    }

    public function edit(Department $department): View
    {
        $this->authorize('gerenciar_departamentos');

        $members = Member::orderBy('full_name')->get();
        $department->load(['responsibleMembers', 'members']);
        
        return view('departments.edit', compact('department', 'members'));
    }

    public function update(UpdateDepartmentRequest $request, Department $departments): RedirectResponse
    {
        $this->authorize('gerenciar_departamentos');

        $departments->update($request->validated());

        $departments->responsibleMembers()->sync($request->responsible_members ?? []);
        $departments->members()->sync($request->members ?? []);

        return redirect()->route('departments.index')
            ->with('success', 'Departamento atualizado com sucesso.');
    }

    public function destroy(Department $departments): RedirectResponse
    {
        $this->authorize('gerenciar_departamentos');

        $departments->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Departamento excluído com sucesso.');
    }
}
