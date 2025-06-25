<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Services\FileService;

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
    public function store(StoreMemberRequest $request, FileService $fileService)
    {
        $member = Member::create($request->validated());
        if ($request->hasFile('foto_perfil')) {
            $fileService->uploadFile($request->file('foto_perfil'), $member, 'foto_perfil');
        }
        return redirect()->route('members.index')
            ->with('success', 'Membro cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return view('members.show', compact('member'));
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
    public function update(UpdateMemberRequest $request, Member $member, FileService $fileService)
    {
        $member->update($request->validated());
        if ($request->hasFile('foto_perfil')) {
            $fotoAtual = $member->foto->first();
            if ($fotoAtual) {
                $fileService->deleteFile($fotoAtual);
            }
            $fileService->uploadFile($request->file('foto_perfil'), $member, 'foto_perfil');
        }
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
