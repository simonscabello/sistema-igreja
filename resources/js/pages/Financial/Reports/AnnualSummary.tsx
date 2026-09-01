import { FormEvent, useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { PrimaryButton } from '@/components/ui/Button';
import { Select } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { cn, formatCurrency, route } from '@/utils';
import { ReportNav } from '../components/ReportNav';
import { AnnualSummaryReport } from '../types';

interface AnnualSummaryProps {
    report: AnnualSummaryReport;
    availableYears: Record<number, number>;
}

function AnnualSummary({ report, availableYears }: AnnualSummaryProps) {
    const [year, setYear] = useState(String(report.ano));

    const yearOptions = Object.entries(availableYears).map(([value, label]) => ({
        value: Number(value),
        label: String(label),
    }));

    const hasData = report.yearly_totals.entradas > 0 || report.yearly_totals.saidas > 0;
    const monthlyRows = Object.entries(report.monthly_data)
        .sort(([a], [b]) => Number(a) - Number(b))
        .filter(([, data]) => data.has_transactions);

    const handleFilter = (event: FormEvent) => {
        event.preventDefault();
        router.get(route('financial.reports.annual.summary'), { year }, { preserveState: true });
    };

    return (
        <AppPage>
            <PageCard title="Ano resumido">
                <ReportNav current="annual-summary" />

                <form onSubmit={handleFilter} className="grid grid-cols-1 md:grid-cols-2 gap-4 items-end mb-6">
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
                            <h3 className="text-lg font-semibold text-blue-800 dark:text-blue-300">Relatório Anual Simplificado de {report.ano}</h3>
                            <p className="text-sm text-blue-600 dark:text-blue-400 mt-1">Totais mensais de entradas e saídas sem detalhamento por categoria</p>
                        </div>

                        <div className="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-6">
                            <h2 className="text-2xl font-bold text-blue-800 dark:text-blue-300 mb-6">Totais do Ano</h2>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div className="text-center p-4 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                    <div className="text-sm text-green-700 dark:text-green-300 mb-1">Total de Entradas</div>
                                    <div className="text-2xl font-bold text-green-800 dark:text-green-200">{formatCurrency(report.yearly_totals.entradas)}</div>
                                </div>
                                <div className="text-center p-4 bg-red-100 dark:bg-red-900/30 rounded-lg">
                                    <div className="text-sm text-red-700 dark:text-red-300 mb-1">Total de Saídas</div>
                                    <div className="text-2xl font-bold text-red-800 dark:text-red-200">{formatCurrency(report.yearly_totals.saidas)}</div>
                                </div>
                                <div className={cn('text-center p-4 rounded-lg', report.saldo_anual >= 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30')}>
                                    <div className={cn('text-sm mb-1', report.saldo_anual >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300')}>
                                        Saldo Anual
                                    </div>
                                    <div className={cn('text-2xl font-bold', report.saldo_anual >= 0 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200')}>
                                        {formatCurrency(report.saldo_anual)}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="mb-8">
                            <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-6">Totais Mensais</h3>
                            <div className="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead className="bg-gray-50 dark:bg-gray-900">
                                            <tr>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mês</th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Entradas</th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Saídas</th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Saldo do Mês</th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            {monthlyRows.map(([month, data]) => (
                                                <tr key={month} className="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{data.mes_nome}</td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400">{formatCurrency(data.total_entradas)}</td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400">{formatCurrency(data.total_saidas)}</td>
                                                    <td
                                                        className={cn(
                                                            'px-6 py-4 whitespace-nowrap text-sm font-medium',
                                                            data.saldo_mensal >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400',
                                                        )}
                                                    >
                                                        {formatCurrency(data.saldo_mensal)}
                                                    </td>
                                                </tr>
                                            ))}
                                            <tr className="bg-gray-100 dark:bg-gray-700 border-t-2 border-gray-300 dark:border-gray-600">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">TOTAL DO ANO</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 dark:text-green-400">{formatCurrency(report.yearly_totals.entradas)}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600 dark:text-red-400">{formatCurrency(report.yearly_totals.saidas)}</td>
                                                <td
                                                    className={cn(
                                                        'px-6 py-4 whitespace-nowrap text-sm font-bold',
                                                        report.saldo_anual >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400',
                                                    )}
                                                >
                                                    {formatCurrency(report.saldo_anual)}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </>
                ) : (
                    <div className="text-center py-12 text-gray-500 dark:text-gray-400">
                        <p>Não há transações registradas para {report.ano}.</p>
                    </div>
                )}
            </PageCard>
        </AppPage>
    );
}

AnnualSummary.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default AnnualSummary;
