<x-app-layout>
    <x-page-card title="Detalhes da Campanha">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Informações da Campanha -->
            <div class="lg:w-1/3">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações Gerais</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nome</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $campaign->name }}</p>
                        </div>

                        @if($campaign->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $campaign->description }}</p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Meta</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">R$ {{ number_format($campaign->goal_amount, 2, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $campaign->status === 'ativo' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}{{ $campaign->status === 'encerrado' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}{{ $campaign->status === 'cancelada' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </div>

                        @if($campaign->start_date || $campaign->end_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Período</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($campaign->start_date && $campaign->end_date)
                                        {{ $campaign->start_date->format('d/m/Y') }} - {{ $campaign->end_date->format('d/m/Y') }}
                                    @elseif($campaign->start_date)
                                        A partir de {{ $campaign->start_date->format('d/m/Y') }}
                                    @elseif($campaign->end_date)
                                        Até {{ $campaign->end_date->format('d/m/Y') }}
                                    @endif
                                </p>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <x-link-button href="{{ route('campaigns.edit', $campaign) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar Campanha
                                </x-link-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progresso e Transações -->
            <div class="lg:w-2/3">
                <!-- Progresso -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Progresso Financeiro</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Arrecadado</span>
                            <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">R$ {{ number_format($campaign->progress, 2, ',', '.') }}</span>
                        </div>
                        
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                            <div class="bg-blue-600 h-4 rounded-full transition-all duration-300" style="width: {{ $campaign->progress_percentage }}%"></div>
                        </div>
                        
                        <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                            <span>{{ number_format($campaign->progress_percentage, 1) }}% da meta</span>
                            <span>Faltam R$ {{ number_format($campaign->goal_amount - $campaign->progress, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Transações -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Transações Relacionadas</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categoria</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subcategoria</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($campaign->transactions as $transaction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $transaction->action_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $transaction->subcategory->financialCategory->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $transaction->subcategory->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'entrada' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                {{ $transaction->type === 'entrada' ? 'Entrada' : 'Saída' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $transaction->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Nenhuma transação encontrada para esta campanha.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <x-secondary-button href="{{ route('campaigns.index') }}">
                Voltar para Campanhas
            </x-secondary-button>
            
            @if($campaign->status === 'ativo')
                <x-primary-button href="{{ route('financial-transactions.create', ['campaign_id' => $campaign->id]) }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Transação
                </x-primary-button>
            @endif
        </div>
    </x-page-card>
</x-app-layout> 