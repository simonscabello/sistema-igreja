import { FormEvent, useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { FilterButton } from '@/components/ui/Button';
import { Select } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { EmptyState } from '@/components/ui/EmptyState';
import { cn, formatCurrency, route } from '@/utils';
import { ReportNav, ReportTotals } from '../components/ReportNav';
import { MonthlyReport } from '../types';
import { FileBarChart } from 'lucide-react';

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

function CategorySection({ title, data, tone }: { title: string; data: MonthlyReport['entradas']; tone: 'entrada' | 'saida' }) {
    const entries = Object.entries(data);

    if (entries.length === 0) {
        return <p className="py-6 text-sm text-muted-foreground">Nenhuma {tone === 'entrada' ? 'entrada' : 'saída'} neste período.</p>;
    }

    return (
        <div className="space-y-4">
            {entries.map(([categoryName, categoryData]) => (
                <div key={categoryName} className="overflow-hidden rounded-xl border bg-card">
                    <div
                        className={cn(
                            'border-b px-4 py-3',
                            tone === 'entrada'
                                ? 'border-entrada/20 bg-entrada/10 dark:bg-entrada/15'
                                : 'border-saida/20 bg-saida/10 dark:bg-saida/15',
                        )}
                    >
                        <h3 className={cn('font-semibold', tone === 'entrada' ? 'text-entrada' : 'text-saida')}>{categoryName}</h3>
                    </div>
                    <div className="p-4">
                        <div className="space-y-2">
                            {Object.entries(categoryData.subcategorias).map(([subcategoryName, value]) => (
                                <div
                                    key={subcategoryName}
                                    className="flex items-center justify-between border-b py-2 last:border-b-0"
                                >
                                    <span className="text-sm text-foreground">{subcategoryName}</span>
                                    <span className={cn('tabular font-medium', tone === 'entrada' ? 'text-entrada' : 'text-saida')}>
                                        {formatCurrency(value)}
                                    </span>
                                </div>
                            ))}
                        </div>
                        <div className="mt-4 flex justify-between border-t pt-3">
                            <span className="font-semibold text-foreground">Total da categoria</span>
                            <span className={cn('tabular text-lg font-semibold', tone === 'entrada' ? 'text-entrada' : 'text-saida')}>
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
            <PageCard
                title="Balancete mensal"
                description={`${report.periodo.mes_nome.charAt(0).toUpperCase()}${report.periodo.mes_nome.slice(1)} de ${report.periodo.ano}`}
            >
                <ReportNav current="monthly" />

                <form onSubmit={handleFilter} className="mb-6 grid grid-cols-1 items-end gap-4 md:grid-cols-3">
                    <Select
                        id="month"
                        label="Mês"
                        value={month}
                        onChange={(event) => setMonth(event.target.value)}
                        options={MONTH_OPTIONS}
                    />
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
                            entradas={report.total_entradas}
                            saidas={report.total_saidas}
                            saldo={report.saldo_mensal}
                            saldoLabel="Saldo do mês"
                        />

                        <section className="mb-8">
                            <h2 className="mb-4 text-base font-semibold text-entrada">Entradas</h2>
                            <CategorySection title="Entradas" data={report.entradas} tone="entrada" />
                        </section>

                        <section>
                            <h2 className="mb-4 text-base font-semibold text-saida">Saídas</h2>
                            <CategorySection title="Saídas" data={report.saidas} tone="saida" />
                        </section>
                    </>
                ) : (
                    <EmptyState
                        title="Nenhuma transação neste período"
                        description={`Não há lançamentos em ${report.periodo.mes_nome} de ${report.periodo.ano}.`}
                        icon={<FileBarChart className="h-8 w-8" />}
                    />
                )}
            </PageCard>
        </AppPage>
    );
}

Monthly.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Monthly;
