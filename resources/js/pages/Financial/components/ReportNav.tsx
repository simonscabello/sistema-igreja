import { Link } from '@inertiajs/react';
import { actionFillClass } from '@/components/ui/Button';
import { cn, formatCurrency, route } from '@/utils';

type ReportPage = 'hub' | 'monthly' | 'annual-detailed' | 'annual-summary';

interface ReportNavProps {
    current: ReportPage;
}

const items: Array<{ id: ReportPage; href: string; label: string }> = [
    { id: 'monthly', href: 'financial.reports.monthly', label: 'Balancete do mês' },
    { id: 'annual-detailed', href: 'financial.reports.annual.detailed', label: 'Ano detalhado' },
    { id: 'annual-summary', href: 'financial.reports.annual.summary', label: 'Ano resumido' },
];

export function ReportNav({ current }: ReportNavProps) {
    return (
        <nav className="mb-6 flex flex-wrap gap-1 rounded-xl border bg-card p-1" aria-label="Tipos de relatório">
            {current !== 'hub' && (
                <Link
                    href={route('financial.reports.index')}
                    className="inline-flex min-h-10 items-center rounded-lg px-3 text-sm text-muted-foreground hover:text-foreground"
                >
                    Todos
                </Link>
            )}
            {items.map((item) => (
                <Link
                    key={item.id}
                    href={route(item.href)}
                    aria-current={current === item.id ? 'page' : undefined}
                    className={cn(
                        'inline-flex min-h-10 items-center rounded-lg px-3 text-sm font-medium transition-colors',
                        current === item.id ? actionFillClass : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                    )}
                >
                    {item.label}
                </Link>
            ))}
        </nav>
    );
}

export function ReportTotals({
    entradas,
    saidas,
    saldo,
    saldoLabel = 'Saldo',
}: {
    entradas: number;
    saidas: number;
    saldo: number;
    saldoLabel?: string;
}) {
    return (
        <dl className="mb-8 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div className="rounded-xl border bg-card p-4">
                <dt className="text-sm text-muted-foreground">Entradas</dt>
                <dd className="mt-1 tabular text-xl font-semibold text-entrada">{formatCurrency(entradas)}</dd>
            </div>
            <div className="rounded-xl border bg-card p-4">
                <dt className="text-sm text-muted-foreground">Saídas</dt>
                <dd className="mt-1 tabular text-xl font-semibold text-saida">{formatCurrency(saidas)}</dd>
            </div>
            <div className="rounded-xl border bg-card p-4">
                <dt className="text-sm text-muted-foreground">{saldoLabel}</dt>
                <dd className={`mt-1 tabular text-xl font-semibold ${saldo >= 0 ? 'text-entrada' : 'text-saida'}`}>
                    {formatCurrency(saldo)}
                </dd>
            </div>
        </dl>
    );
}
