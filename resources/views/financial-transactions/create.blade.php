<x-app-layout>
    <x-page-card title="Nova Transação Financeira">
        <form action="{{ route('financial-transactions.store') }}" method="POST">
            @csrf

            <div class="max-w-2xl">
                <div class="space-y-6">
                    <x-select label="Categoria" name="financial_category_id" :options="$categories->pluck('name', 'id')" required />
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <div class="flex space-x-4">
                            <label class="cursor-pointer px-4 py-2 rounded-lg border text-sm font-medium transition-all duration-200 {{ old('type') === 'entrada' ? 'bg-green-100 text-green-800 border-green-500' : 'bg-white text-gray-700 border-gray-300' }}">
                                <input type="radio" name="type" value="entrada" class="appearance-none focus:ring-2 focus:ring-green-500" {{ old('type') === 'entrada' ? 'checked' : '' }}>
                                Entrada
                            </label>
                            <label class="cursor-pointer px-4 py-2 rounded-lg border text-sm font-medium transition-all duration-200 {{ old('type') === 'saida' ? 'bg-red-100 text-red-800 border-red-500' : 'bg-white text-gray-700 border-gray-300' }}">
                                <input type="radio" name="type" value="saida" class="appearance-none focus:ring-2 focus:ring-red-500" {{ old('type') === 'saida' ? 'checked' : '' }}>
                                Saída
                            </label>
                        </div>
                    </div>
                    <x-input-currency label="Valor" name="amount" prefix="R$" required />
                    <x-input-datetime label="Data da Ação" name="action_date" required />
                    <x-textarea label="Descrição" name="description" />
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex space-x-3">
                    <a href="{{ route('financial-transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
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
