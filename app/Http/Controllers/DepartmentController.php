<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    public function index(Request $request): Response
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

        return Inertia::render('Departments/Index', compact('departments'));
    }

    public function create(): Response
    {
        $this->authorize('gerenciar_departamentos');

        $memberOptions = $this->memberSelectOptions();

        return Inertia::render('Departments/Create', compact('memberOptions'));
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

    public function show(Department $department): Response
    {
        $this->authorize('visualizar_departamentos');

        $department->load(['responsibleMembers', 'members']);

        return Inertia::render('Departments/Show', compact('department'));
    }

    public function edit(Department $department): Response
    {
        $this->authorize('gerenciar_departamentos');

        $memberOptions = $this->memberSelectOptions();
        $department->load(['responsibleMembers', 'members']);

        return Inertia::render('Departments/Edit', compact('department', 'memberOptions'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $this->authorize('gerenciar_departamentos');

        $department->update($request->validated());

        $department->responsibleMembers()->sync($request->responsible_members ?? []);
        $department->members()->sync($request->members ?? []);

        return redirect()->route('departments.index')
            ->with('success', 'Departamento atualizado com sucesso.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->authorize('gerenciar_departamentos');

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Departamento excluído com sucesso.');
    }

    private function memberSelectOptions(): array
    {
        return Member::query()
            ->with('foto')
            ->orderBy('full_name')
            ->get()
            ->mapWithKeys(function (Member $member) {
                $foto = $member->foto->first();
                $image = null;
                if ($foto) {
                    $image = filled($foto->url) ? $foto->url : asset('storage/'.$foto->path);
                }

                return [$member->id => [
                    'name' => $member->full_name,
                    'image' => $image,
                ]];
            })
            ->all();
    }
}
