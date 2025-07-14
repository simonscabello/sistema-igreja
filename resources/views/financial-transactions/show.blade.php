<x-app-layout>
    <x-page-card title="Detalhes da Transação Financeira">
        <div class="space-y-6">
            <!-- Card principal com informações -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Coluna esquerda - Informações da Transação -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-300 mb-6">Informações da Transação</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data da Transação</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ $financialTransaction->action_date->format('d/m/Y') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            {{ $financialTransaction->type === 'entrada'
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                            @if($financialTransaction->type === 'entrada')
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m0 0l-5-5m5 5l5-5" />
                                                </svg>
                                                Entrada
                                            @else
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-5 5m5-5l5 5" />
                                                </svg>
                                                Saída
                                            @endif
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor</dt>
                                    <dd class="mt-1 text-2xl font-bold {{ $financialTransaction->type === 'entrada' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        R$ {{ number_format($financialTransaction->amount, 2, ',', '.') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Coluna direita - Categorização -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-300 mb-6">Categorização</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Categoria</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ $financialTransaction->subcategory->financialCategory->name }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subcategoria</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ $financialTransaction->subcategory->name }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Campanha</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">
                                        @if($financialTransaction->campaign)
                                            <a href="{{ route('campaigns.show', $financialTransaction->campaign) }}"
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 underline">
                                                {{ $financialTransaction->campaign->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Nenhuma campanha</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descrição (se existir) -->
            @if($financialTransaction->description)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-5">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-300 mb-4">Descrição</h3>
                        <div class="text-sm text-gray-900 dark:text-gray-300 whitespace-pre-line bg-gray-50 dark:bg-gray-700 rounded-md p-4">{{ $financialTransaction->description }}</div>
                    </div>
                </div>
            @endif

            <!-- Informações de auditoria -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Informações do Sistema</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-500 dark:text-gray-400">
                    <div>
                        <span class="font-medium">Criado em:</span> {{ $financialTransaction->created_at->format('d/m/Y H:i') }}
                    </div>
                    @if($financialTransaction->updated_at != $financialTransaction->created_at)
                        <div>
                            <span class="font-medium">Última atualização:</span> {{ $financialTransaction->updated_at->format('d/m/Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Botões de ação -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex flex-wrap gap-3">
                    <!-- Botão Voltar -->
                    <a href="{{ route('financial-transactions.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Voltar
                    </a>

                    <!-- Botão Editar -->
                    @can('editar_transacoes')
                        <x-link-button href="{{ route('financial-transactions.edit', $financialTransaction) }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </x-link-button>
                    @endcan

                    <!-- Botão Excluir -->
                    @can('excluir_transacoes')
                        <form action="{{ route('financial-transactions.destroy', $financialTransaction) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Tem certeza que deseja excluir esta transação? Esta ação não pode ser desfeita.')">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Excluir
                            </x-danger-button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </x-page-card>
</x-app-layout>
