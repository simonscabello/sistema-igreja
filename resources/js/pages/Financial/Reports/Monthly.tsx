import { FormEvent, useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { PrimaryButton } from '@/components/ui/Button';
import { Select } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { cn, formatCurrency, route } from '@/utils';
import { ReportNav } from '../components/ReportNav';
import { MonthlyReport } from '../types';

const MONTH_OPTIONS = [
    { value: 1, label: 'Janeiro' },
    { value: 2, label: 'Fevereiro' },
    { value: 3, label: 'Março' },
    { value: 4, label: 'Abril' },
    { value: 5, label: 'Maio' },
    { value: 6, label: 'Junho' },
    { value: 7, label: 'Julho' },
    { value: 8, label: 'Agosto' },
    { value: 9, label: 'Setembro' },
    { value: 10, label: 'Outubro' },
    { value: 11, label: 'Novembro' },
    { value: 12, label: 'Dezembro' },
];

interface MonthlyProps {
    report: MonthlyReport;
    availableYears: Record<number, number>;
}

function CategorySection({
    title,
    data,
    tone,
}: {
    title: string;
    data: MonthlyReport['entradas'];
    tone: 'green' | 'red';
}) {
    const entries = Object.entries(data);

    if (entries.length === 0) {
        return (
            <div className="text-center py-8 text-gray-500 dark:text-gray-400">
                <p>Nenhuma {title.toLowerCase()} registrada neste período.</p>
            </div>
        );
    }

    return (
        <div className="space-y-6">
            {entries.map(([categoryName, categoryData]) => (
                <div key={categoryName} className="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div
                        className={cn(
                            'px-4 py-3 border-b',
                            tone === 'green'
                                ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800'
                                : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
                        )}
                    >
                        <h3
                            className={cn(
                                'font-semibold',
                                tone === 'green' ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300',
                            )}
                        >
                            {categoryName}
                        </h3>
                    </div>
                    <div className="p-4">
                        <div className="space-y-2">
                            {Object.entries(categoryData.subcategorias).map(([subcategoryName, value]) => (
                                <div key={subcategoryName} className="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                    <span className="text-gray-700 dark:text-gray-300">{subcategoryName}</span>
                                    <span className={cn('font-medium', tone === 'green' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400')}>
                                        {formatCurrency(value)}
                                    </span>
                                </div>
                            ))}
                        </div>
                        <div className="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                            <span className="font-semibold text-gray-900 dark:text-white">Total da Categoria:</span>
                            <span className={cn('font-bold text-lg', tone === 'green' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400')}>
                                {formatCurrency(categoryData.total_categoria)}
                            </span>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
}

function Monthly({ report, availableYears }: MonthlyProps) {
    const [month, setMonth] = useState(String(report.periodo.mes));
    const [year, setYear] = useState(String(report.periodo.ano));

    const yearOptions = Object.entries(availableYears).map(([value, label]) => ({
        value: Number(value),
        label: String(label),
    }));

    const hasData = Object.keys(report.entradas).length > 0 || Object.keys(report.saidas).length > 0;

    const handleFilter = (event: FormEvent) => {
        event.preventDefault();
        router.get(route('financial.reports.monthly'), { month, year }, { preserveState: true });
    };

    return (
        <AppPage>
            <PageCard title="Balancete mensal">
                <ReportNav current="monthly" />

                <form onSubmit={handleFilter} className="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-6">
                    <Select id="month" label="Mês" value={month} onChange={(event) => setMonth(event.target.value)} options={MONTH_OPTIONS} />
                    <Select id="year" label="Ano" value={year} onChange={(event) => setYear(event.target.value)} options={yearOptions} placeholder="" />
                    <div className="flex items-end">
                        <PrimaryButton type="submit" className="px-6 py-3">
                            Filtrar
                        </PrimaryButton>
                    </div>
                </form>

                {hasData ? (
                    <>
                        <div className="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h3 className="text-lg font-semibold text-blue-800 dark:text-blue-300">
                                Relatório de {report.periodo.mes_nome} de {report.periodo.ano}
                            </h3>
                        </div>

                        <div className="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-6 mb-8">
                            <h2 className="text-2xl font-bold text-blue-800 dark:text-blue-300 mb-6">Balancete</h2>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div className="text-center p-4 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                    <div className="text-sm text-green-700 dark:text-green-300 mb-1">Total de Entradas</div>
                                    <div className="text-2xl font-bold text-green-800 dark:text-green-200">{formatCurrency(report.total_entradas)}</div>
                                </div>
                                <div className="text-center p-4 bg-red-100 dark:bg-red-900/30 rounded-lg">
                                    <div className="text-sm text-red-700 dark:text-red-300 mb-1">Total de Saídas</div>
                                    <div className="text-2xl font-bold text-red-800 dark:text-red-200">{formatCurrency(report.total_saidas)}</div>
                                </div>
                                <div
                                    className={cn(
                                        'text-center p-4 rounded-lg',
                                        report.saldo_mensal >= 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30',
                                    )}
                                >
                                    <div className={cn('text-sm mb-1', report.saldo_mensal >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300')}>
                                        Saldo Mensal
                                    </div>
                                    <div className={cn('text-2xl font-bold', report.saldo_mensal >= 0 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200')}>
                                        {formatCurrency(report.saldo_mensal)}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-green-600 dark:text-green-400 mb-4">Entradas</h2>
                            <CategorySection title="Entradas" data={report.entradas} tone="green" />
                        </div>

                        <div>
                            <h2 className="text-2xl font-bold text-red-600 dark:text-red-400 mb-4">Saídas</h2>
                            <CategorySection title="Saídas" data={report.saidas} tone="red" />
                        </div>
                    </>
                ) : (
                    <div className="text-center py-12 text-gray-500 dark:text-gray-400">
                        <p>Nenhuma transação encontrada para {report.periodo.mes_nome} de {report.periodo.ano}.</p>
                    </div>
                )}
            </PageCard>
        </AppPage>
    );
}

Monthly.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Monthly;
