import { FormEvent, useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { FilterButton } from '@/components/ui/Button';
import { Select } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { EmptyState } from '@/components/ui/EmptyState';
import { Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { formatCurrency, formatDateBr, route } from '@/utils';
import { ReportNav, ReportTotals } from '../components/ReportNav';
import { TransactionTypeBadge } from '../components/StatusBadge';
import { AnnualDetailedReport, FinancialCategory, FinancialTransaction } from '../types';
import { FileBarChart } from 'lucide-react';

interface AnnualDetailedProps {
    report: AnnualDetailedReport;
    availableYears: Record<number, number>;
    categories: FinancialCategory[];
}

function MonthlyTransactionsTable({ transactions }: { transactions: FinancialTransaction[] }) {
    return (
        <TableShell>
            <Table>
                <THead>
                    <Th>Data</Th>
                    <Th>Categoria</Th>
                    <Th>Subcategoria</Th>
                    <Th>Tipo</Th>
                    <Th align="right">Valor</Th>
                </THead>
                <TBody>
                    {transactions.map((transaction) => (
                        <Tr key={transaction.id}>
                            <Td className="tabular">{formatDateBr(transaction.action_date)}</Td>
                            <Td>{transaction.subcategory?.financial_category?.name ?? '—'}</Td>
                            <Td>{transaction.subcategory?.name ?? '—'}</Td>
                            <Td>
                                <TransactionTypeBadge type={transaction.type} />
                            </Td>
                            <Td align="right" className={`tabular ${transaction.type === 'entrada' ? 'text-entrada' : 'text-saida'}`}>
                                {formatCurrency(transaction.amount)}
                            </Td>
                        </Tr>
                    ))}
                </TBody>
            </Table>
        </TableShell>
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
            <PageCard title="Ano detalhado" description={`Lançamentos de ${report.ano}, mês a mês.`}>
                <ReportNav current="annual-detailed" />

                <form onSubmit={handleFilter} className="mb-6 grid grid-cols-1 items-end gap-4 md:grid-cols-4">
                    <Select
                        id="year"
                        label="Ano"
                        value={year}
                        onChange={(event) => setYear(event.target.value)}
                        options={yearOptions}
                        placeholder=""
                    />
                    <Select
                        id="type"
                        label="Tipo"
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
                        label="Categoria"
                        value={category}
                        onChange={(event) => setCategory(event.target.value)}
                        options={categoryOptions}
                        placeholder="Todas"
                    />
                    <FilterButton />
                </form>

                {hasData ? (
                    <>
                        <ReportTotals
                            entradas={report.yearly_totals.entradas}
                            saidas={report.yearly_totals.saidas}
                            saldo={report.saldo_anual}
                            saldoLabel="Saldo do ano"
                        />

                        <div className="space-y-8">
                            {monthlyEntries.map(([month, monthData]) =>
                                monthData.has_transactions ? (
                                    <section key={month}>
                                        <h2 className="mb-3 text-base font-semibold capitalize text-foreground">{monthData.mes_nome}</h2>
                                        {(monthData.transactions?.length ?? 0) > 0 && (
                                            <MonthlyTransactionsTable transactions={monthData.transactions ?? []} />
                                        )}
                                        <dl className="mt-3 flex flex-wrap gap-x-6 gap-y-1 text-sm">
                                            <div className="text-entrada">
                                                Entradas: <span className="tabular">{formatCurrency(monthData.total_entradas)}</span>
                                            </div>
                                            <div className="text-saida">
                                                Saídas: <span className="tabular">{formatCurrency(monthData.total_saidas)}</span>
                                            </div>
                                            <div className={monthData.saldo_mensal >= 0 ? 'text-entrada' : 'text-saida'}>
                                                Saldo: <span className="tabular">{formatCurrency(monthData.saldo_mensal)}</span>
                                            </div>
                                        </dl>
                                    </section>
                                ) : null,
                            )}
                        </div>
                    </>
                ) : (
                    <EmptyState
                        title="Nenhuma transação neste ano"
                        description={`Não há lançamentos registrados para ${report.ano}.`}
                        icon={<FileBarChart className="h-8 w-8" />}
                    />
                )}
            </PageCard>
        </AppPage>
    );
}

AnnualDetailed.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default AnnualDetailed;
