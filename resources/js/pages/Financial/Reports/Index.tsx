import { Link } from '@inertiajs/react';
import { ArrowRight, CalendarDays, ChartColumn, LayoutList } from 'lucide-react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

const reports = [
    {
        href: 'financial.reports.monthly',
        title: 'Balancete do mês',
        use: 'Para a reunião de tesouraria: entradas e saídas agrupadas por categoria.',
        icon: CalendarDays,
    },
    {
        href: 'financial.reports.annual.detailed',
        title: 'Ano detalhado',
        use: 'Para conferir lançamento a lançamento, mês a mês.',
        icon: LayoutList,
    },
    {
        href: 'financial.reports.annual.summary',
        title: 'Ano resumido',
        use: 'Para ver o saldo de cada mês, sem o detalhe das categorias.',
        icon: ChartColumn,
    },
];

function Index() {
    return (
        <AppPage>
            <PageCard
                title="Relatórios"
                description="Escolha o recorte. Os números vêm das transações já lançadas."
                breadcrumbs={[{ label: 'Finanças', href: route('financial.dashboard.index') }, { label: 'Relatórios' }]}
            >
                <ul className="divide-y divide-line overflow-hidden rounded-xl border border-line bg-surface dark:divide-line-dark dark:border-line-dark dark:bg-surface-dark">
                    {reports.map((report) => {
                        const Icon = report.icon;

                        return (
                            <li key={report.href}>
                                <Link
                                    href={route(report.href)}
                                    className="flex items-start gap-4 px-4 py-4 hover:bg-canvas dark:hover:bg-white/5 sm:px-5"
                                >
                                    <span className="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                        <Icon className="h-5 w-5" />
                                    </span>
                                    <span className="min-w-0 flex-1">
                                        <span className="block font-semibold text-ink dark:text-ink-inverse">{report.title}</span>
                                        <span className="mt-0.5 block text-sm text-ink-muted dark:text-ink-inverse/70">{report.use}</span>
                                    </span>
                                    <ArrowRight className="mt-2 h-4 w-4 shrink-0 text-ink-muted" />
                                </Link>
                            </li>
                        );
                    })}
                </ul>
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
