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
    <!-- ApexCharts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.2.0/apexcharts.min.js"></script>
    
    <style>
        /* Correções específicas para o gráfico */
        #financial-chart .apexcharts-canvas {
            cursor: default !important;
        }
        
        #financial-chart .apexcharts-tooltip {
            cursor: default !important;
        }
        
        #financial-chart .apexcharts-legend {
            cursor: default !important;
        }
        
        #financial-chart .apexcharts-xaxis, 
        #financial-chart .apexcharts-yaxis {
            cursor: default !important;
        }
        
        /* Garante que os elementos do gráfico tenham o cursor correto */
        #financial-chart svg * {
            cursor: default !important;
        }
        
        /* Melhora a visibilidade dos markers */
        #financial-chart .apexcharts-marker {
            cursor: crosshair !important;
        }
        
        /* Melhora a responsividade do tooltip */
        #financial-chart .apexcharts-tooltip-series-group {
            pointer-events: none;
        }
        
        /* Garante que o tooltip seja visível */
        #financial-chart .apexcharts-tooltip {
            z-index: 1000 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }
        
        /* Melhora a área do gráfico */
        #financial-chart .apexcharts-svg {
            overflow: visible !important;
        }
    </style>
    
    <script>
        let chart = null;
        let currentPeriod = 'last_30_days';

        function initChart() {
            const options = {
                series: [
                    {
                        name: 'Entradas',
                        data: [],
                        color: '#10B981' // Verde para entradas
                    },
                    {
                        name: 'Saídas',
                        data: [],
                        color: '#EF4444' // Vermelho para saídas
                    }
                ],
                chart: {
                    type: 'area',
                    height: 384,
                    background: 'transparent',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                plotOptions: {
                    area: {
                        fillTo: 'origin'
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                markers: {
                    size: 0,
                    hover: {
                        sizeOffset: 6
                    }
                },
                states: {
                    hover: {
                        filter: {
                            type: 'lighten',
                            value: 0.15
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'darken',
                            value: 0.35
                        }
                    }
                },
                grid: {
                    strokeDashArray: 4,
                    borderColor: '#E5E7EB',
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                xaxis: {
                    categories: [],
                    labels: {
                        style: {
                            colors: '#6B7280'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        },
                        style: {
                            colors: '#6B7280'
                        }
                    }
                },
                tooltip: {
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                    shared: true,
                    intersect: false,
                    marker: {
                        show: true
                    },
                    y: {
                        formatter: function (value, { seriesIndex, dataPointIndex, w }) {
                            return 'R$ ' + value.toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        },
                        title: {
                            formatter: function (seriesName) {
                                return seriesName + ':';
                            }
                        }
                    },
                    x: {
                        formatter: function (value, { dataPointIndex, w }) {
                            return 'Data: ' + w.globals.categoryLabels[dataPointIndex];
                        }
                    },
                    followCursor: false,
                    style: {
                        fontSize: '12px',
                        fontFamily: 'inherit'
                    }
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetX: 0,
                    offsetY: 0,
                    labels: {
                        colors: '#6B7280',
                        useSeriesColors: false
                    },
                    markers: {
                        width: 12,
                        height: 12,
                        strokeWidth: 0,
                        radius: 12,
                        offsetX: 0,
                        offsetY: 0
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 0
                    },
                    onItemClick: {
                        toggleDataSeries: true
                    },
                    onItemHover: {
                        highlightDataSeries: true
                    }
                },
                responsive: [
                    {
                        breakpoint: 768,
                        options: {
                            chart: {
                                height: 300
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                ]
            };

            chart = new ApexCharts(document.querySelector("#financial-chart"), options);
            chart.render();
        }

        function updateChart(period = 'last_30_days') {
            currentPeriod = period;
            
            fetch(`{{ route('financial.dashboard.data') }}?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    // Atualizar o gráfico com animação
                    chart.updateSeries([
                        {
                            name: 'Entradas',
                            data: data.chart_data.entradas,
                            color: '#10B981'
                        },
                        {
                            name: 'Saídas',
                            data: data.chart_data.saidas,
                            color: '#EF4444'
                        }
                    ], true);
                    
                    chart.updateOptions({
                        xaxis: {
                            categories: data.chart_data.categories,
                            labels: {
                                style: {
                                    colors: '#6B7280'
                                }
                            }
                        },
                        tooltip: {
                            shared: true,
                            intersect: false
                        }
                    }, false, true);

                    // Atualizar informações do resumo
                    updateSummary(data.summary, data.period_info);
                })
                .catch(error => {
                    console.error('Erro ao carregar dados:', error);
                });
        }

        function updateSummary(summary, periodInfo) {
            // Atualizar valor total (saldo)
            const totalElement = document.getElementById('total-amount');
            const saldo = summary.saldo;
            totalElement.textContent = 'R$ ' + Math.abs(saldo).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Atualizar subtítulo do período
            const subtitleElement = document.getElementById('period-subtitle');
            subtitleElement.textContent = `Saldo em ${periodInfo.display_name}`;

            // Atualizar indicador de saldo (positivo/negativo)
            const balanceIndicator = document.getElementById('balance-indicator');
            const balancePercentage = document.getElementById('balance-percentage');
            const balanceArrow = document.getElementById('balance-arrow');

            if (saldo > 0) {
                balanceIndicator.className = 'flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center';
                balanceArrow.style.transform = 'rotate(0deg)';
                balancePercentage.textContent = '+' + ((summary.total_entradas > 0 ? (saldo / summary.total_entradas * 100) : 0).toFixed(1)) + '%';
            } else if (saldo < 0) {
                balanceIndicator.className = 'flex items-center px-2.5 py-0.5 text-base font-semibold text-red-500 dark:text-red-500 text-center';
                balanceArrow.style.transform = 'rotate(180deg)';
                balancePercentage.textContent = ((summary.total_saidas > 0 ? (Math.abs(saldo) / summary.total_saidas * 100) : 0).toFixed(1)) + '%';
            } else {
                balanceIndicator.className = 'flex items-center px-2.5 py-0.5 text-base font-semibold text-gray-500 dark:text-gray-500 text-center';
                balancePercentage.textContent = '0%';
            }

            balanceIndicator.classList.remove('hidden');
        }

        // Event listeners para os períodos
        document.addEventListener('DOMContentLoaded', function() {
            initChart();
            updateChart(currentPeriod);

            // Event listeners para as opções de período
            document.querySelectorAll('.period-option').forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const period = this.getAttribute('data-period');
                    const periodText = this.textContent;
                    
                    document.getElementById('current-period').textContent = periodText;
                    updateChart(period);
                });
            });

            // Event listener para mudanças de tema (dark mode)
            const themeObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        chart.updateOptions({
                            tooltip: {
                                theme: isDark ? 'dark' : 'light',
                                shared: true,
                                intersect: false,
                                marker: {
                                    show: true
                                }
                            },
                            grid: {
                                borderColor: isDark ? '#374151' : '#E5E7EB'
                            },
                            xaxis: {
                                labels: {
                                    style: {
                                        colors: isDark ? '#9CA3AF' : '#6B7280'
                                    }
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        colors: isDark ? '#9CA3AF' : '#6B7280'
                                    }
                                }
                            },
                            legend: {
                                labels: {
                                    colors: isDark ? '#9CA3AF' : '#6B7280'
                                }
                            }
                        });
                    }
                });
            });

            themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>
    @endpush
</x-app-layout> 