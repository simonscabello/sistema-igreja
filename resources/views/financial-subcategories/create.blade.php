<x-app-layout>
    <x-page-card title="Nova Subcategoria Financeira">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('subcategories.store') }}" method="POST">
            @csrf

            <div class="max-w-2xl">
                <div class="space-y-6">
                    <x-select label="Categoria" name="financial_category_id" :options="$categories->pluck('name', 'id')->toArray()" :selected="old('financial_category_id')" required />

                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <x-input-label value="Subcategorias" />
                            <button type="button" id="addSubcategory"
                                    class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                + Adicionar
                            </button>
                        </div>

                        <div class="space-y-3" id="subcategoriesContainer">
                            <div class="flex items-center space-x-3 subcategory-item">
                                <div class="flex-1">
                                    <x-text-input
                                        name="subcategories[0][name]"
                                        placeholder="Nome da subcategoria"
                                        class="w-full"
                                        value="{{ old('subcategories.0.name') }}"
                                    />
                                </div>
                                <div class="flex items-center">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="subcategories[0][active]" value="0">
                                        <input type="checkbox" name="subcategories[0][active]" value="1" class="sr-only peer" {{ old('subcategories.0.active', true) ? 'checked' : '' }}>
                                        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 
                                                    peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full 
                                                    peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] 
                                                    after:bg-white after:border-gray-300 after:border after:rounded-full 
                                                    after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                                                    peer-checked:bg-primary dark:peer-checked:bg-primary">
                                        </div>
                                        <span class="ms-3 text-sm font-medium text-neutral-dark dark:text-gray-300">Ativo</span>
                                    </label>
                                </div>
                                <button type="button" class="remove-subcategory hidden inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Remover
                                </button>
                            </div>
                        </div>

                        @error('subcategories')
                            <x-input-error :messages="$errors->get('subcategories')" class="mt-2" />
                        @enderror
                        @error('subcategories.*.name')
                            <x-input-error :messages="$errors->get('subcategories.*.name')" class="mt-2" />
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex space-x-3">
                    <a href="{{ route('subcategories.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
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
