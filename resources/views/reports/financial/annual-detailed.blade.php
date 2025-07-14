<x-app-layout>
    <x-page-card title="Relatório Anual Detalhado">
        <!-- Navegação dos Relatórios -->
        <div class="mb-6 flex flex-wrap gap-2">
            <a href="{{ route('reports.financial.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar aos Relatórios
            </a>
            <a href="{{ route('reports.financial.monthly') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                Relatório Mensal
            </a>
            <span class="inline-flex items-center px-3 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-md">
                Anual Detalhado
            </span>
            <a href="{{ route('reports.financial.annual.summary') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                Anual Compilado
            </a>
        </div>

        <!-- Filtro -->
        <div class="mb-6">
            <form action="{{ route('reports.financial.annual.detailed') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
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

        @if(isset($report) && $report['yearly_totals']['entradas'] > 0 || $report['yearly_totals']['saidas'] > 0)
            <!-- Período do Relatório -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300">
                    Relatório Anual Detalhado de {{ $report['ano'] }}
                </h3>
            </div>

            <!-- Resumo Anual -->
            <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-6">
                <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Resumo Anual
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <div class="text-sm text-green-700 dark:text-green-300 mb-1">Total de Entradas</div>
                        <div class="text-2xl font-bold text-green-800 dark:text-green-200">
                            R$ {{ number_format($report['yearly_totals']['entradas'], 2, ',', '.') }}
                        </div>
                    </div>
                    
                    <div class="text-center p-4 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <div class="text-sm text-red-700 dark:text-red-300 mb-1">Total de Saídas</div>
                        <div class="text-2xl font-bold text-red-800 dark:text-red-200">
                            R$ {{ number_format($report['yearly_totals']['saidas'], 2, ',', '.') }}
                        </div>
                    </div>
                    
                    <div class="text-center p-4 {{ $report['saldo_anual'] >= 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }} rounded-lg">
                        <div class="text-sm {{ $report['saldo_anual'] >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }} mb-1">Saldo Anual</div>
                        <div class="text-2xl font-bold {{ $report['saldo_anual'] >= 0 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                            R$ {{ number_format($report['saldo_anual'], 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Relatório Mensal -->
            <div class="space-y-8">
                @foreach($report['monthly_data'] as $month => $data)
                    @if($data['has_transactions'])
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $data['mes_nome'] }}
                            </h3>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Entradas -->
                                <div>
                                    <h4 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-4 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Entradas
                                    </h4>
                                    
                                    @if(count($data['entradas']) > 0)
                                        <div class="space-y-4">
                                            @foreach($data['entradas'] as $categoria => $dados)
                                                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                                    <h5 class="font-semibold text-green-800 dark:text-green-300 mb-2">{{ $categoria }}</h5>
                                                    <div class="space-y-1">
                                                        @foreach($dados['subcategorias'] as $subcategoria => $valor)
                                                            <div class="flex justify-between items-center text-sm">
                                                                <span class="text-gray-700 dark:text-gray-300">{{ $subcategoria }}</span>
                                                                <span class="font-medium text-green-600 dark:text-green-400">
                                                                    R$ {{ number_format($valor, 2, ',', '.') }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="mt-2 pt-2 border-t border-green-200 dark:border-green-800">
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-sm font-semibold text-green-800 dark:text-green-300">Total:</span>
                                                            <span class="font-bold text-green-700 dark:text-green-400">
                                                                R$ {{ number_format($dados['total_categoria'], 2, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                            <p class="text-sm">Nenhuma entrada registrada</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Saídas -->
                                <div>
                                    <h4 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                        Saídas
                                    </h4>
                                    
                                    @if(count($data['saidas']) > 0)
                                        <div class="space-y-4">
                                            @foreach($data['saidas'] as $categoria => $dados)
                                                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                                                    <h5 class="font-semibold text-red-800 dark:text-red-300 mb-2">{{ $categoria }}</h5>
                                                    <div class="space-y-1">
                                                        @foreach($dados['subcategorias'] as $subcategoria => $valor)
                                                            <div class="flex justify-between items-center text-sm">
                                                                <span class="text-gray-700 dark:text-gray-300">{{ $subcategoria }}</span>
                                                                <span class="font-medium text-red-600 dark:text-red-400">
                                                                    R$ {{ number_format($valor, 2, ',', '.') }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="mt-2 pt-2 border-t border-red-200 dark:border-red-800">
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-sm font-semibold text-red-800 dark:text-red-300">Total:</span>
                                                            <span class="font-bold text-red-700 dark:text-red-400">
                                                                R$ {{ number_format($dados['total_categoria'], 2, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                            <p class="text-sm">Nenhuma saída registrada</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Saldo Mensal -->
                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Saldo do Mês:</span>
                                    <span class="text-lg font-bold {{ $data['saldo_mensal'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        R$ {{ number_format($data['saldo_mensal'], 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @elseif(isset($report))
            <!-- Período do Relatório -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300">
                    Relatório Anual Detalhado de {{ $report['ano'] }}
                </h3>
            </div>
            
            <!-- Mensagem de nenhum dado -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum dado encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400">Não há transações registradas para {{ $report['ano'] }}.</p>
            </div>
        @else
            <!-- Estado inicial - sem dados -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Selecione um ano</h3>
                <p class="text-gray-500 dark:text-gray-400">Escolha o ano para visualizar o relatório anual detalhado.</p>
            </div>
        @endif
    </x-page-card>
</x-app-layout> 