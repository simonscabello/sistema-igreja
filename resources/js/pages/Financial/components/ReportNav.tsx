import { Link } from '@inertiajs/react';
import { cn, route } from '@/utils';

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
        <nav className="mb-6 flex flex-wrap gap-1 rounded-xl border border-line bg-surface p-1 dark:border-line-dark dark:bg-surface-dark" aria-label="Tipos de relatório">
            {current !== 'hub' && (
                <Link
                    href={route('financial.reports.index')}
                    className="inline-flex min-h-10 items-center rounded-lg px-3 text-sm text-ink-muted hover:text-ink dark:text-ink-inverse/70"
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
                        current === item.id
                            ? 'bg-primary text-white'
                            : 'text-ink-muted hover:bg-canvas hover:text-ink dark:text-ink-inverse/70 dark:hover:bg-white/5',
                    )}
                >
                    {item.label}
                </Link>
            ))}
        </nav>
    );
}
