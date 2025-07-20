<x-app-layout>
    <x-page-card title="Transações Financeiras" actions="{{ route('financial.transactions.create') }}">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="flex flex-col gap-4 mb-4">
            <div class="flex-1">
                <form action="{{ route('financial.transactions.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <x-text-input name="search" placeholder="Buscar transações..." value="{{ request('search') }}" class="sm:col-span-2" />
                    <x-select label="" name="type" :options="['' => 'Todos os tipos', 'entrada' => 'Entradas', 'saida' => 'Saídas']" :selected="request('type')" class="text-sm" />
                    <x-select label="" name="subcategory" :options="$categories->flatMap(function($category) { return $category->subcategories->pluck('name', 'id'); })->prepend('Todas as subcategorias', '')" :selected="request('subcategory', '')" class="text-sm" />
                    <div class="flex items-end sm:col-span-1">
                        <x-primary-button type="submit" class="w-full px-4 py-3 text-sm">Buscar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Layout Desktop -->
        <div class="hidden lg:block overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                <thead class="bg-neutral-light dark:bg-gray-700">
                    <tr>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Data</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Subcategoria</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Campanha</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Valor</th>
                        <th class="px-4 xl:px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                <div class="flex items-center space-x-2">
                                    <span>{{ $transaction->action_date->format('d/m/Y') }}</span>
                                    @if($transaction->files('comprovantes')->exists())
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Possui anexo">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{{ $transaction->subcategory->name }}</td>
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                @if($transaction->campaign)
                                    <a href="{{ route('financial.campaigns.show', $transaction->campaign) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ $transaction->campaign->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'entrada' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $transaction->type === 'entrada' ? 'Entrada' : 'Saída' }}
                                </span>
                            </td>
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <x-link-button href="{{ route('financial.transactions.show', $transaction) }}" class="text-xs px-3 py-1">
                                        Ver
                                    </x-link-button>
                                    <x-link-button href="{{ route('financial.transactions.edit', $transaction) }}" class="text-xs px-3 py-1">
                                        Editar
                                    </x-link-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 xl:px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhuma transação encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Layout Mobile/Tablet -->
        <div class="lg:hidden space-y-3">
            @forelse ($transactions as $transaction)
                <div class="bg-white dark:bg-gray-700 border border-neutral-medium dark:border-gray-600 rounded-lg p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="text-base font-semibold text-neutral-dark dark:text-gray-300 flex items-center space-x-2">
                            <span>{{ $transaction->action_date->format('d/m/Y') }}</span>
                            @if($transaction->files('comprovantes')->exists())
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Possui anexo">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            @endif
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->type === 'entrada' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $transaction->type === 'entrada' ? 'Entrada' : 'Saída' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Valor:</span>
                            <span class="font-semibold {{ $transaction->type === 'entrada' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Subcategoria:</span>
                            <span class="text-neutral-dark dark:text-gray-300">{{ $transaction->subcategory->name }}</span>
                        </div>
                        @if($transaction->campaign)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Campanha:</span>
                                <a href="{{ route('financial.campaigns.show', $transaction->campaign) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-right">
                                    {{ $transaction->campaign->name }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-neutral-medium dark:border-gray-600">
                        <x-link-button href="{{ route('financial.transactions.show', $transaction) }}" class="text-xs px-3 py-2 flex-1 text-center">
                            Ver
                        </x-link-button>
                        <x-link-button href="{{ route('financial.transactions.edit', $transaction) }}" class="text-xs px-3 py-2 flex-1 text-center">
                            Editar
                        </x-link-button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-neutral-medium dark:text-gray-500">
                    Nenhuma transação encontrada.
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </x-page-card>
</x-app-layout>
