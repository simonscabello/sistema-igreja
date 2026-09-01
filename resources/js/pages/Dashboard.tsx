import { Head, Link } from '@inertiajs/react';
import { ArrowRight, Cake, Church, Music, UserPlus, Users, Wallet } from 'lucide-react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Can } from '@/components/layout/Can';
import { Avatar } from '@/components/ui/Avatar';
import { Badge } from '@/components/ui/Badge';
import { EmptyState } from '@/components/ui/EmptyState';
import { useAuthUser, useCan } from '@/hooks/useCan';
import { formatCurrency, formatNumber, route } from '@/utils';

interface Aniversariante {
    id?: number;
    nome: string;
    mobile?: string | null;
    data: string;
    tipo: string;
    foto_url: string;
    is_today: boolean;
}

interface UltimoVisitante {
    id: number;
    nome: string;
    mobile?: string | null;
    primeira_visita: string;
    wants_contact?: boolean;
}

interface ProximoCulto {
    id: number;
    date: string;
    period_label: string;
    singer: string;
    preacher: string;
    songs_count: number;
}

interface DashboardProps {
    aniversariantesDoMes: Aniversariante[];
    ultimosVisitantes: UltimoVisitante[];
    visitantesQuerendoContato?: number;
    proximoCulto?: ProximoCulto | null;
    totalMembros: number;
    totalVisitantes: number;
    totalDepartamentos: number;
    saldoAtual?: number;
}

function greeting(name: string): string {
    const hour = new Date().getHours();
    const firstName = name.split(' ')[0] ?? name;

    if (hour < 12) {
        return `Bom dia, ${firstName}`;
    }

    if (hour < 18) {
        return `Boa tarde, ${firstName}`;
    }

    return `Boa noite, ${firstName}`;
}

function todayLabel(): string {
    return new Intl.DateTimeFormat('pt-BR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
    }).format(new Date());
}

function QuickLink({
    href,
    icon,
    children,
}: {
    href: string;
    icon: React.ReactNode;
    children: React.ReactNode;
}) {
    return (
        <Link
            href={href}
            className="inline-flex min-h-touch items-center gap-2 rounded-lg border border-line bg-surface px-3 text-sm font-medium text-ink hover:border-primary/40 hover:text-primary dark:border-line-dark dark:bg-surface-dark dark:text-ink-inverse"
        >
            {icon}
            {children}
        </Link>
    );
}

function Dashboard({
    aniversariantesDoMes,
    ultimosVisitantes,
    visitantesQuerendoContato = 0,
    proximoCulto = null,
    totalMembros,
    totalVisitantes,
    saldoAtual = 0,
}: DashboardProps) {
    const user = useAuthUser();
    const canFinance = useCan('visualizar_financeiro');
    const canMembers = useCan('visualizar_membros');
    const canVisitors = useCan('visualizar_visitantes');
    const canWorship = useCan('visualizar_escalas_louvor');

    const aniversariantesHoje = aniversariantesDoMes.filter((item) => item.is_today);
    const monthName = new Intl.DateTimeFormat('pt-BR', { month: 'long' }).format(new Date());

    return (
        <AppPage>
            <Head title="Início" />
            <div className="mx-auto w-full max-w-7xl px-4 py-5 sm:px-6 sm:py-8 lg:px-8">
                <header className="mb-8">
                    <p className="text-sm capitalize text-ink-muted dark:text-ink-inverse/60">{todayLabel()}</p>
                    <h1 className="mt-1 text-2xl font-semibold tracking-tight text-ink dark:text-ink-inverse sm:text-3xl">
                        {user ? greeting(user.name) : 'Início'}
                    </h1>
                    <p className="mt-1 text-sm text-ink-muted dark:text-ink-inverse/70">
                        O que pede atenção nesta semana.
                    </p>
                </header>

                <div className="mb-8 flex flex-wrap gap-2">
                    <Can permission="criar_visitantes">
                        <QuickLink href={route('visitors.create')} icon={<UserPlus className="h-4 w-4" />}>
                            Registrar visitante
                        </QuickLink>
                    </Can>
                    <Can permission="criar_transacoes">
                        <QuickLink href={route('financial.transactions.create')} icon={<Wallet className="h-4 w-4" />}>
                            Lançar transação
                        </QuickLink>
                    </Can>
                    <Can permission="criar_membros">
                        <QuickLink href={route('members.create')} icon={<Users className="h-4 w-4" />}>
                            Novo membro
                        </QuickLink>
                    </Can>
                    <Can permission="gerenciar_escalas_louvor">
                        <QuickLink href={route('worship-sets.create')} icon={<Music className="h-4 w-4" />}>
                            Montar culto
                        </QuickLink>
                    </Can>
                </div>

                <div className="grid gap-6 lg:grid-cols-[minmax(0,1.4fr)_minmax(18rem,0.9fr)]">
                    <div className="space-y-6">
                        {canMembers && (
                            <section>
                                <div className="mb-3 flex items-end justify-between gap-3">
                                    <h2 className="text-base font-semibold text-ink dark:text-ink-inverse">
                                        Aniversariantes de {monthName}
                                    </h2>
                                    <span className="text-sm text-ink-muted tabular">{aniversariantesDoMes.length}</span>
                                </div>

                                {aniversariantesDoMes.length === 0 ? (
                                    <EmptyState
                                        title="Nenhum aniversário neste mês"
                                        description="Quando houver membros com data de nascimento cadastrada, eles aparecem aqui."
                                        icon={<Cake className="h-8 w-8" />}
                                    />
                                ) : (
                                    <ul className="divide-y divide-line overflow-hidden rounded-xl border border-line bg-surface dark:divide-line-dark dark:border-line-dark dark:bg-surface-dark">
                                        {aniversariantesDoMes.map((aniversariante, index) => {
                                            const content = (
                                                <>
                                                    <Avatar
                                                        name={aniversariante.nome}
                                                        imageUrl={aniversariante.foto_url}
                                                        size="w-10 h-10"
                                                    />
                                                    <div className="min-w-0 flex-1">
                                                        <p className="truncate font-medium text-ink dark:text-ink-inverse">
                                                            {aniversariante.nome}
                                                        </p>
                                                        <p className="text-sm text-ink-muted dark:text-ink-inverse/60">
                                                            {aniversariante.mobile ?? aniversariante.tipo}
                                                        </p>
                                                    </div>
                                                    <div className="flex shrink-0 flex-col items-end gap-1">
                                                        <span className="tabular text-sm font-medium text-ink dark:text-ink-inverse">
                                                            {aniversariante.data}
                                                        </span>
                                                        {aniversariante.is_today && <Badge tone="warning">Hoje</Badge>}
                                                    </div>
                                                </>
                                            );

                                            if (aniversariante.id) {
                                                return (
                                                    <li key={aniversariante.id}>
                                                        <Link
                                                            href={route('members.show', aniversariante.id)}
                                                            className="flex items-center gap-3 px-4 py-3 hover:bg-canvas dark:hover:bg-white/5"
                                                        >
                                                            {content}
                                                        </Link>
                                                    </li>
                                                );
                                            }

                                            return (
                                                <li key={`${aniversariante.nome}-${index}`} className="flex items-center gap-3 px-4 py-3">
                                                    {content}
                                                </li>
                                            );
                                        })}
                                    </ul>
                                )}
                            </section>
                        )}

                        {canVisitors && (
                            <section>
                                <div className="mb-3 flex items-end justify-between gap-3">
                                    <h2 className="text-base font-semibold text-ink dark:text-ink-inverse">
                                        Visitantes recentes
                                    </h2>
                                    {visitantesQuerendoContato > 0 && (
                                        <Badge tone="success">{visitantesQuerendoContato} querem contato</Badge>
                                    )}
                                </div>

                                {ultimosVisitantes.length === 0 ? (
                                    <EmptyState
                                        title="Ainda não há visitantes este mês"
                                        description="Registre quem passou no culto para o acompanhamento pastoral."
                                        icon={<UserPlus className="h-8 w-8" />}
                                    />
                                ) : (
                                    <ul className="divide-y divide-line overflow-hidden rounded-xl border border-line bg-surface dark:divide-line-dark dark:border-line-dark dark:bg-surface-dark">
                                        {ultimosVisitantes.map((visitante) => (
                                            <li key={visitante.id}>
                                                <Link
                                                    href={route('visitors.show', visitante.id)}
                                                    className="flex items-center gap-3 px-4 py-3 hover:bg-canvas dark:hover:bg-white/5"
                                                >
                                                    <div className="min-w-0 flex-1">
                                                        <p className="truncate font-medium text-ink dark:text-ink-inverse">
                                                            {visitante.nome}
                                                        </p>
                                                        <p className="text-sm text-ink-muted dark:text-ink-inverse/60">
                                                            {visitante.primeira_visita}
                                                            {visitante.mobile ? ` · ${visitante.mobile}` : ''}
                                                        </p>
                                                    </div>
                                                    {visitante.wants_contact && <Badge tone="success">Contato</Badge>}
                                                    <ArrowRight className="h-4 w-4 shrink-0 text-ink-muted" />
                                                </Link>
                                            </li>
                                        ))}
                                    </ul>
                                )}
                            </section>
                        )}
                    </div>

                    <aside className="space-y-4">
                        {(canMembers || canVisitors || canFinance) && (
                            <div className="rounded-xl border border-line bg-surface p-4 dark:border-line-dark dark:bg-surface-dark">
                                <p className="text-sm text-ink-muted dark:text-ink-inverse/60">Números</p>
                                <dl className="mt-3 space-y-3">
                                    {canMembers && (
                                        <div className="flex items-baseline justify-between gap-3">
                                            <dt className="text-sm text-ink dark:text-ink-inverse">Membros</dt>
                                            <dd className="tabular text-lg font-semibold text-ink dark:text-ink-inverse">
                                                {formatNumber(totalMembros)}
                                            </dd>
                                        </div>
                                    )}
                                    {canVisitors && (
                                        <div className="flex items-baseline justify-between gap-3">
                                            <dt className="text-sm text-ink dark:text-ink-inverse">Visitantes</dt>
                                            <dd className="tabular text-lg font-semibold text-ink dark:text-ink-inverse">
                                                {formatNumber(totalVisitantes)}
                                            </dd>
                                        </div>
                                    )}
                                    {canFinance && (
                                        <div className="flex items-baseline justify-between gap-3 border-t border-line pt-3 dark:border-line-dark">
                                            <dt className="text-sm text-ink dark:text-ink-inverse">Saldo em caixa</dt>
                                            <dd
                                                className={`tabular text-lg font-semibold ${
                                                    saldoAtual >= 0 ? 'text-entrada' : 'text-saida'
                                                }`}
                                            >
                                                {formatCurrency(saldoAtual)}
                                            </dd>
                                        </div>
                                    )}
                                </dl>
                                {canFinance && (
                                    <Link
                                        href={route('financial.dashboard.index')}
                                        className="mt-4 inline-flex items-center gap-1 text-sm font-medium text-primary hover:text-primary-dark"
                                    >
                                        Ver movimentação
                                        <ArrowRight className="h-4 w-4" />
                                    </Link>
                                )}
                            </div>
                        )}

                        {canWorship && (
                            <div className="rounded-xl border border-line bg-surface p-4 dark:border-line-dark dark:bg-surface-dark">
                                <div className="mb-3 flex items-center gap-2 text-ink dark:text-ink-inverse">
                                    <Church className="h-4 w-4 text-primary" />
                                    <h2 className="text-sm font-semibold">Próximo culto</h2>
                                </div>
                                {proximoCulto ? (
                                    <>
                                        <p className="text-lg font-semibold text-ink dark:text-ink-inverse">
                                            {proximoCulto.date}
                                            <span className="ml-2 text-sm font-medium text-ink-muted">
                                                {proximoCulto.period_label}
                                            </span>
                                        </p>
                                        <p className="mt-1 text-sm text-ink-muted dark:text-ink-inverse/70">
                                            {proximoCulto.singer ? `Cantor: ${proximoCulto.singer}` : 'Cantor ainda não definido'}
                                        </p>
                                        <p className="text-sm text-ink-muted dark:text-ink-inverse/70">
                                            {proximoCulto.songs_count} {proximoCulto.songs_count === 1 ? 'música' : 'músicas'}
                                        </p>
                                        <Link
                                            href={route('worship-sets.show', proximoCulto.id)}
                                            className="mt-3 inline-flex items-center gap-1 text-sm font-medium text-primary hover:text-primary-dark"
                                        >
                                            Abrir repertório
                                            <ArrowRight className="h-4 w-4" />
                                        </Link>
                                    </>
                                ) : (
                                    <p className="text-sm text-ink-muted dark:text-ink-inverse/70">
                                        Monte o repertório do próximo domingo.
                                    </p>
                                )}
                            </div>
                        )}

                        {aniversariantesHoje.length > 0 && canMembers && (
                            <p className="rounded-xl bg-accent-subtle px-4 py-3 text-sm text-amber-950 dark:bg-accent/20 dark:text-accent-subtle">
                                {aniversariantesHoje.length === 1
                                    ? `${aniversariantesHoje[0].nome} faz aniversário hoje.`
                                    : `${aniversariantesHoje.length} pessoas fazem aniversário hoje.`}
                            </p>
                        )}
                    </aside>
                </div>
            </div>
        </AppPage>
    );
}

Dashboard.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Dashboard;
