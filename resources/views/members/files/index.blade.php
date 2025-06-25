@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Arquivos de {{ $member->full_name }}</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <form action="{{ route('members.files.store', $member) }}" method="POST" enctype="multipart/form-data" class="mb-8">
        @csrf
        <div class="flex items-center gap-4">
            <input type="file" name="files[]" multiple required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark">Enviar</button>
        </div>
        @error('files.*')
            <div class="text-red-600 mt-2 text-sm">{{ $message }}</div>
        @enderror
    </form>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <h3 class="font-semibold mb-4">Arquivos vinculados</h3>
        <table class="w-full text-left">
            <thead>
                <tr>
                    <th class="py-2">Nome</th>
                    <th class="py-2">Tamanho</th>
                    <th class="py-2">Visualizar</th>
                    <th class="py-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($files as $file)
                    <tr class="border-t">
                        <td class="py-2">{{ $file->original_name }}</td>
                        <td class="py-2">{{ number_format($file->size / 1024, 2, ',', '.') }} KB</td>
                        <td class="py-2">
                            <a href="{{ $file->url }}" target="_blank" class="text-primary underline">Abrir</a>
                        </td>
                        <td class="py-2">
                            <form action="{{ route('members.files.destroy', [$member, $file]) }}" method="POST" onsubmit="return confirm('Remover este arquivo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-center text-gray-500">Nenhum arquivo enviado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 