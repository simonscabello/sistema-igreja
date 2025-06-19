<x-app-layout>
    <x-page-card title="Editar Subcategoria Financeira">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('subcategories.update', $financialSubcategory) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="max-w-2xl">
                <div class="space-y-6">
                    <x-select label="Categoria" name="financial_category_id" :options="$categories->pluck('name', 'id')->toArray()" :selected="old('financial_category_id', $financialSubcategory->financial_category_id)" required />

                    <x-text-input label="Nome da Subcategoria" name="name" :value="old('name', $financialSubcategory->name)" required />

                    <input type="hidden" name="active" value="0">
                    <x-checkbox label="Ativo" name="active" :checked="old('active', $financialSubcategory->active)" />
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex space-x-3">
                    <a href="{{ route('subcategories.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
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
