<x-app-layout>
    <x-page-card title="Relatório Anual Simplificado">
        <!-- Navegação dos Relatórios -->
        <div class="mb-6 flex flex-wrap gap-2">
            <a href="{{ route('financial.reports.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar aos Relatórios
            </a>
            <a href="{{ route('financial.reports.monthly') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                Relatório Mensal
            </a>
            <a href="{{ route('financial.reports.annual.detailed') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                Anual Detalhado
            </a>
            <span class="inline-flex items-center px-3 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-md">
                Anual Simplificado
            </span>
        </div>

        <!-- Filtro -->
        <div class="mb-6">
            <form action="{{ route('financial.reports.annual.summary') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                                <div>
                    <x-select
                        label="Ano"
                        name="year"
                        :options="$availableYears"
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

        @if(isset($report) && ($report['yearly_totals']['entradas'] > 0 || $report['yearly_totals']['saidas'] > 0))
            <!-- Período do Relatório -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300">
                    Relatório Anual Simplificado de {{ $report['ano'] }}
                </h3>
                <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                    Totais mensais de entradas e saídas sem detalhamento por categoria
                </p>
            </div>

            <!-- Resumo Anual -->
            <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-6">
                <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Totais do Ano
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

            <!-- Tabela Mensal Simplificada -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Totais Mensais
                </h3>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Mês
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Entradas
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Saídas
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Saldo do Mês
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($report['monthly_data'] as $month => $data)
                                    @if($data['has_transactions'])
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $data['mes_nome'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400">
                                                R$ {{ number_format($data['total_entradas'], 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400">
                                                R$ {{ number_format($data['total_saidas'], 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $data['saldo_mensal'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                R$ {{ number_format($data['saldo_mensal'], 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach

                                <!-- Linha de totais -->
                                <tr class="bg-gray-100 dark:bg-gray-700 border-t-2 border-gray-300 dark:border-gray-600">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                        TOTAL DO ANO
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 dark:text-green-400">
                                        R$ {{ number_format($report['yearly_totals']['entradas'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600 dark:text-red-400">
                                        R$ {{ number_format($report['yearly_totals']['saidas'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $report['saldo_anual'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        R$ {{ number_format($report['saldo_anual'], 2, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @elseif(isset($report))
            <!-- Período do Relatório -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300">
                    Relatório Anual Simplificado de {{ $report['ano'] }}
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
                <p class="text-gray-500 dark:text-gray-400">Escolha o ano para visualizar o relatório anual simplificado.</p>
            </div>
        @endif
    </x-page-card>
</x-app-layout>
