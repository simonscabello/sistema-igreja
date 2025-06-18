<x-app-layout>
    <x-page-card title="Nova Transação Financeira">
        <form action="{{ route('financial-transactions.store') }}" method="POST">
            @csrf

            <div class="max-w-2xl">
                <div class="space-y-6">
                    <x-select label="Categoria" name="financial_category_id" :options="$categories->pluck('name', 'id')" required />

                    <div class="space-y-2">
                        <label class="block text-md font-bold text-gray-700 ">Tipo</label>
                        <div class="flex gap-4">
                            <label class="relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="type"
                                    value="entrada"
                                    class="sr-only peer "
                                    {{ old('type') === 'entrada' ? 'checked' : '' }}
                                />
                                <div class="px-4 py-2 rounded-lg border transition-all duration-200 text-md font-medium
                                    peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary
                                    bg-white text-primary border-primary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>

                                    Entrada
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="type"
                                    value="saida"
                                    class="sr-only peer"
                                    {{ old('type') === 'saida' ? 'checked' : '' }}
                                />
                                <div class="px-4 py-2 rounded-lg border transition-all duration-200 text-md font-medium
                                    peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500
                                    bg-white text-orange-500 border-orange-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>

                                    Saída
                                </div>
                        </div>
                    </div>


                    <x-input-currency label="Valor" name="amount" prefix="R$" required />

                    <x-input-date label="Data da Ação" name="action_date" required />

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
