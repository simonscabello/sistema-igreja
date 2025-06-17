<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(10);

        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'required|string|max:20',
            'gender' => 'required|in:Masculino,Feminino,Outro',
            'marital_status' => 'nullable|in:Solteiro,Casado,Divorciado,Viúvo',
            'birth_date' => 'required|date',
            'baptism_date' => 'nullable|date',
            'admission_date' => 'nullable|date',
            'wedding_date' => 'nullable|date',
            'zip_code' => 'required|string|max:9',
            'street' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
        ]);

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Membro cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'required|string|max:20',
            'gender' => 'required|in:Masculino,Feminino,Outro',
            'marital_status' => 'nullable|in:Solteiro,Casado,Divorciado,Viúvo',
            'birth_date' => 'required|date',
            'baptism_date' => 'nullable|date',
            'admission_date' => 'nullable|date',
            'wedding_date' => 'nullable|date',
            'zip_code' => 'required|string|max:9',
            'street' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Membro atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Membro excluído com sucesso.');
    }
}
