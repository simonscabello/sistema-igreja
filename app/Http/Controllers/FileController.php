<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(Member $member, FileService $service)
    {
        $files = $service->listFilesFor($member);
        return view('members.files.index', compact('member', 'files'));
    }

    public function store(Request $request, Member $member, FileService $service)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240',
        ]);
        foreach ($request->file('files', []) as $file) {
            $service->uploadFile($file, $member);
        }
        return redirect()->route('members.files.index', $member)->with('success', 'Arquivos enviados com sucesso!');
    }

    public function destroy(Member $member, File $file, FileService $service)
    {
        $service->deleteFile($file);
        return redirect()->route('members.files.index', $member)->with('success', 'Arquivo removido!');
    }
} 