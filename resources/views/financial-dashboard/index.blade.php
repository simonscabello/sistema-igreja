<x-app-layout>
    <x-page-card title="Dashboard Financeiro">
        <div class="w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6">
            <div class="flex justify-between mb-5">
                <div>
                    <h5 id="total-amount" class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">
                        R$ 0,00
                    </h5>
                    <p id="period-subtitle" class="text-base font-normal text-gray-500 dark:text-gray-400">
                        Carregando...
                    </p>
                </div>
                <div id="balance-indicator" class="flex items-center px-2.5 py-0.5 text-base font-semibold text-center hidden">
                    <span id="balance-percentage">0%</span>
                    <svg id="balance-arrow" class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                    </svg>
                </div>
            </div>

            <!-- Área do gráfico -->
            <div id="financial-chart" class="h-96" style="cursor: default;"></div>

            <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between mt-5">
                <div class="flex justify-between items-center pt-5">
                    <!-- Dropdown de períodos -->
                    <button
                        id="dropdownDefaultButton"
                        data-dropdown-toggle="lastDaysdropdown"
                        data-dropdown-placement="bottom"
                        class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                        type="button">
                        <span id="current-period">Últimos 30 dias</span>
                        <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>

                    <div id="lastDaysdropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                            <li><a href="#" data-period="yesterday" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white period-option">Ontem</a></li>
                            <li><a href="#" data-period="today" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white period-option">Hoje</a></li>
                            <li><a href="#" data-period="last_7_days" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white period-option">Últimos 7 dias</a></li>
                            <li><a href="#" data-period="last_30_days" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white period-option">Últimos 30 dias</a></li>
                            <li><a href="#" data-period="last_90_days" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white period-option">Últimos 90 dias</a></li>
                        </ul>
                    </div>

                    <!-- Link para relatórios -->
                    <a
                        href="{{ route('reports.financial.index') }}"
                        class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500 hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                        Relatório Financeiro
                        <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </x-page-card>

    @push('scripts')
        @vite(['resources/js/financial-dashboard.js'])
    @endpush
</x-app-layout>
