<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Member;
use App\Services\FileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FileController extends Controller
{
    public function index(Member $member, FileService $service): Response
    {
        $this->authorize('visualizar_membros');

        $files = $service->listFilesFor($member);

        return Inertia::render('Members/Files/Index', compact('member', 'files'));
    }

    public function store(Request $request, Member $member, FileService $service): RedirectResponse
    {
        $this->authorize('editar_membros');

        $request->validate([
            'files.*' => 'required|file|max:10240',
        ]);

        foreach ($request->file('files', []) as $file) {
            $service->uploadFile($file, $member);
        }

        return redirect()->route('members.files.index', $member)
            ->with('success', 'Arquivos enviados com sucesso!');
    }

    public function destroy(Member $member, File $file, FileService $service): RedirectResponse
    {
        $this->authorize('editar_membros');

        $service->deleteFile($file);

        return redirect()->route('members.files.index', $member)
            ->with('success', 'Arquivo removido!');
    }
}
