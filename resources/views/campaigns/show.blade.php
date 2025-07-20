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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subcategoria</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($campaign->transactions as $transaction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $transaction->action_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $transaction->subcategory->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'entrada' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                {{ $transaction->type === 'entrada' ? 'Entrada' : 'Saída' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('financial.transactions.show', $transaction) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
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

        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="flex gap-4">
                <a href="{{ route('financial.campaigns.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Voltar
                </a>
                <x-link-button href="{{ route('financial.campaigns.edit', $campaign) }}">
                    Editar
                </x-link-button>
                <form action="{{ route('financial.campaigns.destroy', $campaign) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="submit">
                        Excluir
                    </x-danger-button>
                </form>
            </div>
        </div>
    </x-page-card>
</x-app-layout> 