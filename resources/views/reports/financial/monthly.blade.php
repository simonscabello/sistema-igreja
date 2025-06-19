<x-app-layout>
    <x-page-card title="Relatório Financeiro Mensal">
        <!-- Filtro -->
        <div class="mb-6">
            <form action="{{ route('reports.financial.monthly') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <x-select 
                        label="Mês"
                        name="month" 
                        :options="[
                            1 => 'Janeiro',
                            2 => 'Fevereiro', 
                            3 => 'Março',
                            4 => 'Abril',
                            5 => 'Maio',
                            6 => 'Junho',
                            7 => 'Julho',
                            8 => 'Agosto',
                            9 => 'Setembro',
                            10 => 'Outubro',
                            11 => 'Novembro',
                            12 => 'Dezembro'
                        ]" 
                        :selected="request('month', now()->month)" 
                    />
                </div>
                <div>
                    <x-select 
                        label="Ano"
                        name="year" 
                        :options="[
                            now()->year - 5 => now()->year - 5,
                            now()->year - 4 => now()->year - 4,
                            now()->year - 3 => now()->year - 3,
                            now()->year - 2 => now()->year - 2,
                            now()->year - 1 => now()->year - 1,
                            now()->year => now()->year,
                            now()->year + 1 => now()->year + 1
                        ]" 
                        :selected="request('year', now()->year)" 
                    />
                </div>
                <div class="flex items-end">
                    <x-primary-button type="submit" class="px-6 py-3">
                        Filtrar
                    </x-primary-button>
                </div>
            </form>
        </div>

        @if(isset($report) && (count($report['entradas']) > 0 || count($report['saidas']) > 0))
            <!-- Período do Relatório -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300">
                    Relatório de {{ $report['periodo']['mes_nome'] }} de {{ $report['periodo']['ano'] }}
                </h3>
            </div>

            <!-- Seção de Entradas -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Entradas
                </h2>
                
                @if(count($report['entradas']) > 0)
                    <div class="space-y-6">
                        @foreach($report['entradas'] as $categoria => $dados)
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="bg-green-50 dark:bg-green-900/20 px-4 py-3 border-b border-green-200 dark:border-green-800">
                                    <h3 class="font-semibold text-green-800 dark:text-green-300">{{ $categoria }}</h3>
                                </div>
                                <div class="p-4">
                                    <div class="space-y-2">
                                        @foreach($dados['subcategorias'] as $subcategoria => $valor)
                                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                                <span class="text-gray-700 dark:text-gray-300">{{ $subcategoria }}</span>
                                                <span class="font-medium text-green-600 dark:text-green-400">
                                                    R$ {{ number_format($valor, 2, ',', '.') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-gray-900 dark:text-white">Total da Categoria:</span>
                                            <span class="font-bold text-lg text-green-600 dark:text-green-400">
                                                R$ {{ number_format($dados['total_categoria'], 2, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Nenhuma entrada registrada neste período.</p>
                    </div>
                @endif
            </div>

            <!-- Seção de Saídas -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                    Saídas
                </h2>
                
                @if(count($report['saidas']) > 0)
                    <div class="space-y-6">
                        @foreach($report['saidas'] as $categoria => $dados)
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="bg-red-50 dark:bg-red-900/20 px-4 py-3 border-b border-red-200 dark:border-red-800">
                                    <h3 class="font-semibold text-red-800 dark:text-red-300">{{ $categoria }}</h3>
                                </div>
                                <div class="p-4">
                                    <div class="space-y-2">
                                        @foreach($dados['subcategorias'] as $subcategoria => $valor)
                                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                                <span class="text-gray-700 dark:text-gray-300">{{ $subcategoria }}</span>
                                                <span class="font-medium text-red-600 dark:text-red-400">
                                                    R$ {{ number_format($valor, 2, ',', '.') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-gray-900 dark:text-white">Total da Categoria:</span>
                                            <span class="font-bold text-lg text-red-600 dark:text-red-400">
                                                R$ {{ number_format($dados['total_categoria'], 2, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Nenhuma saída registrada neste período.</p>
                    </div>
                @endif
            </div>

            <!-- Balancete -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-6">
                <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Balancete
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <div class="text-sm text-green-700 dark:text-green-300 mb-1">Total de Entradas</div>
                        <div class="text-2xl font-bold text-green-800 dark:text-green-200">
                            R$ {{ number_format($report['total_entradas'], 2, ',', '.') }}
                        </div>
                    </div>
                    
                    <div class="text-center p-4 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <div class="text-sm text-red-700 dark:text-red-300 mb-1">Total de Saídas</div>
                        <div class="text-2xl font-bold text-red-800 dark:text-red-200">
                            R$ {{ number_format($report['total_saidas'], 2, ',', '.') }}
                        </div>
                    </div>
                    
                    <div class="text-center p-4 {{ $report['saldo_mensal'] >= 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }} rounded-lg">
                        <div class="text-sm {{ $report['saldo_mensal'] >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }} mb-1">Saldo Mensal</div>
                        <div class="text-2xl font-bold {{ $report['saldo_mensal'] >= 0 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                            R$ {{ number_format($report['saldo_mensal'], 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif(isset($report))
            <!-- Período do Relatório -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300">
                    Relatório de {{ $report['periodo']['mes_nome'] }} de {{ $report['periodo']['ano'] }}
                </h3>
            </div>
            
            <!-- Mensagem de nenhum dado -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum dado encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400">Não há transações registradas para {{ $report['periodo']['mes_nome'] }} de {{ $report['periodo']['ano'] }}.</p>
            </div>
        @else
            <!-- Estado inicial - sem dados -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Selecione um período</h3>
                <p class="text-gray-500 dark:text-gray-400">Escolha o mês e ano para visualizar o relatório financeiro.</p>
            </div>
        @endif
    </x-page-card>
</x-app-layout> 