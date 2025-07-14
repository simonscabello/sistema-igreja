<x-app-layout>
    <x-page-card title="Relatórios Financeiros">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Relatório Mensal -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Relatório Mensal</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Visualizar por mês</p>
                    </div>
                </div>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Relatório detalhado das transações financeiras agrupadas por categoria e subcategoria em um mês específico.</p>
                <a href="{{ route('reports.financial.monthly') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                    Visualizar
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Relatório Anual Detalhado -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Relatório Anual Detalhado</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Mês a mês com detalhes</p>
                    </div>
                </div>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Relatório completo com todas as transações do ano, organizadas mês a mês, por categoria e subcategoria.</p>
                <a href="{{ route('reports.financial.annual.detailed') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors duration-200">
                    Visualizar
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Relatório Anual Compilado -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Relatório Anual Compilado</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Resumo por categoria</p>
                    </div>
                </div>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Relatório resumido com totais mensais de entradas e saídas, agrupados por categoria financeira.</p>
                <a href="{{ route('reports.financial.annual.summary') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-md transition-colors duration-200">
                    Visualizar
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Informações sobre os Relatórios</h4>
            <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                <li>• <strong>Mensal:</strong> Ideal para análise detalhada de um período específico</li>
                <li>• <strong>Anual Detalhado:</strong> Visão completa de todas as transações organizadas por mês</li>
                <li>• <strong>Anual Compilado:</strong> Resumo executivo com totais por categoria e mês</li>
            </ul>
        </div>
    </x-page-card>
</x-app-layout> 