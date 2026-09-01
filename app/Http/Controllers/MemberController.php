<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Services\FileService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly FileService $fileService) {}

    public function index(Request $request): Response
    {
        $this->authorize('visualizar_membros');

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

        return Inertia::render('Members/Index', compact('members'));
    }

    public function create(): Response
    {
        $this->authorize('criar_membros');

        return Inertia::render('Members/Create');
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $this->authorize('criar_membros');

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

    public function show(Member $member): Response
    {
        $this->authorize('visualizar_membros');

        $member = $this->serializeMemberWithFoto($member);

        return Inertia::render('Members/Show', compact('member'));
    }

    public function edit(Member $member): Response
    {
        $this->authorize('editar_membros');

        $member = $this->serializeMemberWithFoto($member);

        return Inertia::render('Members/Edit', compact('member'));
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $this->authorize('editar_membros');

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
        $this->authorize('excluir_membros');

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Membro excluído com sucesso.');
    }

    private function serializeMemberWithFoto(Member $member): array
    {
        $member->load('foto');

        $data = $member->toArray();

        $foto = $member->foto->first();
        if ($foto) {
            $data['foto_url'] = filled($foto->url) ? $foto->url : asset('storage/'.$foto->path);
        }

        return $data;
    }
}
