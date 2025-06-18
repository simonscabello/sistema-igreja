<x-app-layout>
    <x-page-card title="Nova Categoria Financeira">
        <form action="{{ route('financial-categories.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <x-text-input label="Nome" name="name" required />
                <x-textarea label="Descrição" name="description" />
                <x-checkbox label="Ativo" name="active" />

                <div class="flex justify-end space-x-3">
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
