import ApexCharts from 'apexcharts';

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
            enabled: true,
            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
            shared: true,
            intersect: false,
            followCursor: true,
            marker: {
                show: false
            },
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                // Obter o nome da categoria (data)
                const categoryName = w.globals.categoryLabels && w.globals.categoryLabels[dataPointIndex]
                    ? w.globals.categoryLabels[dataPointIndex]
                    : `Dia ${dataPointIndex + 1}`;

                // Obter os valores das séries
                const entradas = series[0] ? series[0][dataPointIndex] : 0;
                const saidas = series[1] ? series[1][dataPointIndex] : 0;

                // Formatar valores
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

    fetch(`/financial/dashboard/data?period=${period}`)
        .then(response => response.json())
        .then(data => {
            // Garantir que as séries e categorias tenham o mesmo número de elementos
            const maxLength = Math.max(
                data.chart_data.entradas.length,
                data.chart_data.saidas.length,
                data.chart_data.categories.length
            );

            const entradas = data.chart_data.entradas.slice(0, maxLength);
            const saidas = data.chart_data.saidas.slice(0, maxLength);
            let categories = data.chart_data.categories.slice(0, maxLength);

            // Preencher arrays menores com zeros se necessário
            while (entradas.length < maxLength) entradas.push(0);
            while (saidas.length < maxLength) saidas.push(0);

            // Garantir que todas as categorias sejam válidas
            for (let i = 0; i < maxLength; i++) {
                if (!categories[i] || categories[i].trim() === '') {
                    categories[i] = `Dia ${i + 1}`;
                }
            }

            // Atualizar o gráfico com animação
            chart.updateSeries([
                {
                    name: 'Entradas',
                    data: entradas,
                    color: '#10B981'
                },
                {
                    name: 'Saídas',
                    data: saidas,
                    color: '#EF4444'
                }
            ], true);

            // Determinar tema atual para aplicar as cores corretas
            const isDark = document.documentElement.classList.contains('dark');

            chart.updateOptions({
                xaxis: {
                    categories: categories,
                    labels: {
                        style: {
                            colors: isDark ? '#9CA3AF' : '#6B7280'
                        }
                    }
                },
                grid: {
                    borderColor: isDark ? '#374151' : '#E5E7EB'
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
                            colors: isDark ? '#9CA3AF' : '#6B7280'
                        }
                    }
                },
                legend: {
                    labels: {
                        colors: isDark ? '#9CA3AF' : '#6B7280'
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    x: {
                        show: false
                    }
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

                // Obter as categorias atuais para evitar perda de dados
                const currentCategories = chart.w.globals.categoryLabels || [];

                chart.updateOptions({
                    tooltip: {
                        theme: isDark ? 'dark' : 'light'
                    },
                    grid: {
                        borderColor: isDark ? '#374151' : '#E5E7EB'
                    },
                    xaxis: {
                        categories: currentCategories, // Manter as categorias originais
                        labels: {
                            style: {
                                colors: isDark ? '#9CA3AF' : '#6B7280'
                            }
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
                                colors: isDark ? '#9CA3AF' : '#6B7280'
                            }
                        }
                    },
                    legend: {
                        labels: {
                            colors: isDark ? '#9CA3AF' : '#6B7280'
                        }
                    }
                }, false, true);
            }
        });
    });

    themeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});
