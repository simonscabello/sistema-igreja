<x-app-layout>
    <x-page-card title="Nova Permissão">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('permissions.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-4">
                <x-text-input label="Nome" name="name" :value="old('name')" placeholder="Digite o nome da permissão" required="true" />
                <div class="text-sm text-neutral-medium dark:text-gray-400">
                    <p class="mb-2">Exemplos de nomes de permissão:</p>
                    <ul class="list-disc pl-5">
                        <li>view-users</li>
                        <li>create-members</li>
                        <li>edit-financial-transactions</li>
                        <li>delete-campaigns</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('permissions.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
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
