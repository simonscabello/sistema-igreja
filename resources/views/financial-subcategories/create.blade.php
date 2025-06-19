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
                                    <input type="hidden" name="subcategories[0][active]" value="0">
                                    <input type="checkbox" name="subcategories[0][active]" value="1" class="rounded border-neutral-medium text-primary shadow-sm focus:border-primary focus:ring-primary" {{ old('subcategories.0.active', true) ? 'checked' : '' }}>
                                    <label class="ml-2 text-sm text-neutral-dark dark:text-gray-300">Ativo</label>
                                </div>
                                <button type="button" class="remove-subcategory hidden inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('subcategoriesContainer');
                const addButton = document.getElementById('addSubcategory');
                let subcategoryIndex = 1;

                addButton.addEventListener('click', function() {
                    const newItem = document.createElement('div');
                    newItem.className = 'flex items-center space-x-3 subcategory-item';
                    newItem.innerHTML = `
                        <div class="flex-1">
                            <input type="text"
                                   name="subcategories[${subcategoryIndex}][name]"
                                   placeholder="Nome da subcategoria"
                                   class="mt-1 block w-full border-neutral-medium dark:border-gray-600 rounded-md shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-neutral-dark dark:text-white">
                        </div>
                        <div class="flex items-center">
                            <input type="hidden" name="subcategories[${subcategoryIndex}][active]" value="0">
                            <input type="checkbox" name="subcategories[${subcategoryIndex}][active]" value="1" class="rounded border-neutral-medium text-primary shadow-sm focus:border-primary focus:ring-primary" checked>
                            <label class="ml-2 text-sm text-neutral-dark dark:text-gray-300">Ativo</label>
                        </div>
                        <button type="button" class="remove-subcategory inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Remover
                        </button>
                    `;
                    container.appendChild(newItem);
                    subcategoryIndex++;

                    // Mostrar botão remover no primeiro item se houver mais de um
                    const items = container.querySelectorAll('.subcategory-item');
                    if (items.length > 1) {
                        items[0].querySelector('.remove-subcategory').classList.remove('hidden');
                    }
                });

                container.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-subcategory')) {
                        e.target.closest('.subcategory-item').remove();

                        // Esconder botão remover no primeiro item se só houver um
                        const items = container.querySelectorAll('.subcategory-item');
                        if (items.length === 1) {
                            items[0].querySelector('.remove-subcategory').classList.add('hidden');
                        }

                        // Reindexar os campos
                        const items = container.querySelectorAll('.subcategory-item');
                        items.forEach((item, index) => {
                            const nameInput = item.querySelector('input[name^="subcategories"][name$="[name]"]');
                            const activeHiddenInput = item.querySelector('input[name^="subcategories"][name$="[active]"]');
                            const activeCheckbox = item.querySelector('input[type="checkbox"][name^="subcategories"][name$="[active]"]');

                            if (nameInput) nameInput.name = `subcategories[${index}][name]`;
                            if (activeHiddenInput) activeHiddenInput.name = `subcategories[${index}][active]`;
                            if (activeCheckbox) activeCheckbox.name = `subcategories[${index}][active]`;
                        });
                        subcategoryIndex = items.length;
                    }
                });
            });
        </script>
    </x-page-card>
</x-app-layout>
