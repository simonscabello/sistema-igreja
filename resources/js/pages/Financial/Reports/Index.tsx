import { Link } from '@inertiajs/react';
import { ArrowRight, CalendarDays, ChartColumn, LayoutList } from 'lucide-react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { PageCard } from '@/components/ui/PageCard';
import { Card, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
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
                <div className="grid gap-4 md:grid-cols-3">
                    {reports.map((report) => {
                        const Icon = report.icon;

                        return (
                            <Link key={report.href} href={route(report.href)} className="group">
                                <Card className="h-full shadow-none transition-colors group-hover:bg-muted/40">
                                    <CardHeader>
                                        <span className="mb-2 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-muted">
                                            <Icon className="h-5 w-5" />
                                        </span>
                                        <CardTitle className="flex items-center justify-between gap-2 text-base">
                                            {report.title}
                                            <ArrowRight className="h-4 w-4 text-muted-foreground transition-transform group-hover:translate-x-0.5" />
                                        </CardTitle>
                                        <CardDescription>{report.use}</CardDescription>
                                    </CardHeader>
                                </Card>
                            </Link>
                        );
                    })}
                </div>
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
