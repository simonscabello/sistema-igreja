<x-app-layout>
    <x-page-card title="Novo Departamento">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('departamentos.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-text-input label="Título" name="title" :value="old('title')" placeholder="Digite o título do departamento" required />
                <x-checkbox label="Ativo" name="is_active" :checked="old('is_active', true)" />
            </div>

            <div>
                <x-textarea label="Descrição" name="description" :value="old('description')" placeholder="Digite a descrição do departamento" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold text-md text-gray-700 dark:text-gray-300 mb-2">Líderes</label>
                    <select name="responsible_members[]" multiple 
                            class="w-full border-neutral-medium dark:border-gray-600 rounded-md shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-neutral-dark dark:text-white min-h-[120px]">
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ in_array($member->id, old('responsible_members', [])) ? 'selected' : '' }}>
                                {{ $member->full_name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400">Pressione Ctrl (ou Cmd no Mac) para selecionar múltiplos membros</p>
                    @error('responsible_members')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-bold text-md text-gray-700 dark:text-gray-300 mb-2">Membros</label>
                    <select name="members[]" multiple 
                            class="w-full border-neutral-medium dark:border-gray-600 rounded-md shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-neutral-dark dark:text-white min-h-[120px]">
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ in_array($member->id, old('members', [])) ? 'selected' : '' }}>
                                {{ $member->full_name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400">Pressione Ctrl (ou Cmd no Mac) para selecionar múltiplos membros</p>
                    @error('members')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('departamentos.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    <x-primary-button type="submit">
                        Salvar
                    </x-primary-button>
                </div>
            </div>
        </form>
    </x-page-card>
</x-app-layout> 