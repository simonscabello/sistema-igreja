<x-app-layout>
    <x-page-card title="Nova Categoria Financeira">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('financial-categories.store') }}" method="POST">
            @csrf

            <div class="max-w-2xl">
                <div class="space-y-6">
                    <x-text-input label="Nome" name="name" required />
                    <x-textarea label="Descrição" name="description" />
                    <input type="hidden" name="active" value="0">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="active" value="1" class="sr-only peer" {{ old('active', true) ? 'checked' : '' }}>
                        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 
                                    peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full 
                                    peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] 
                                    after:bg-white after:border-gray-300 after:border after:rounded-full 
                                    after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                                    peer-checked:bg-primary dark:peer-checked:bg-primary"></div>
                        <span class="ms-3 text-sm font-medium text-neutral-dark dark:text-gray-300">Ativo</span>
                    </label>
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex space-x-3">
                    <a href="{{ route('financial-categories.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
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
