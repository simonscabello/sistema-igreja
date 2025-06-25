<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Member;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Department::with(['responsibleMembers', 'members']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::orderBy('full_name')->get();
        return view('departments.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $department = Department::create($request->validated());

        if ($request->filled('responsible_members')) {
            $department->responsibleMembers()->attach($request->responsible_members);
        }

        if ($request->filled('members')) {
            $department->members()->attach($request->members);
        }

        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $departamento)
    {
        $departamento->load(['responsibleMembers', 'members']);
        return view('departments.show', compact('departamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $departamento)
    {
        $members = Member::orderBy('full_name')->get();
        $departamento->load(['responsibleMembers', 'members']);
        return view('departments.edit', compact('departamento', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $departamento)
    {
        $departamento->update($request->validated());

        $departamento->responsibleMembers()->sync($request->responsible_members ?? []);
        $departamento->members()->sync($request->members ?? []);

        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $departamento)
    {
        $departamento->delete();

        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento excluído com sucesso.');
    }
}
