<x-app-layout>
    <x-page-card title="Editar Categoria Financeira">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('financial-categories.update', $financialCategory) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="max-w-2xl">
                <div class="space-y-6">
                    <x-text-input label="Nome" name="name" :value="$financialCategory->name" required />
                    <x-textarea label="Descrição" name="description" :value="$financialCategory->description" />
                    <div class="flex flex-col items-start gap-1">
                        <span class="text-sm font-medium text-neutral-dark dark:text-gray-300 mb-1">Ativo</span>
                        <input type="hidden" name="active" value="0">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="active" value="1" class="sr-only peer" {{ old('active', $financialCategory->active) ? 'checked' : '' }}>
                            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 
                                        peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full 
                                        peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] 
                                        after:bg-white after:border-gray-300 after:border after:rounded-full 
                                        after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                                        peer-checked:bg-primary dark:peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex space-x-3">
                    <a href="{{ route('financial-categories.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    <x-primary-button type="submit">
                        Atualizar
                    </x-primary-button>
                </div>
            </div>
        </form>
    </x-page-card>
</x-app-layout>
