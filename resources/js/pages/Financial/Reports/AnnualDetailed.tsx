import { FormEvent, useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { PrimaryButton } from '@/components/ui/Button';
import { Select } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { cn, formatCurrency, formatDateBr, route } from '@/utils';
import { ReportNav } from '../components/ReportNav';
import { TransactionTypeBadge } from '../components/StatusBadge';
import { AnnualDetailedReport, FinancialCategory, FinancialTransaction } from '../types';

interface AnnualDetailedProps {
    report: AnnualDetailedReport;
    availableYears: Record<number, number>;
    categories: FinancialCategory[];
}

function MonthlyTransactionsTable({ transactions }: { transactions: FinancialTransaction[] }) {
    return (
        <table className="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
            <thead className="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Data</th>
                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Categoria</th>
                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subcategoria</th>
                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                    <th className="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Valor</th>
                </tr>
            </thead>
            <tbody className="divide-y divide-gray-200 dark:divide-gray-700">
                {transactions.map((transaction) => (
                    <tr key={transaction.id} className="hover:bg-gray-50 dark:hover:bg-gray-700" title={transaction.description ?? undefined}>
                        <td className="px-4 py-3 text-sm text-gray-900 dark:text-white">{formatDateBr(transaction.action_date)}</td>
                        <td className="px-4 py-3 text-sm text-gray-900 dark:text-white">{transaction.subcategory?.financial_category?.name ?? '-'}</td>
                        <td className="px-4 py-3 text-sm text-gray-900 dark:text-white">{transaction.subcategory?.name ?? '-'}</td>
                        <td className="px-4 py-3">
                            <TransactionTypeBadge type={transaction.type} />
                        </td>
                        <td className="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">{formatCurrency(transaction.amount)}</td>
                    </tr>
                ))}
            </tbody>
        </table>
    );
}

function AnnualDetailed({ report, availableYears, categories }: AnnualDetailedProps) {
    const [year, setYear] = useState(String(report.ano));
    const [type, setType] = useState(report.filters?.type ?? '');
    const [category, setCategory] = useState(report.filters?.category ?? '');

    const yearOptions = Object.entries(availableYears).map(([value, label]) => ({
        value: Number(value),
        label: String(label),
    }));

    const categoryOptions = categories.map((item) => ({ value: item.id, label: item.name }));

    const hasData = report.yearly_totals.entradas > 0 || report.yearly_totals.saidas > 0;

    const handleFilter = (event: FormEvent) => {
        event.preventDefault();
        router.get(
            route('financial.reports.annual.detailed'),
            { year, type: type || undefined, category: category || undefined },
            { preserveState: true },
        );
    };

    const monthlyEntries = Object.entries(report.monthly_data).sort(([a], [b]) => Number(a) - Number(b));

    return (
        <AppPage>
            <PageCard title="Ano detalhado">
                <ReportNav current="annual-detailed" />

                <form onSubmit={handleFilter} className="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mb-6">
                    <Select id="year" label="Ano" value={year} onChange={(event) => setYear(event.target.value)} options={yearOptions} placeholder="" />
                    <Select
                        id="type"
                        label="Tipo (Opcional)"
                        value={type}
                        onChange={(event) => setType(event.target.value)}
                        options={[
                            { value: 'entrada', label: 'Entrada' },
                            { value: 'saida', label: 'Saída' },
                        ]}
                        placeholder="Todos"
                    />
                    <Select
                        id="category"
                        label="Categoria (Opcional)"
                        value={category}
                        onChange={(event) => setCategory(event.target.value)}
                        options={categoryOptions}
                        placeholder="Todas"
                    />
                    <div className="flex items-end">
                        <PrimaryButton type="submit" className="px-6 py-3">
                            Filtrar
                        </PrimaryButton>
                    </div>
                </form>

                {hasData ? (
                    <>
                        <div className="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h3 className="text-lg font-semibold text-blue-800 dark:text-blue-300">Relatório Anual Detalhado de {report.ano}</h3>
                        </div>

                        <div className="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-6">
                            <h2 className="text-2xl font-bold text-blue-800 dark:text-blue-300 mb-6">Resumo Anual</h2>
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

                        <div className="space-y-8">
                            {monthlyEntries.map(([month, monthData]) =>
                                monthData.has_transactions ? (
                                    <div key={month} className="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                                        <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-6 capitalize">{monthData.mes_nome}</h3>

                                        {(monthData.transactions?.length ?? 0) > 0 && (
                                            <div className="mt-4">
                                                <h4 className="text-md font-semibold text-gray-900 dark:text-white mb-4">Todas as Transações do Mês</h4>
                                                <MonthlyTransactionsTable transactions={monthData.transactions ?? []} />
                                            </div>
                                        )}

                                        <div className="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                            <div className="text-green-600 dark:text-green-400">Entradas: {formatCurrency(monthData.total_entradas)}</div>
                                            <div className="text-red-600 dark:text-red-400">Saídas: {formatCurrency(monthData.total_saidas)}</div>
                                            <div className={monthData.saldo_mensal >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}>
                                                Saldo: {formatCurrency(monthData.saldo_mensal)}
                                            </div>
                                        </div>
                                    </div>
                                ) : null,
                            )}
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

AnnualDetailed.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default AnnualDetailed;
