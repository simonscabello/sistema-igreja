<x-app-layout>
    <x-page-card title="Dashboard Financeiro">
        <div class="space-y-6">
            <!-- Card do gráfico -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-medium dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-neutral-dark dark:text-white">
                            Entradas vs Saídas
                        </h3>
                        
                        <!-- Dropdown para seleção de período -->
                        <div class="relative">
                            <button id="periodDropdown" 
                                    data-dropdown-toggle="periodDropdownMenu" 
                                    class="text-neutral-dark dark:text-white bg-neutral-light dark:bg-gray-700 hover:bg-neutral-medium dark:hover:bg-gray-600 focus:ring-4 focus:ring-primary/20 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center transition-colors duration-200" 
                                    type="button">
                                <span id="selectedPeriod">Últimos 30 dias</span>
                                <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div id="periodDropdownMenu" 
                                 class="z-10 hidden bg-white dark:bg-gray-700 divide-y divide-neutral-medium dark:divide-gray-600 rounded-lg shadow w-44 border border-neutral-medium dark:border-gray-600">
                                <ul class="py-2 text-sm text-neutral-dark dark:text-gray-200" aria-labelledby="periodDropdown">
                                    <li>
                                        <button type="button" 
                                                class="w-full text-left px-4 py-2 hover:bg-neutral-light dark:hover:bg-gray-600 transition-colors duration-200 period-option" 
                                                data-period="7">
                                            Últimos 7 dias
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" 
                                                class="w-full text-left px-4 py-2 hover:bg-neutral-light dark:hover:bg-gray-600 transition-colors duration-200 period-option" 
                                                data-period="30">
                                            Últimos 30 dias
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" 
                                                class="w-full text-left px-4 py-2 hover:bg-neutral-light dark:hover:bg-gray-600 transition-colors duration-200 period-option" 
                                                data-period="90">
                                            Últimos 90 dias
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Container do gráfico -->
                    <div id="financialChart" class="w-full h-80"></div>
                    
                    <!-- Legenda customizada -->
                    <div class="flex items-center justify-center mt-4">
                        <div class="flex items-center space-x-4 flex-wrap justify-center">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full" style="background-color: #10B981;"></div>
                                <span class="ml-2 text-sm text-neutral-dark dark:text-gray-300">Entradas</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full" style="background-color: #EF4444;"></div>
                                <span class="ml-2 text-sm text-neutral-dark dark:text-gray-300">Saídas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards de resumo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total de Entradas -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-medium dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-neutral-dark dark:text-gray-300">Total de Entradas</p>
                            <p id="totalEntradas" class="text-2xl font-semibold text-green-600 dark:text-green-400">R$ 0,00</p>
                        </div>
                    </div>
                </div>

                <!-- Total de Saídas -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-medium dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-neutral-dark dark:text-gray-300">Total de Saídas</p>
                            <p id="totalSaidas" class="text-2xl font-semibold text-red-600 dark:text-red-400">R$ 0,00</p>
                        </div>
                    </div>
                </div>

                <!-- Saldo -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-medium dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-light dark:bg-primary/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary dark:text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-neutral-dark dark:text-gray-300">Saldo</p>
                            <p id="saldo" class="text-2xl font-semibold text-primary dark:text-primary-light">R$ 0,00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-page-card>

</x-app-layout> 