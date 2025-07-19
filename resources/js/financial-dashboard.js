import ApexCharts from 'apexcharts';
// Dashboard Financeiro - JavaScript

class FinancialDashboard {
    constructor() {
        this.chart = null;
        this.currentPeriod = 30;
        this.init();
    }

    init() {
        this.setupDropdown();
        this.createChart();
        this.loadData();
    }

    setupDropdown() {
        const dropdownButton = document.getElementById('periodDropdown');
        const dropdownMenu = document.getElementById('periodDropdownMenu');
        const periodOptions = document.querySelectorAll('.period-option');

        // Toggle dropdown
        dropdownButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });

        // Fechar dropdown ao clicar fora
        document.addEventListener('click', (e) => {
            if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        // Seleção de período
        periodOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                e.preventDefault();
                const period = parseInt(option.dataset.period);
                this.currentPeriod = period;
                
                // Atualizar texto do botão
                document.getElementById('selectedPeriod').textContent = option.textContent;
                
                // Fechar dropdown
                dropdownMenu.classList.add('hidden');
                
                // Recarregar dados
                this.loadData();
            });
        });
    }

    createChart() {
        const self = this;
        const options = {
            series: [
                {
                    name: 'Entradas',
                    data: [],
                    color: '#10B981'
                },
                {
                    name: 'Saídas',
                    data: [],
                    color: '#EF4444'
                }
            ],
            noData: {
                text: 'Nenhum dado disponível para o período selecionado',
                align: 'center',
                verticalAlign: 'middle',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: self.isDarkMode() ? '#9CA3AF' : '#6B7280',
                    fontSize: '14px',
                    fontFamily: 'Fira Sans, sans-serif'
                }
            },
            chart: {
                type: 'area',
                height: 320,
                fontFamily: 'Fira Sans, sans-serif',
                toolbar: {
                    show: false
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
            colors: ['#10B981', '#EF4444'], // Verde para entradas, vermelho para saídas
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                type: 'datetime',
                labels: {
                    show: true,
                    style: {
                        colors: self.isDarkMode() ? '#9CA3AF' : '#6B7280'
                    }
                },
                axisBorder: {
                    color: self.isDarkMode() ? '#374151' : '#E5E7EB'
                },
                axisTicks: {
                    color: self.isDarkMode() ? '#374151' : '#E5E7EB'
                }
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    },
                    style: {
                        colors: self.isDarkMode() ? '#9CA3AF' : '#6B7280'
                    }
                }
            },
            tooltip: {
                enabled: true,
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                shared: true,
                intersect: false,
                followCursor: true,
                marker: {
                    show: true
                },
                custom: function({ series, seriesIndex, dataPointIndex, w }) {
                    // Obter a data bruta do eixo X
                    const rawDate = w.globals.labels[dataPointIndex];
                
                    // Formatar a data para o padrão brasileiro
                    let categoryName;
                    if (rawDate) {
                        const dateObj = new Date(rawDate);
                        categoryName = dateObj.toLocaleDateString('pt-BR', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                    } else {
                        categoryName = `Dia ${dataPointIndex + 1}`;
                    }
                
                    // Obter os valores das séries
                    const entradas = series[0] ? series[0][dataPointIndex] : 0;
                    const saidas = series[1] ? series[1][dataPointIndex] : 0;
                
                    // Formatar valores monetários
                    const formatValue = (value) => {
                        return 'R$ ' + (value || 0).toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    };
                
                    // Determinar tema atual
                    const isDark = document.documentElement.classList.contains('dark');
                    const bgColor = isDark ? '#374151' : '#ffffff';
                    const textColor = isDark ? '#f9fafb' : '#111827';
                    const borderColor = isDark ? '#4b5563' : '#e5e7eb';
                
                    return `
                        <div style="
                            background: ${bgColor};
                            color: ${textColor};
                            padding: 12px 16px;
                            border-radius: 8px;
                            border: 1px solid ${borderColor};
                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                            font-family: inherit;
                            font-size: 12px;
                            min-width: 180px;
                        ">
                            <div style="display: flex; align-items: center; margin-bottom: 6px;">
                                <div style="
                                    width: 8px;
                                    height: 8px;
                                    background-color: #10B981;
                                    border-radius: 50%;
                                    margin-right: 8px;
                                "></div>
                                <span style="color: ${textColor};">
                                    <strong>Entradas:</strong> ${formatValue(entradas)}
                                </span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <div style="
                                    width: 8px;
                                    height: 8px;
                                    background-color: #EF4444;
                                    border-radius: 50%;
                                    margin-right: 8px;
                                "></div>
                                <span style="color: ${textColor};">
                                    <strong>Saídas:</strong> ${formatValue(saidas)}
                                </span>
                            </div>
                            <div style="font-weight: 600; padding-top: 4px; border-top: 1px solid ${borderColor}; color: ${textColor};">
                                ${categoryName}
                            </div>
                        </div>
                    `;
                }
                
            },
    
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '14px',
                fontFamily: 'Fira Sans, sans-serif',
                labels: {
                    colors: self.isDarkMode() ? '#9CA3AF' : '#6B7280'
                },
                markers: {
                    width: 12,
                    height: 12,
                    radius: 6,
                    strokeWidth: 0,
                    strokeColors: ['#10B981', '#EF4444'],
                    fillColors: ['#10B981', '#EF4444']
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                }
            },
            grid: {
                show: true,
                borderColor: self.isDarkMode() ? '#374151' : '#E5E7EB',
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: 0,
                    bottom: 0
                  },
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
            responsive: [
                {
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 250
                        },
                        legend: {
                            show: true,
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '12px'
                        }
                    }
                }
            ]
        };

        this.chart = new ApexCharts(document.querySelector("#financialChart"), options);
        this.chart.render();
    }

    async loadData() {
        try {
            // Adicionar loading
            this.setLoading(true);

            const response = await fetch(`/financial-dashboard/data?period=${this.currentPeriod}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Erro ao carregar dados');
            }

            const data = await response.json();
            this.updateChart(data);
            this.updateSummary(data);

        } catch (error) {
            console.error('Erro ao carregar dados:', error);
            this.showError('Erro ao carregar dados do dashboard');
        } finally {
            this.setLoading(false);
        }
    }

    updateChart(data) {
        let dates = Object.keys(data.entradas).concat(Object.keys(data.saidas))
            .filter((value, index, self) => self.indexOf(value) === index)
            .sort((a, b) => new Date(a) - new Date(b));
    
        const entradasData = dates.map(date => ({
            x: new Date(date).getTime(),
            y: data.entradas[date] || 0
        }));
    
        const saidasData = dates.map(date => ({
            x: new Date(date).getTime(),
            y: data.saidas[date] || 0
        }));
    
        if (entradasData.length === 0 && saidasData.length === 0) {
            const today = new Date().getTime();
            entradasData.push({ x: today, y: 0 });
            saidasData.push({ x: today, y: 0 });
            dates = [today]; // para o min/max
        }
    
        this.chart.updateSeries([
            {
                name: 'Entradas',
                data: entradasData,
                color: '#10B981'
            },
            {
                name: 'Saídas',
                data: saidasData,
                color: '#EF4444'
            }
        ]);
    
        // 🔷 FORÇA os limites do eixo X
        this.chart.updateOptions({
            xaxis: {
                min: new Date(dates[0]).getTime(),
                max: new Date(dates[dates.length - 1]).getTime()
            }
        });
    }

    
    updateSummary(data) {
        document.getElementById('totalEntradas').textContent = 
            'R$ ' + data.total_entradas.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

        document.getElementById('totalSaidas').textContent = 
            'R$ ' + data.total_saidas.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

        document.getElementById('saldo').textContent = 
            'R$ ' + data.saldo.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

        // Atualizar cor do saldo baseado no valor
        const saldoElement = document.getElementById('saldo');
        if (data.saldo >= 0) {
            saldoElement.className = 'text-2xl font-semibold text-green-600 dark:text-green-400';
        } else {
            saldoElement.className = 'text-2xl font-semibold text-red-600 dark:text-red-400';
        }
    }

    setLoading(loading) {
        const chartContainer = document.getElementById('financialChart');
        if (loading) {
            chartContainer.classList.add('loading');
        } else {
            chartContainer.classList.remove('loading');
        }
    }

    showError(message) {
        // Implementar notificação de erro se necessário
        console.error(message);
    }

    isDarkMode() {
        return document.documentElement.classList.contains('dark');
    }
}

// Inicializar dashboard quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new FinancialDashboard();
}); 