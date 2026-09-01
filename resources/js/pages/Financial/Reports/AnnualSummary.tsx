import { FormEvent, useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { FilterButton } from '@/components/ui/Button';
import { Select } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { EmptyState } from '@/components/ui/EmptyState';
import { Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { formatCurrency, route } from '@/utils';
import { ReportNav, ReportTotals } from '../components/ReportNav';
import { AnnualSummaryReport } from '../types';
import { FileBarChart } from 'lucide-react';

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
            <PageCard title="Ano resumido" description={`Totais mensais de ${report.ano}, sem detalhe por categoria.`}>
                <ReportNav current="annual-summary" />

                <form onSubmit={handleFilter} className="mb-6 grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                    <Select
                        id="year"
                        label="Ano"
                        value={year}
                        onChange={(event) => setYear(event.target.value)}
                        options={yearOptions}
                        placeholder=""
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

                        <TableShell>
                            <Table>
                                <THead>
                                    <Th>Mês</Th>
                                    <Th>Entradas</Th>
                                    <Th>Saídas</Th>
                                    <Th>Saldo</Th>
                                </THead>
                                <TBody>
                                    {monthlyRows.map(([month, data]) => (
                                        <Tr key={month}>
                                            <Td className="font-medium">{data.mes_nome}</Td>
                                            <Td className="tabular text-entrada">{formatCurrency(data.total_entradas)}</Td>
                                            <Td className="tabular text-saida">{formatCurrency(data.total_saidas)}</Td>
                                            <Td className={`tabular font-medium ${data.saldo_mensal >= 0 ? 'text-entrada' : 'text-saida'}`}>
                                                {formatCurrency(data.saldo_mensal)}
                                            </Td>
                                        </Tr>
                                    ))}
                                    <Tr className="bg-muted font-semibold ">
                                        <Td>Total do ano</Td>
                                        <Td className="tabular text-entrada">{formatCurrency(report.yearly_totals.entradas)}</Td>
                                        <Td className="tabular text-saida">{formatCurrency(report.yearly_totals.saidas)}</Td>
                                        <Td className={`tabular ${report.saldo_anual >= 0 ? 'text-entrada' : 'text-saida'}`}>
                                            {formatCurrency(report.saldo_anual)}
                                        </Td>
                                    </Tr>
                                </TBody>
                            </Table>
                        </TableShell>
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

AnnualSummary.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default AnnualSummary;
