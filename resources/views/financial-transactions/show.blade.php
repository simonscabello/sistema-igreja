<x-app-layout>
    <x-page-card title="Detalhes da Transação">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-300 mb-4">Informações da Transação</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-300">{{ $financialTransaction->action_date->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-300">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $financialTransaction->type === 'entrada' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $financialTransaction->type === 'entrada' ? 'Entrada' : 'Saída' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-gray-300">R$ {{ number_format($financialTransaction->amount, 2, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-300 mb-4">Categorização</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Categoria</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-300">{{ $financialTransaction->subcategory->financialCategory->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subcategoria</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-300">{{ $financialTransaction->subcategory->name }}</dd>
                        </div>
                        @if($financialTransaction->campaign)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Campanha</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-300">{{ $financialTransaction->campaign->name }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            @if($financialTransaction->description)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-300 mb-4">Descrição</h3>
                    <p class="text-sm text-gray-900 dark:text-gray-300 whitespace-pre-line">{{ $financialTransaction->description }}</p>
                </div>
            @endif

            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('financial-transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Voltar
                    </a>
                    <x-link-button href="{{ route('financial-transactions.edit', $financialTransaction) }}">
                        Editar
                    </x-link-button>
                    <form action="{{ route('financial-transactions.destroy', $financialTransaction) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit">
                            Excluir
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </div>
    </x-page-card>
</x-app-layout> 