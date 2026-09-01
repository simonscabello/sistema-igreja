import { useCallback, useEffect, useMemo, useState } from 'react';
import axios from 'axios';
import Chart from 'react-apexcharts';
import type { ApexOptions } from 'apexcharts';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Button, CreateButton } from '@/components/ui/Button';
import { PageCard } from '@/components/ui/PageCard';
import { Card, CardContent } from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { useTheme } from '@/hooks/useTheme';
import { cn, formatCurrency, route } from '@/utils';
import { DashboardData } from '../types';

const PERIOD_OPTIONS = [
    { value: 7, label: '7 dias' },
    { value: 30, label: '30 dias' },
    { value: 90, label: '90 dias' },
];

function Index() {
    const { theme } = useTheme();
    const [period, setPeriod] = useState(30);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(false);
    const [data, setData] = useState<DashboardData | null>(null);

    const isDark = theme === 'dark';
    const labelColor = isDark ? '#A1A1AA' : '#71717A';
    const gridColor = isDark ? '#27272A' : '#E4E4E7';

    const loadData = useCallback(async (selectedPeriod: number) => {
        setLoading(true);
        setError(false);

        try {
            const response = await axios.get<DashboardData>(route('financial.dashboard.data'), {
                params: { period: selectedPeriod },
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            setData(response.data);
        } catch {
            setError(true);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        loadData(period);
    }, [loadData, period]);

    const chartSeries = useMemo(() => {
        if (!data) {
            return [
                { name: 'Entradas', data: [] as Array<{ x: number; y: number }> },
                { name: 'Saídas', data: [] as Array<{ x: number; y: number }> },
            ];
        }

        const dates = [...new Set([...Object.keys(data.entradas), ...Object.keys(data.saidas)])].sort(
            (a, b) => new Date(a).getTime() - new Date(b).getTime(),
        );

        if (dates.length === 0) {
            const today = Date.now();

            return [
                { name: 'Entradas', data: [{ x: today, y: 0 }] },
                { name: 'Saídas', data: [{ x: today, y: 0 }] },
            ];
        }

        return [
            {
                name: 'Entradas',
                data: dates.map((date) => ({ x: new Date(date).getTime(), y: data.entradas[date] ?? 0 })),
            },
            {
                name: 'Saídas',
                data: dates.map((date) => ({ x: new Date(date).getTime(), y: data.saidas[date] ?? 0 })),
            },
        ];
    }, [data]);

    const chartOptions: ApexOptions = useMemo(
        () => ({
            chart: {
                type: 'area',
                height: 320,
                fontFamily: 'Figtree, sans-serif',
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 250 },
                background: 'transparent',
            },
            colors: ['#1F7A4D', '#B42318'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 90, 100] },
            },
            xaxis: {
                type: 'datetime',
                labels: { style: { colors: labelColor } },
                axisBorder: { color: gridColor },
                axisTicks: { color: gridColor },
            },
            yaxis: {
                labels: {
                    formatter: (value: number) => formatCurrency(value),
                    style: { colors: labelColor },
                },
            },
            tooltip: { theme: isDark ? 'dark' : 'light', shared: true, intersect: false },
            legend: { show: false },
            grid: { borderColor: gridColor, strokeDashArray: 4 },
            noData: {
                text: 'Sem lançamentos neste período',
                align: 'center',
                verticalAlign: 'middle',
                style: { color: labelColor, fontSize: '14px' },
            },
        }),
        [gridColor, isDark, labelColor],
    );

    return (
        <AppPage>
            <PageCard
                title="Caixa"
                description="Entradas e saídas do período. O saldo considera só estas datas."
                action={
                    <CreateButton href={route('financial.transactions.create')} size="sm">
                        Nova transação
                    </CreateButton>
                }
            >
                <div className="mb-6 flex flex-wrap items-end justify-between gap-4">
                    <dl className="flex flex-wrap gap-x-8 gap-y-3">
                        <div>
                            <dt className="text-sm text-muted-foreground">Entradas</dt>
                            <dd className="tabular text-xl font-semibold text-entrada">{formatCurrency(data?.total_entradas ?? 0)}</dd>
                        </div>
                        <div>
                            <dt className="text-sm text-muted-foreground">Saídas</dt>
                            <dd className="tabular text-xl font-semibold text-saida">{formatCurrency(data?.total_saidas ?? 0)}</dd>
                        </div>
                        <div>
                            <dt className="text-sm text-muted-foreground">Saldo do período</dt>
                            <dd className={cn('tabular text-xl font-semibold', (data?.saldo ?? 0) >= 0 ? 'text-foreground' : 'text-saida')}>
                                {formatCurrency(data?.saldo ?? 0)}
                            </dd>
                        </div>
                    </dl>

                    <Tabs value={String(period)} onValueChange={(value) => setPeriod(Number(value))}>
                        <TabsList>
                            {PERIOD_OPTIONS.map((option) => (
                                <TabsTrigger key={option.value} value={String(option.value)}>
                                    {option.label}
                                </TabsTrigger>
                            ))}
                        </TabsList>
                    </Tabs>
                </div>

                <Card className="shadow-none">
                    <CardContent className="p-4 sm:p-5">
                        {error ? (
                            <div className="flex h-80 flex-col items-center justify-center gap-3 text-sm text-muted-foreground">
                                Não foi possível carregar o gráfico.
                                <Button variant="secondary" size="sm" onClick={() => loadData(period)}>
                                    Tentar de novo
                                </Button>
                            </div>
                        ) : loading && !data ? (
                            <div className="flex h-80 flex-col justify-center gap-3">
                                <Skeleton className="h-8 w-40" />
                                <Skeleton className="h-64 w-full" />
                            </div>
                        ) : (
                            <div className={loading ? 'opacity-60' : undefined}>
                                <Chart options={chartOptions} series={chartSeries} type="area" height={320} />
                            </div>
                        )}
                    </CardContent>
                </Card>
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
