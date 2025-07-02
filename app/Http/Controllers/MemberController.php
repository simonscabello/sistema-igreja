<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Services\FileService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MemberController extends Controller
{
    public function __construct(private readonly FileService $fileService) {}

    public function index(Request $request): View
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

    public function create(): View
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $member = Member::create($request->validated());
        if ($request->hasFile('foto_perfil')) {
            $this->fileService->uploadFile(
                file: $request->file('foto_perfil'),
                related: $member,
                collection: 'foto_perfil'
            );
        }

        return redirect()->route('members.index')
            ->with('success', 'Membro cadastrado com sucesso.');
    }

    public function show(Member $member): View
    {
        return view('members.show', compact('member'));
    }

    public function edit(Member $member): View
    {
        return view('members.edit', compact('member'));
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $member->update($request->validated());
        if ($request->hasFile('foto_perfil')) {
            $fotoAtual = $member->foto->first();
            if ($fotoAtual) {
                $this->fileService->deleteFile($fotoAtual);
            }
            $this->fileService->uploadFile(
                file: $request->file('foto_perfil'),
                related: $member,
                collection: 'foto_perfil'
            );
        }

        return redirect()->route('members.index')
            ->with('success', 'Membro atualizado com sucesso.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Membro excluído com sucesso.');
    }
}
